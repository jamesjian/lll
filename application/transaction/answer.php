<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Answer as Model_Answer;
use \App\Model\Question as Model_Question;
use \App\Model\User as Model_User;
use \Zx\Message\Message as Zx_Message;
use \Zx\Model\Mysql;
use \App\Transaction\User as Transaction_User;
use \App\Transaction\Staff as Transaction_Staff;

//use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Answer {

    /**
     * mainly for front end
     * @param array $answer  record
     * @return string 
     */
    public static function get_link($answer) {
        $link = FRONT_HTML_ROOT . 'answer/content/' . $answer['id1'];
        return $link;
    }

    /**
     * answer question
     * 1. num of answer for question 
     * 1. num of answer and score for user
     *  
     * @param array $arr
     * @return boolean
     */
    public static function reply_question($arr = array()) {
        if (isset($_SESSION['user'])) {
            $uid = $_SESSION['user']['uid'];
            $uname = $_SESSION['user']['uname'];
        } else {
            $user = Model_User::get_default_answer_user();
            $uid = $user['id'];
            $uname = '匿名回答用户';
        }
        $arr['uid'] = $uid;
        $arr['uname'] = $uname;
        $status = Model_Answer::S_ACTIVE;
        if (count($arr) > 0 &&
                isset($arr['content']) && trim($arr['content']) != ''
        ) {
            $arr['num_of_votes'] = 0;
            if (Model_Answer::create($arr)) {
                Model_Question::increase_num_of_answers($arr['qid']);
                $arr = array('score'=>$user['score']+SCORE_OF_ANSWER,
                    'num_of_answers'=>$user['num_of_answers']+1,);
                Model_User::update($uid, $arr);
                Zx_Message::set_success_message('感谢您回答问题。');
                return true;
            } else {
                Zx_Message::set_error_message(SYSTEM_ERROR_MESSAGE);
                return false;
            }
        } else {
            Zx_Message::set_error_message('请填写内容。');
            return false;
        }
    }

    /**
     * user id is not from session, it's from form
     */
    public static function create_answer_by_admin($arr = array()) {
        
    }

    /**

     * if an answer status is S_CLAIMED, S_DISABLED or S_DELETED, it cannot be updated
     * 
     * if status is S_CORRECT, it will be changed to S_ACTIVE
     * 
     * @param type $id
     * @param type $arr
     * @return boolean
     */
    public static function update_by_user($id, $arr) {
//\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $answer = Model_Answer::get_one($id);
        if (
                $answer['status'] != Model_Answer::S_CLAIMED &&
                $answer['status'] != Model_Answer::S_DELETED &&
                $answer['status'] != Model_Answer::S_DISABLED
        ) {
            if (count($arr) > 0 && isset($arr['content'])) {
                if ($answer['status'] == Model_Answer::S_CORRECT) {
                    $arr['status'] = Model_Answer::S_ACTIVE;
                }
                if (Model_Answer::update($id, $arr)) {
                    Zx_Message::set_success_message('回答更新成功');
                    return true;
                } else {
                    Zx_Message::set_error_message('系统出错。');
                    return false;
                }
            } else {
                Zx_Message::set_error_message('提供信息不全');
                return false;
            }
        } else {
            Zx_Message::set_error_message('该回答被举报或被删除或被禁止显示， 目前无法更新。');
        }
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
     * status is not involved
     * @param type $id
     * @param type $arr
     * @return boolean
     */
    public static function update_by_admin($id, $arr) {
//\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $answer = Model_Answer::get_one($id);
        if (count($arr) > 0 && Model_Answer::update($id, $arr)) {
            Zx_Message::set_success_message('回答更新成功');
            return true;
        } else {
            Zx_Message::set_error_message('系统出错。');
            return false;
        }
    }

    /**
     * status cannot be changed by user
     * status can be changed only by admin, but it's different from claim process
     * this method only adds score, never decreases score
     * 1. so when  change S_DELETED / S_DISABLED to S_ACTIVE / S_CORRECT, will add score
     * 2. others just update 
     * 3. if new status is S_CLAIMED, ignore it, because 
     *   admin no need to set status to claimed
     * 
     * @param type $id
     * @param type $arr  only has arr['status'] 
     * @return boolean
     */
    public static function update_status_by_admin($id, $arr) {
//\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $answer = Model_Answer::get_one($id);
        if ($answer && isset($arr['status']) && $arr['status'] <> $answer['status']
                && $arr['status'] <> Model_Answer::S_CLAIMED) {
            //if change
            if (($answer['status'] == Model_Answer::S_DELETED || $answer['status'] == Model_Answer::S_DISABLED)
                    && $arr['status'] <> Model_Answer::S_DELETED && $arr['status'] <> Model_Answer::S_DISABLED) {
                //if add score
                $uid = $answer['uid'];
                Model_User::increase_num_of_answers($uid);
                Model_User::increase_score($uid, SCORE_OF_ANSWER);
            }
            if (Model_Answer::update($id, $arr)) {
                Zx_Message::set_success_message('回答更新成功');
                return true;
            } else {
                Zx_Message::set_error_message('系统出错。');
                return false;
            }
        } else {
                //nothing to do 
        }
    }

    /**
     * only user who give this answer and admin can delete it
     * 
     * the score will be decreased in user table
     * 
     * @param type $id
     * @return boolean
     */
    public static function delete_answer($id) {
        $can_be_deleted = false;
        $answer = Model_Answer::get_one($id);
        if ($answer) {
            $uid = $answer['uid'];
            $score = $answer['num_of_votes'];

            if (Transaction_User::user_has_loggedin() && $uid == Transaction_User::get_uid()) {
                $can_be_deleted = true;
            } elseif (Transaction_Staff::staff_has_loggedin()) {
                $can_be_deleted = true;
            }
            if ($can_be_deleted) {
                Model_Answer::delete($id);
                Model_Question::decrease_num_of_answers($answer['qid']);
                Model_User::decrease_score($uid, $score);
                Zx_Message::set_success_message('success');
                return true;
            } else {
                Zx_Message::set_error_message('fail');
                return false;
            }
        }
    }

    /**
     * for cron job
     * backup answer table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM answer";
        $r = Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO answer VALUES ';
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

    /*
      $arr['aids']包括回答ID, 可以有以下几种格式
      例1. <1000， 我的所有ID小于1000的回答都显示本广告
      例2. >1000， 我的所有ID大于1000的回答都显示本广告
      例3. >1000<2000， 我的所有ID大于1000且小于2000的回答都显示本广告
      例4. 1,3,5,8,9，  我的ID是1,3,5,8,9的回答都显示本广告
     * ad id must belong to user id (it's judged in controller)
     */

    public static function link_ad($arr) {
        $ad_id = $arr['ad_id'];
        $uid = $arr['uid'];

        $aids = $arr['aids'];
        if (strpos($aids, '<')) {
            $domain = 'less than';
            $aid = intval(str_replace('<', '', $aids));
            $update = 'UPDATE ' . Model_Answer::$table . ' SET ad_id=' . $ad_id . ' WHERE id<=' . $aid . ' AND uid=' . $uid;
        } elseif (strpos($aids, '>')) {
            $domain = 'more than';
            $aid = intval(str_replace('>', '', $aids));
            $update = 'UPDATE ' . Model_Answer::$table . ' SET ad_id=' . $ad_id . ' WHERE id<=' . $aid . ' AND uid=' . $uid;
        } elseif (strpos($aids, 'between')) {
            $domain = 'between';
            $aids = intval(str_replace('between', '', $aids));
            $aids = explod(',', $aids);
            if (intval($aids[0]) <= intval($aids[1])) {
                $low = intval($aids[0]);
                $high = intval($aids[1]);
            } else {
                $low = intval($aids[1]);
                $high = intval($aids[0]);
            }
            $update = 'UPDATE ' . Model_Answer::$table . ' SET ad_id=' . $ad_id . ' WHERE id>=' . $low . ' AND id<=' . $high . ' AND uid=' . $uid;
        } else {
            $domain = 'equal';
            $aids = explode(',', $aids);
            $update = 'UPDATE ' . Model_Answer::$table . ' SET ad_id=' . $ad_id . ' WHERE  uid=' . $uid . ' AND (';
            foreach ($aids as $aid) {
                $update .= ' aid=' . $aid . ' OR ';
            }
            $update = substr($update, 0, -4) . ')'; //remove last 'OR', and ')' 
        }
        Mysql::exec($update);
//when link, the ad display date will be extended
        $arr = array('date_start' => date('Y-m-d h:i:s'),
            'date_end' => date * 'Y-m-d h:i:s', strtotime('+' . DAYS_OF_AD . ' days'),
        );
        Model_Ad::update($ad_id, $arr);
    }

}