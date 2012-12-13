<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');


use \App\Model\Base\Ad as Base_Ad;
use \Zx\Model\Mysql;

class Ad extends Base_Ad {


    public static function ad_belong_to_user($ad_id, $uid) {
        $ad = parent::get_one($ad_id);
        if ($ad AND $ad['uid'] == $uid) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * currently top 10
     * according to date_created
     * @return records
     */
    public static function get_latest_ads() {
        $where = ' status=1';
        return parent::get_all($where, 0, 20, 'date_created', 'DESC');
    }
    /**
     */
    public static function get_active_ads_by_uid_and_page_num($uid, $where=1,$page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' status=1 AND uid=' . $uid;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_ads_by_uid($uid, $where = 1) {
        $where = " status=1 AND uid=$uid AND ($where)";
        return parent::get_num($where);
    }

    public static function get_ads_by_uid_and_page_num($uid, $where = '1', $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' (uid=' . $uid . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
    public static function get_num_of_ads_by_uid($uid, $where = 1) {
        $where = " uid=$uid AND ($where)";
        return parent::get_num($where);
    }    
    
}