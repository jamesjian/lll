<?php
namespace App\Transaction;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Abuse as Model_Abuse;
use \Zx\Message\Message;
use \Zx\Model\Mysql;
use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Abuse {

    /**
     * 
     * @param array $arr must have item type, item id and cat id
     * @return boolean
     */
    public static function create_abuse($arr = array()) {
        if (count($arr) > 0 && isset($arr['item_type']) && isset($arr['item_id']) && isset($arr['cat_id'])) {
            $arr['status'] = 1; //created
            if (Model_Abuse::create($arr)) {
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

    public static function update_abuse($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (count($arr) > 0 && isset($arr['status']) && $arr['status'] != 1) {
            if (Model_Abuse::update($id, $arr)) {
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

    public static function delete_abuse($id) {
        if (Model_Abuse::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
            return false;
        }
    }

    /**
     * for cron job
     * backup abuse table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM abuse";
        $r = Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO abuse VALUES ';
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