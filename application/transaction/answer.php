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
            $user_id = $_SESSION['user']['user_id'];
            $user_name = $_SESSION['user']['user_name'];
            $status = 1;
        } else {
            $user_id = Model_User::get_default_question_user_id();
            $user_name = '匿名回答用户';
            $status = 0;
        }        
        $arr['user_id'] = $user_id;
        $arr['user_name'] = $user_name;
        $status = $status;
        if (count($arr) > 0 && 
                isset($arr['content']) && trim($arr['content'])!='' 
                ) {
            if (!isset($arr['rank']))
                $arr['rank'] = 0; //initialize
            
            if (Model_Answer::create($arr)) {
                Model_Question::increase_num_of_answers($arr['question_id']);
                Model_User::increase_num_of_answers($arr['user_id']);
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
    public static function create_answer_by_admin($arr = array())
    {
        
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

}