<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Ad as Base_Ad;
use \Zx\Model\Mysql;

class Ad extends Base_Ad {
    /**
     * todo:
     * add a special status or mark for all selected ads in ad table
     * 
     $q = select * from ad where status=special and date_end>today limit 0, $n
     * 
     * this will be changing depends on the situation of ads
     * @param int $n, usually from Model_Answer::get_num_of_inactive_ads($answers)
     * @return array of ads
     */
    public static function get_selected_ads($n) {
        $arr = array();
        for ($i=0; $i<$n; $i++) {
            $arr[] = array(
                'ad_id'=>0,
                'ad_title'=>'welcome to this site', 
                'ad_content'=>'welcome to this site');
        }
        return $arr;
        
    }
    /**
     * 
     * disable all ads for this uid by admin
     * status is 2 (disabled by admin)
     * it's different from self::inactive($ad_id)
     * @param int $uid
     * @return boolean
     */
    public static function disable_by_uid($uid) {
        $q = 'UPDATE ' . parent::$table . ' SET status=2 WHERE uid=:uid';
        $params = array(':uid' => $uid);
        return Mysql::exec($sql, $params);
    }
    /**
     * set inactive status of one ad by user, status is 0(inactive)
     * it's different from self::disable_by_uid($uid)
     * @param int $ad_id
     * @return boolean
     */
    public static function inactive($ad_id){
        $arr = array('status'=>0);
        return parent::update($ad_id, $arr);
    }

    /**
     * @param int $uid
     * @return array
     */
    public static function get_recent_ads_by_uid($uid) {
        $where = " status=1 AND uid=$uid";
        $offset = ($page_num - 1) * NUM_OF_RECENT_ADS_IN_FRONT_PAGE;
        $order_by = 'date_created';
        $direction = 'DESC';
        return parent::get_all($where, $offset, NUM_OF_RECENT_ADS_IN_FRONT_PAGE, $order_by, $direction);
    }

    public static function increase_num_of_views($ad_id) {
        $sql = 'UPDATE article SET increase_num_of_views=increase_num_of_views+1 WHERE id=:id';
        $params = array(':id' => $ad_id);
        return Mysql::exec($sql, $params);
    }

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
    public static function get_active_ads_by_uid_and_page_num($uid, $where = 1, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
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