<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Question as Base_Question;
use \App\Model\Base\Answer as Base_Answer;
use \App\Model\Base\Ad as Base_Ad;
use \App\Model\Base\User as Base_User;
use \App\Model\Base\Claim as Base_Claim;
use \Zx\Model\Mysql as Zx_Mysql;

class Claim extends Base_Claim {

    /**
     * 
     * @return array('1'=>'question', '2'=>'answer','3'=>'ad');
     */
    public static function get_item_types()
    {
        return array('1'=>'question', '2'=>'answer','3'=>'ad');
    }
    public static function get_num_of_active_claims($where = 1) {
        $where = " status=1 AND ($where)";
        return parent::get_num($where);
    }

    public static function get_num_of_claims($where = '1') {
        return parent::get_num($where);
    }

    /**
     * 
     * @param int $item_type  1. question, 2. answer, 3. ad
     * @param type $where
     * @param type $page_num
     * @param type $order_by
     * @param type $direction
     * @return records
     */
    public static function get_claims_by_item_type_and_page_num($item_type, $where = 1, $page_num = 1, $order_by = 'a.id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        $start = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;      
        switch ($item_type) {
            case 1:
                //question  has id1 and title
                $table = Base_Question::$table;
            $sql = "SELECT a.*, i.id1, i.title, i.content as item_content, u.uname
            FROM " . parent::$table . " a
            LEFT JOIN " . $table . " i ON i.id=a.item_id
            LEFT JOIN " . Base_User::$table . " u ON u.id=i.uid
            WHERE item_type=$item_type AND ($where)
            ORDER BY $order_by $direction
            LIMIT $start, " . NUM_OF_ITEMS_IN_ONE_PAGE;                
                break;
            case 2:
                //answer  has id1, but no title
                $table = Base_Answer::$table;
            $sql = "SELECT a.*, i.id1,i.content as item_content, u.uname
            FROM " . parent::$table . " a
            LEFT JOIN " . $table . " i ON i.id=a.item_id
            LEFT JOIN " . Base_User::$table . " u ON u.id=i.uid
            WHERE item_type=$item_type AND ($where)
            ORDER BY $order_by $direction
            LIMIT $start, " . NUM_OF_ITEMS_IN_ONE_PAGE;                       
                break;
            case 3:
                //ad  no id1, but has title
                $table = Base_Ad::$table;
            $sql = "SELECT a.*, i.title,  i.content as item_content, u.uname
            FROM " . parent::$table . " a
            LEFT JOIN " . $table . " i ON i.id=a.item_id
            LEFT JOIN " . Base_User::$table . " u ON u.id=i.uid
            WHERE item_type=$item_type AND ($where)
            ORDER BY $order_by $direction
            LIMIT $start, " . NUM_OF_ITEMS_IN_ONE_PAGE;                       
                break;
        }
  
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::select_all($sql);
    }

    /**
     * 
     * @param int $item_type  1. question, 2. answer, 3. ad
     * @param type $where
     * @return int
     */
    public static function get_num_of_claims_by_item_type($item_type, $where = 1) {
        $where = " ($where) AND item_type=$item_type";
        return parent::get_num($where);
    }

    /**
     * 
     * @param int $uid  user id
     * @param type $where
     * @param type $page_num
     * @param type $order_by
     * @param type $direction
     * @return records
     */
    public static function get_claims_by_uid_and_page_num($uid, $where = 1, $page_num = 1, $order_by = 'a.id', $direction = 'ASC') {
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
        $sql = "SELECT a.*, i.content as item_content, u.uname
            FROM " . parent::$table . " a
            LEFT JOIN " . $table . " i ON i.id=a.item_id
            LEFT JOIN " . Base_User::$table . " u ON u.id=a.uid
            WHERE (claimant_id=$uid OR defendant_id=$uid)  AND ($where)
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::select_all($sql);
    }

    /**
     * 
     * @param int $item_type  1. question, 2. answer, 3. ad
     * @param type $where
     * @return int
     */
    public static function get_num_of_claims_by_uid($uid, $where = 1) {
        $where = " ($where) AND (claimant_id=$uid OR defendant_id=$uid)";
        return parent::get_num($where);
    }

    /**
     * 
     * @param int $qid   question id, item type is 1
     * @param type $where
     * @param type $page_num
     * @param type $order_by
     * @param type $direction
     * @return records
     */
    public static function get_claims_by_qid_and_page_num($qid, $where = 1, $page_num = 1, $order_by = 'a.id', $direction = 'ASC') {
        //use content rather than title, because answer doesn't have a title
        $sql = "SELECT a.*, i.content as item_content, u.uname
            FROM " . parent::$table . " a
            LEFT JOIN " . $table . " i ON i.id=a.item_id
            LEFT JOIN " . Base_User::$table . " u ON u.id=a.uid
            WHERE item_type=1 AND item_id=$qid  AND ($where)
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::select_all($sql);
    }

    /**
     * 
     * @param int $qid   question id, item type is 1
     * @param type $where
     * @return int
     */
    public static function get_num_of_claims_by_qid($qid, $where = 1) {
        $where = " ($where) AND item_type=1 AND item_id=$qid";
        return parent::get_num($where);
    }

    /**
     * 
     * @param int $ad_id    item type is 3
     * @param type $where
     * @param type $page_num
     * @param type $order_by
     * @param type $direction
     * @return records
     */
    public static function get_claims_by_ad_id_and_page_num($ad_id, $where = 1, $page_num = 1, $order_by = 'a.id', $direction = 'ASC') {
        //use content rather than title, because answer doesn't have a title
        $sql = "SELECT a.*, i.content as item_content, u.uname
            FROM " . parent::$table . " a
            LEFT JOIN " . $table . " i ON i.id=a.item_id
            LEFT JOIN " . Base_User::$table . " u ON u.id=a.uid
            WHERE item_type=3 AND item_id=$ad_id  AND ($where)
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::select_all($sql);
    }

    /**
     * 
     * @param int $ad_id   ad id, item type is 3
     * @param type $where
     * @return int
     */
    public static function get_num_of_claims_by_ad_id($ad_id, $where = 1) {
        $where = " ($where) AND item_type=3 AND item_id=$ad_id";
        return parent::get_num($where);
    }

    /**
     * 
     * @param int $aid  answer id, item type is 2
     * @param type $where
     * @param type $page_num
     * @param type $order_by
     * @param type $direction
     * @return records
     */
    public static function get_claims_by_aid_and_page_num($aid, $where = 1, $page_num = 1, $order_by = 'a.id', $direction = 'ASC') {
        //use content rather than title, because answer doesn't have a title
        $sql = "SELECT a.*, i.content as item_content, u.uname
            FROM " . parent::$table . " a
            LEFT JOIN " . $table . " i ON i.id=a.item_id
            LEFT JOIN " . Base_User::$table . " u ON u.id=a.uid
            WHERE item_type=2 AND item_id=$aid  AND ($where)
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::select_all($sql);
    }

    /**
     * 
     * @param int $aid  answer id, item type is 2
     * @param type $where
     * @return int
     */
    public static function get_num_of_claims_by_aid($aid, $where = 1) {
        $where = " ($where) AND item_type=2 AND item_id=$aid";
        return parent::get_num($where);
    }

    public static function get_records_by_page_num($where = '1', $page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        $start = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $start, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_records($where) {
        return parent::get_num($where);
    }

}