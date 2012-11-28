<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');


use \App\Model\Base\Question as Base_Question;
use \App\Model\Base\Answer as Base_Answer;
use \App\Model\Base\Ad as Base_Ad;
use \App\Model\Base\User as Base_User;
use \App\Model\Base\Abuse as Base_Abuse;
use \Zx\Model\Mysql;

class Abuse extends Base_Abuse {

    public static function get_num_of_active_abuses($where = 1) {
        $where = " status=1 AND ($where)";
        return parent::get_num($where);
    }
    public static function get_num_of_abuses_by_item_type($item_type, $where=1) {
        $where = " ($where) AND item_type=$item_type";
        return parent::get_num($where);
    }

    public static function get_num_of_abuses($where = '1') {
        return parent::get_num($where);
    }
    
 public static function get_abuses_by_item_type_and_page_num($item_type,$where=1, $page_num = 1, $order_by = 'a.id', $direction = 'ASC') {
     switch ($item_type) {
         case 1:
             //question
             $table = Base_Question::$table;
             break;
         case 2:
             //answer
              $table = Base_Answer::$table;
             break;
         case 3:
             //ad
              $table = Base_Ad::$table;
             break;
     }
     //use content rather than title, because answer doesn't have a title
        $sql = "SELECT a.*, i.content as item_content, u.user_name
            FROM " . parent::$table . " a
            LEFT JOIN " . $table . " i ON i.id=a.item_id
            LEFT JOIN " . Base_User::$table . " u ON u.id=a.user_id
            WHERE item_type=$item_type AND ($where)
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::select_all($sql);
    }


}