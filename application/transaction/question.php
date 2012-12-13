<?php

namespace App\Transaction;

use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\User as Model_User;
use \App\Model\Tag as Model_Tag;
use \Zx\Message\Message;
use \Zx\Model\Mysql;

//use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Question {

    /**
     * in controller, it must have title, content and tag_names
     * if user has logged in, fill the user id and status=1 (active)
     * otherwise, fill the user id with default question user id and status=0 (inactive)
     * @param type $arr
     */
    public static function create($arr = array()) {
        //\Zx\Test\Test::object_log('$_SESSION',$_SESSION, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (isset($_SESSION['user'])) {
            $uid = $_SESSION['user']['uid'];
            $uname = $_SESSION['user']['uname'];
            $status = 1;
        } else {
            $user = Model_User::get_default_question_user();
            $uid = $user['id'];
            $uname = $user['uname'];
            $status = 0;
        }

        //prepare tag ids
        $tags = explode('@', $arr['tag_names']);
        $tag_ids = '';
            foreach ($tags as $tag) {
                if ($existing_tag = Model_Tag::exist_tag_by_tag_name($tag)) {
                    Model_Tag::increase_num_of_questions($existing_tag['id']);
                    $tag_ids .= $existing_tag['id'] . '@';
                } else {
                    $tag_arr = array('name' => $tag, 'num_of_questions' => 1);  //must have one now
                    //\Zx\Test\Test::object_log('$tag_arr', $tag_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
                    
                    $tag_id = Model_Tag::create($tag_arr);
                    $tag_ids .= $tag_id . '@';
                }
            }
        $arr['tag_ids'] = $tag_ids;
        $arr['uid'] = $uid;
        $arr['uname'] = $uname;
        $arr['status'] = $status;
        $arr['num_of_answers'] = 0;
        $arr['num_of_views'] = 0;
        $arr['num_of_votes'] = 0;
        Model_Question::create($arr);
        Model_User::increase_num_of_questions($uid);
        return true;
    }

    /**
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


//prepare tag ids
            $tags = explode('@', $arr['tag_names']);
            foreach ($tags as $tag) {
                if ($tag_id = Model_Tag::exist($tag)) {
                    $tag_ids .= $tag_id . '@';
                } else {
                    $tag_arr = array('name' => $tag, 'num_of_questions' => 1);
                    $tag_id = Model_Tag::create($tag_arr);
                    $tag_ids .= $tag_id . '@';
                }
            }
            $arr['tag_ids'] = $tag_ids;
            if (Model_Question::create($arr)) {
                Message::set_success_message('success');
                Model_User::increase_num_of_questions($arr['uid']);
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('请填写完整标题和内容。');
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
            $tags = explode('@', $arr['tag_names']);
        //\Zx\Test\Test::object_log('$tags', $tags, __FILE__, __LINE__, __CLASS__, __METHOD__);
            
            $tag_ids = '';
            
            foreach ($tags as $tag) {
                if ($existing_tag = Model_Tag::exist_tag_by_tag_name($tag)) {
                    Model_Tag::increase_num_of_questions($existing_tag['id']);
                    $tag_ids .= $existing_tag['id'] . '@';
                } else {
                    $tag_arr = array('name' => $tag, 'num_of_questions' => 1);  //must have one now
                    //\Zx\Test\Test::object_log('$tag_arr', $tag_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
                    
                    $tag_id = Model_Tag::create($tag_arr);
                    $tag_ids .= $tag_id . '@';
                }
            }

            $question_user = Model_User::get_random_user();
            //\Zx\Test\Test::object_log('$question_user', $question_user, __FILE__, __LINE__, __CLASS__, __METHOD__);
            
            $answer_user = Model_User::get_random_user();
            $question_arr = array('title' => $arr['title'],
                'content' => $arr['q_content'],
                'uid' => $question_user['id'],
                'uname' => $question_user['uname'],
                'tag_ids' => substr($tag_ids, -1), //remove last '@'
                'tag_names' => $arr['tag_names'],
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
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('请填写完整各项。');
            return false;
        }
    }

    /**
     * tag names change, tag ids change, and num_of_questions of tags change
     * hint: array_diff($arr1, $arr2), if an element in arr1, but not in arr2, it will be in the result array
     * @param type $id
     * @param type $arr
     * @return boolean
     */
    public static function update_question($id = 0, $arr = array()) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

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
            $old_tags = explode('@', $question['tag_names']);
            //$new_tag_names = $arr['tag_names'];

            $new_tags = explode('@', $arr['tag_names']);

            $new_difference = array_diff($new_tags, $old_tags);
            $old_difference = array_diff($old_tags, $new_tags);
            //new tags, increase num of questions of tag or insert new tag record
            if (count($new_difference) > 0) {
                //has new tags
                foreach ($new_difference as $tag) {
                    if ($tag_id = Model_Tag::exist($tag)) {
                        //$tag_ids .= $tag_id . '@';
                        Model_Tag::increase_num_of_questions($tag_id);
                    } else {
                        //brand new tag will be inserted into tag table
                        $tag_arr = array('name' => $tag, 'num_of_questions' => 1);
                        $tag_id = Model_Tag::create($tag_arr);
                        //$tag_ids .= $tag_id . '@';
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
                        $tag_ids .= $tag_id . '@';
                        $arr['tag_ids'] = $tag_ids;
                    }
                }
            }
            if (Model_Question::update($id, $arr)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('wrong info');
            return false;
        }
    }

    public static function delete_question($id) {
        if (Model_Question::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
            return false;
        }
    }

    /**
     * for cron job
     * backup question table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM question";
        $r = Mysql::select_all($sql);
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