<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Answer as Model_Answer;
use \App\Model\Question as Model_Question;
use \App\Model\User as Model_User;
use \Zx\Message\Message;
use \Zx\Model\Mysql;

//use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Answer {

    public static function reply_question($arr = array()) {
        if (isset($_SESSION['user'])) {
            $uid = $_SESSION['user']['uid'];
            $uname = $_SESSION['user']['uname'];
            $status = 1;
        } else {
            $uid = Model_User::get_default_question_uid();
            $uname = '匿名回答用户';
            $status = 0;
        }
        $arr['uid'] = $uid;
        $arr['uname'] = $uname;
        $status = $status;
        if (count($arr) > 0 &&
                isset($arr['content']) && trim($arr['content']) != ''
        ) {
            if (!isset($arr['rank']))
                $arr['rank'] = 0; //initialize

            if (Model_Answer::create($arr)) {
                Model_Question::increase_num_of_answers($arr['qid']);
                Model_User::increase_num_of_answers($arr['uid']);
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
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

    public static function delete_answer($id) {
        if (Model_Answer::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
            return false;
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
     * <1000
     * >10000
     * 1,3,5,6
     * ad id must belong to user id (it's judged in controller)
     */

    public static function link_ad($arr) {
        $ad_id = $arr['ad_id'];
        $uid = $arr['uid'];

        $aids = $arr['aids'];
        if (strpos($aids, '<')) {
            $domain = 'less than';
            $aid = intval(str_replace('<', '', $aids));
            $update = 'UPDATE ' . Model_Answer::$table . ' SET ad_id=' . $ad_id . 'WHERE id<=' . $aid . ' AND uid=' . $uid;
        } elseif (strpos($aids, '>')) {
            $domain = 'more than';
            $aid = intval(str_replace('>', '', $aids));
            $update = 'UPDATE ' . Model_Answer::$table . ' SET ad_id=' . $ad_id . 'WHERE id<=' . $aid . ' AND uid=' . $uid;
        } else {
            $domain = 'equal';
            $aids = explode(',', $aids);
            $update = 'UPDATE ' . Model_Answer::$table . ' SET ad_id=' . $ad_id . 'WHERE  uid=' . $uid . ' AND (';
            foreach ($aids as $aid) {
                $update .= ' aid=' . $aid . ' OR ';
            }
            $update = substr($update, 0, -4) . ')'; //remove last 'OR', and ')' 
        }
        Mysql::exec($update);
    }

}