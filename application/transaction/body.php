<?php

namespace App\Transaction;

use \App\Model\Body as Model_Body;
use \App\Transaction\Tool as Transaction_Tool;
use \Zx\Message\Message as Zx_Message;
use \Zx\Model\Mysql;

class Body {

    /**
     * @param array $arr
     * @return boolean
     */
    public static function create($arr = array()) {
//        \Zx\Test\Test::object_log('$arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);              

        if (count($arr) > 0 && isset($arr['en'])
                && !Model_Body::exist_by_en($arr['en'])) {
            //initialize

            if (Model_Body::create($arr)) {
                Zx_Message::set_success_message('success');
                return true;
            } else {
                Zx_Message::set_error_message('fail');
                return false;
            }
        } else {
            Zx_Message::set_error_message('wrong info');
            return false;
        }
    }

    /**
     * if en changed, check name duplicate
     * @param int $id
     * @param array $arr
     * @return boolean
     */
    public static function update($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $body = Model_Body::get_one($id);
        if (isset($arr['en']) && !Model_Body::duplicate_en($id, $arr['en'])) {
            if (Model_Body::update($id, $arr)) {
                Zx_Message::set_success_message('success');
                return true;
            } else {
                Zx_Message::set_error_message('fail');
                return false;
            }
        } else {
            Zx_Message::set_error_message('wrong info or duplicate');
            return false;
        }
    }

    public static function delete($id) {
        $body = Model_Body::get_one($id);
        if (Model_Body::delete($id)) {
            Zx_Message::set_success_message('success');
            return true;
        } else {
            Zx_Message::set_error_message('fail');
            return false;
        }
    }

    function backup_sql() {
        $sql = "SELECT * FROM body";
        $r = Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO body VALUES ';
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