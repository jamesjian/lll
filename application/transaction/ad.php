<?php
namespace App\Transaction;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Ad as Model_Ad;
use \Zx\Message\Message;
use \Zx\Model\Mysql;

class Ad {

    public static function create_ad($arr = array()) {
        if (count($arr) > 0 && isset($arr['title'])) {
            if (!isset($arr['rank']))
                $arr['rank'] = 0; //initialize
            if (Model_Ad::create($arr)) {
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

    public static function update_ad($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (count($arr) > 0 && (isset($arr['title']) || isset($arr['content']))) {
            if (Model_Ad::update($id, $arr)) {
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

    public static function delete_ad($id) {
        if (Model_Ad::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
            return false;
        }
    }

    /**
     * for cron job
     * backup ad table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM ad";
        $r = Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO ad VALUES ';
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