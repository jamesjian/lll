<?php

namespace App\Transaction;

use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\User as Model_User;
use \App\Model\Tag as Model_Tag;
use \App\Model\Score as Model_Score;
use \Zx\Message\Message as Zx_Message;
use \Zx\Model\Mysql as Zx_Mysql;

//use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Question {
    /**
     * increase num of views in question and tag table
     * @param int $id
     */
    public static function increase_num_of_views($id)
    {
        Model_Question::increase_num_of_views($id);
        $question = Model_Question::get_one($id);
        $tids = substr($question['tids'],1,-1);
        Model_Tag::increase_num_of_views_by_tids($tids);
        return true;
    }


    /**
     * in controller, it must have title, content and tnames
     * if user has logged in, fill the user id and status=1 (active)
     * otherwise, fill the user id with default question user id and status=0 (inactive)
     * $arr['tnames'] is an array (tag1, tag2, tag3, tag4, tag5), can be less than 5
     * when create a new question:
     * 1. a new record in question table
     * 2. records in tag table, increase num_of_questions for each tag
     * 3. if loggedin user, increase num_of_questions for this user
     * 
     * @param type $arr
     */
    public static function create($arr = array()) {
        //\Zx\Test\Test::object_log('$_SESSION',$_SESSION, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (isset($_SESSION['user'])) {
            $uid = $_SESSION['user']['uid'];
            $uname = $_SESSION['user']['uname'];
        } else {
            $user = Model_User::get_default_question_user();
            $uid = $user['id'];
            $uname = $user['uname'];
        }
        //prepare tag ids
        $tids = TNAME_SEPERATOR;
        $arr['tnames'] = array_unique($arr['tnames']); //remove duplicate entry
        foreach ($arr['tnames'] as $tag) {
            if ($existing_tag = Model_Tag::exist_tag_by_tag_name($tag)) {
                if (Model_Tag::is_active_tag($tag)) {
                    $tid = $existing_tag['id'];
                    Model_Tag::increase_num_of_ads($tid);
                    $tids .= $tid . TNAME_SEPERATOR;
                } else {
                    //disabled tag cannot be added into column
                }
            } else {
                $tag_arr = array('name' => $tag, 'num_of_questions' => 1);  //have one already
                $tid = Model_Tag::create($tag_arr);
                $tids .= $tid . TNAME_SEPERATOR;
            }
        }
        $arr['tids'] = $tids;
        $arr['tnames'] = TNAME_SEPERATOR . implode(TNAME_SEPERATOR, $arr['tnames']) . TNAME_SEPERATOR; //array to string
        $arr['uid'] = $uid;
        $arr['uname'] = $uname;
        $arr['status'] = Model_Question::S_ACTIVE;
        $arr['num_of_answers'] = 0;
        $arr['num_of_views'] = 0;
        $arr['num_of_votes'] = 0;
        $qid = Model_Question::create($arr);
        $arr = array('num_of_questions'=>$user['num_of_questions']+1,
            'score'=>$user['score'] + SCORE_OF_CREATE_QUESTION,
            );
        Model_User::update($uid, $arr);
        Model_Score::create_question($uid, $qid, $user['score'], SCORE_OF_CREATE_QUESTION);
        return true;
    }

    /**
     * outdated
     * add new tags into tag table 
     * put tag ids into question table
     * @param int $arr
     * @return boolean
     */
    public static function create_question($arr = array()) {
        if (count($arr) > 0 &&
                isset($arr['title']) && trim($arr['title']) != '' &&
                isset($arr['content']) && trim($arr['content']) != ''
        ) {
            $arr['num_of_answers'] = 0;
            $arr['num_of_views'] = 0;
            $arr['num_of_votes'] = 0;

            $arr['tnames'] = array_unique($arr['tnames']); //remove duplicate entry
//prepare tag ids
            $tags = explode(TNAME_SEPERATOR, $arr['tnames']);
            foreach ($tags as $tag) {
                if ($tag_id = Model_Tag::exist($tag)) {
                    $tids .= $tag_id . TNAME_SEPERATOR;
                } else {
                    $tag_arr = array('name' => $tag, 'num_of_questions' => 1);
                    $tag_id = Model_Tag::create($tag_arr);
                    $tids .= $tag_id . TNAME_SEPERATOR;
                }
            }
            $arr['tids'] = $tids;
            if (Model_Question::create($arr)) {
                Zx_Message::set_success_message('success');
                Model_User::increase_num_of_questions($arr['uid']);
                return true;
            } else {
                Zx_Message::set_error_message('fail');
                return false;
            }
        } else {
            Zx_Message::set_error_message('请填写完整标题和内容。');
            return false;
        }
    }

    /**
     * for admin to create a question, an answer, question user and answer user in one step
      1. create tag if tag is new,  tag(num_of_question)
      2. choose question user, num_of_question
      3. choose answer user,  num_of_answer
      4. create question, num_of_answer, use tag ids, question user id
      5. create answer, use question id and answer user id
     * @param int $arr
     * @return boolean

      。
     */
    public static function create_question_and_answer_by_admin($arr = array()) {
        if (count($arr) > 0 &&
                isset($arr['title']) && trim($arr['title']) != '' &&
                isset($arr['q_content']) && trim($arr['q_content']) != ''
        ) {
            //prepare tag ids
            $tags = explode(TNAME_SEPERATOR, $arr['tnames']);
            //\Zx\Test\Test::object_log('$tags', $tags, __FILE__, __LINE__, __CLASS__, __METHOD__);

            $tids = TNAME_SEPERATOR;
            $tnames = TNAME_SEPERATOR;

            foreach ($tags as $tag) {
                if ($existing_tag = Model_Tag::exist_tag_by_tag_name($tag)) {
                    Model_Tag::increase_num_of_questions($existing_tag['id']);
                    $tids .= $existing_tag['id'] . TNAME_SEPERATOR;
                    $tnames .= $tag . TNAME_SEPERATOR;
                } else {
                    $tag_arr = array('name' => $tag, 'num_of_questions' => 1);  //must have one now
                    //\Zx\Test\Test::object_log('$tag_arr', $tag_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

                    $tag_id = Model_Tag::create($tag_arr);
                    $tids .= $tag_id . TNAME_SEPERATOR;
                    $tnames .= $tag . TNAME_SEPERATOR;
                }
            }

            $question_user = Model_User::get_random_user();
            //\Zx\Test\Test::object_log('$question_user', $question_user, __FILE__, __LINE__, __CLASS__, __METHOD__);

            $answer_user = Model_User::get_random_user();
            $question_arr = array('title' => $arr['title'],
                'content' => $arr['q_content'],
                'uid' => $question_user['id'],
                'uname' => $question_user['uname'],
                'tids' => $tids,
                'tnames' => $tnames,
                'region' => $arr['region'],
                'status' => 1,
                'num_of_answers' => 1, //must have one now
                'num_of_views' => 0,
                'num_of_votes' => 0,
            );
            //\Zx\Test\Test::object_log('$question_arr', $question_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

            if ($qid = Model_Question::create($question_arr)) {
                $question_user_arr = array('num_of_questions' => $question_user['num_of_questions'] + 1);
                //\Zx\Test\Test::object_log('$question_user_arr', $question_user_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

                Model_User::update($question_uid, $question_user_arr);
                //if answer is not empty
                $answer_arr = array('qid' => $qid,
                    'content' => $arr['a_content'],
                    'uid' => $answer_user['id'],
                    'uname' => $answer_user['uname'],
                    'status' => 1,
                );
                //\Zx\Test\Test::object_log('$answer_arr', $answer_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

                Model_Answer::create($answer_arr);
                $answer_user_arr = array('num_of_answers' => $answer_user['num_of_answers'] + 1);
                //\Zx\Test\Test::object_log('$answer_user_arr', $answer_user_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

                Model_User::update($answer_uid, $answer_user_arr);
                Zx_Message::set_success_message('success');
                return true;
            } else {
                Zx_Message::set_error_message('fail');
                return false;
            }
        } else {
            Zx_Message::set_error_message('请填写完整各项。');
            return false;
        }
    }
    /**
     * 
     * @param int $id
     * @param int $status
     */
public static function update_status($id, $status)
{
    $question = Model_Question::get_one($id);
        if ($ad) {
             $arr = array('status'=>$status);
            switch ($status) {
                case Model_Question::S_ACTIVE: 
                    break;
                case Model_Question::S_CLAIMED: 
                    break;
                case Model_Question::S_CORRECT: 
                    break;
                case Model_Question::S_DELETED: 
                    break;                
                case Model_Question::S_DISABLED: 
                    break;                
            }
            Model_Question::update($id, $arr);
        } else {
            Zx_Message::set_error_message('无效记录。');
        }
}
    /**
     * status is not involved
     * if a question status is S_CLAIMED, S_DISABLED or S_DELETED, it cannot be updated
     * 
     * tag names change, tag ids change, and num_of_questions of tags change
     * hint: array_diff($arr1, $arr2), if an element in arr1, but not in arr2, it will be in the result array
     * 
     * 1. title and content change 
     * 2. tag change
     *    new tag, old tag
     *     num_of_questions for these tags
     * 
     * @param type $id
     * @param array $arr  $arr['tnames'] is an array
     * @return boolean
     */
    public static function update($id = 0, $arr = array()) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $question = Model_Question::get_one($id);
        if ($question['status'] == Model_Question::S_CLAIMED)
        if (count($arr) > 0) {
            /*             * **
             * prepare tag ids
             * 1.compare original tags and current tags
             *   if no difference, ignore them
             *   if has new tags, 
             *     if brand new, insert tag record
             *      else increase num_of_questions of this tag
             *  if remove old tags, 
             *     decrease num_of_questions of this tag
             * 
             * 
             */
            $question = Model_Question::get_one($id);
            $old_tags = explode(TNAME_SEPERATOR, substr($question['tnames'], 1, -1)); //remove first and last TNAME_SEPERATOR
            //$new_tnames = $arr['tnames'];
            $arr['tnames'] = array_unique($arr['tnames']); //remove duplicate
            $new_tags = $arr['tnames']; //it's an array already

            $new_difference = array_diff($new_tags, $old_tags);
            $old_difference = array_diff($old_tags, $new_tags);
            //new tags, increase num of questions of tag or insert new tag record
            if (count($new_difference) > 0) {
                //has new tags
                foreach ($new_difference as $tag) {
                    if ($tag_id = Model_Tag::exist($tag)) {
                        //$tids .= $tag_id . TNAME_SEPERATOR;
                        Model_Tag::increase_num_of_questions($tag_id);
                    } else {
                        //brand new tag will be inserted into tag table
                        $tag_arr = array('name' => $tag, 'num_of_questions' => 1);
                        $tag_id = Model_Tag::create($tag_arr);
                        //$tids .= $tag_id . TNAME_SEPERATOR;
                    }
                }
            }
            //old tags, decrease num of questions of tag
            if (count($old_difference) > 0) {
                //has new tags
                foreach ($new_difference as $tag) {
                    if ($tag_id = Model_Tag::exist($tag)) {
                        //must exist
                        Model_Tag::decrease_num_of_questions($tag_id);
                    }
                }
            }
            if (count($new_difference) > 0 || count($old_difference) > 0) {
                //means different from the original, update tag ids column
                foreach ($new_tags as $tag) {
                    if ($tag_id = Model_Tag::exist($tag)) {
                        //must exist
                        $tids .= $tag_id . TNAME_SEPERATOR;
                        $arr['tids'] = $tids;
                    }
                }
            }
            $arr['tnames'] = TNAME_SEPERATOR . implode(TNAME_SEPERATOR, $arr['tnames']) . TNAME_SEPERATOR; //array to string
            $arr['status'] = Model_Question::S_ACTIVE; //anytime updated, the status will be reset to S_ACTIVE, can be claimed
            if (Model_Question::update($id, $arr)) {
                Zx_Message::set_success_message('更新问题成功');
                return true;
            } else {
                Zx_Message::set_error_message(SYSTEM_ERROR_MESSAGE);
                return false;
            }
        } else {
            Zx_Message::set_error_message('问题信息不完整。');
            return false;
        }
    }

    /**
     * can_be_deleted() is checked in controller
     * after deletion, 
     * 1. user table num_of_questions and score will be decreased
     * 2. tag table num_of_questions will be decreased
     * @param type $id 
     * @return boolean
     */
    public static function delete_question($id) {
        $question = Model_Question::get_one($id);
        $user = Model_User::get_one($question['uid']);
        $arr = array('status' => Model_Question::S_DELETED);
        if (Model_Question::update($id, $arr)) {
            $arr = array('num_of_questions'=>$user['num_of_questions'] - 1,
                'score'=>$user['score'] -  SCORE_OF_QUESTION);
            Model_User::update($uid, $arr);
            $tids = explode(TNAME_SEPERATOR, substr($question['tids'], 1, -1)); //remove prefix and trailing seperator
            Model_Tag::decrease_num_of_questions_by_tids($tids);
            Zx_Message::set_success_message('问题已被删除。');
            return true;
        } else {
            Zx_Message::set_error_message('系统出错， 请重试或与管理员联系。');
            return false;
        }
    }

    /**
     * for cron job
     * backup question table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM question";
        $r = Zx_Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO question VALUES ';
            foreach ($r as $row) {
                $fields = '';
                foreach ($row as $value) {
                    $fields .= '"' . $value . '",';
                }
                $fields = substr($fields, 0, -1); //remove last ','
                $str .= '(' . $fields . '),';
            }
            $str = substr($str, 0, -1); //remove last ','
            return $str;
            //Transaction_Swiftmail::send_string_to_admin($str);
        }
    }

}