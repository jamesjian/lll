<?php
namespace App\Transaction;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Claim as Model_Claim;
use \Zx\Message\Message;
use \Zx\Model\Mysql;
use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Claim {

    /**
     * 
     * @param array $arr must have item type, item id and cat id
     * @return boolean
     */
    public static function create_claim($arr = array()) {
        if (count($arr) > 0 && isset($arr['item_type']) && isset($arr['item_id']) && isset($arr['cat_id'])) {
            $arr['status'] = 1; //created
            if (Model_Claim::create($arr)) {
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

    public static function update_claim($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (count($arr) > 0 && isset($arr['status']) && $arr['status'] != 1) {
            if (Model_Claim::update($id, $arr)) {
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

    public static function delete_claim($id) {
        if (Model_Claim::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
            return false;
        }
    }

    /**
     * for cron job
     * backup claim table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM claim";
        $r = Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO claim VALUES ';
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