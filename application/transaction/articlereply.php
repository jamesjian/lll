<?php

namespace App\Transaction;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Articlereply as Model_Articlereply;
use \Zx\Message\Message as Zx_Message;
use \Zx\Model\Mysql as Zx_Mysql;
use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Articlereply {

    /**
     * only by user
     * @param array $arr
     * @return boolean
     */
    public static function create($arr = array()) {
        if (count($arr) > 0 && isset($arr['content'])) {
            $arr['status'] = Model_Articlereply::S_ACTIVE;
            if (Model_Articlereply::create($arr)) {
                Zx_Message::set_success_message('回复成功');
                return true;
            } else {
                Zx_Message::set_error_message(SYSTEM_ERROR_MESSAGE);
                return false;
            }
        } else {
            Message::set_error_message('信息不完整， 请重新填写。');
            return false;
        }
    }

    /**
     * only by admin
     * @param int $id
     * @param array $arr
     * @return boolean
     */
    
    public static function update($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (count($arr) > 0 && isset($arr['content'])) {
            if (Model_Articlereply::update($id, $arr)) {
                Zx_Message::set_success_message('更新成功');
                return true;
            } else {
                Zx_Message::set_error_message(SYSTEM_ERROR_MESSAGE);
                return false;
            }
        } else {
            Zx_Message::set_error_message('信息不完整， 请重新填写。');
            return false;
        }
    }

    /**
     * only by admin
     * @param int $id
     * @return boolean
     */
    public static function delete($id) {
        if (Model_Articlereply::delete($id)) {
            Zx_Message::set_success_message('success');
            return true;
        } else {
            Zx_Message::set_error_message('fail');
            return false;
        }
    }

    /**
     * for cron job
     * backup article table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM article";
        $r = Zx_Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO article VALUES ';
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