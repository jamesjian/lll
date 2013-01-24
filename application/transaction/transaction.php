<?php

namespace App\Transaction;

use \App\Model\Article as Model_Article;
use \Zx\Message\Message;
use \Zx\Model\Mysql as Zx_Mysql;
use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Article {

    public static function create_article($arr = array()) {
        if (count($arr) > 0 && isset($arr['title'])) {
            if (!isset($arr['rank']))
                $arr['rank'] = 0; //initialize
            if (Model_Article::create($arr)) {
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

    public static function update_article($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (count($arr) > 0 && (isset($arr['title']) || isset($arr['content']))) {
            if (Model_Article::update($id, $arr)) {
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

    public static function delete_article($id) {
        if (Model_Article::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
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