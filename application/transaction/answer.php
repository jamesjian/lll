<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Answer as Model_Answer;
use \App\Model\Question as Model_Question;
use \App\Model\User as Model_User;
use \Zx\Message\Message;
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
    public static function get_link($answer)
    {
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
                Model_User::increase_num_of_answers($arr['uid']);
                Message::set_success_message('感谢您回答问题。');
                return true;
            } else {
                Message::set_error_message(SYSTEM_ERROR_MESSAGE);
                return false;
            }
        } else {
            Message::set_error_message('请填写内容。');
            return false;
        }
    }

    /**
     * user id is not from session, it's from form
     */
    public static function create_answer_by_admin($arr = array()) {
        
    }

    public static function update_answer($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (count($arr) > 0 && (isset($arr['title']) || isset($arr['content']))) {
            if (Model_Answer::update($id, $arr)) {
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
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
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
            $aids = explod(',',$aids);
            if (intval($aids[0])<=intval($aids[1])) {
                $low = intval($aids[0]); $high = intval($aids[1]);
            } else {
                $low = intval($aids[1]); $high = intval($aids[0]);
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