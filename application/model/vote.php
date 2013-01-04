<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Vote as Base_Vote;
use \Zx\Model\Mysql;

class Vote extends Base_Vote {

    /**
     * 
     * @param int $uid
     * @param int $item_type //1. question, 2. answer, 3. ad
     * @param int $item_id
     * @return boolean 
     */
    public static function is_voted($uid, $item_type, $item_id) {
        if (parent::get_one($uid, $item_type, $item_id)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * item type=1
     * @param int $uid
     * @param int $qid
     * @return boolean
     */
    public static function is_question_voted($uid, $qid) 
    {
        return self::is_voted($uid, 1, $qid);
    }
    /**
     * item type=2
     * @param int $uid
     * @param int $aid
     * @return boolean
     */
    public static function is_answer_voted($uid, $aid) 
    {
        return self::is_voted($uid, 3, $aid);
    }
    /**
     * item type=3
     * @param int $uid
     * @param int $ad_id
     * @return boolean
     */
    public static function is_ad_voted($uid, $ad_id) 
    {
        return self::is_voted($uid, 3, $ad_id);
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