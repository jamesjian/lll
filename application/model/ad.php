<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Ad as Base_Ad;
use \Zx\Model\Mysql;

class Ad extends Base_Ad {
    public static function get_statuses()
    {
        return array(
          parent::S_ACTIVE=>'active',  
          parent::S_CLAIMED=>'claimed',  
          parent::S_CORRECT=>'correct',  
          parent::S_DELETED=>'deleted',  
          parent::S_DISABLED=>'disabled',  
          parent::S_INACTIVE=>'inactive', //by user, not deleted but not displayed
        );
    }    
    /**
     * ,1,2,3,4,5,
     * ,aaa,bbb,ccc,ddd,eee,
     * 
     * if remove tag id 3, it will become:
     * 
     * ,1,2,4,5,
     * ,aaa,bbb,ddd,eee,
     * 
     * @param int $tag_id
     * @param string $tag_name  it's redundant for performance
     */
    public static function remove_tag($tag_id, $tag_name) {
        $tag_id = TNAME_SEPERATOR . $tag_id . TNAME_SEPERATOR;
        $tag_name = TNAME_SEPERATOR . $tag_name . TNAME_SEPERATOR;
        $seperator = TNAME_SEPERATOR;
        $q = "UPDATE " . parent::$table . " SET tids=REPLACE(tids, '$tag_id','$seperator'), 
            tnames=REPLACE(tnames, '$tag_name','$seperator') 
            WHERE tids LIKE '%$tag_id%";
        $params = array();
        return Mysql::exec($sql, $params);
    }
    /**
     * make sure id1 is valid
     * @param string $id1  
     * @return record
     */
    public static function get_one_by_id1($id1) {

        $sql = "SELECT *  FROM " . parent::$table . " WHERE id1=:id1";
        $params = array(':id1' => $id1);
        return Mysql::select_one($sql, $params);
    }

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
        for ($i = 0; $i < $n; $i++) {
            $arr[] = array(
                'ad_id' => 0,
                'ad_title' => 'welcome to this site',
                'ad_content' => 'welcome to this site');
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
        $q = 'UPDATE ' . parent::$table . ' SET status=' . parent::S_DISABLED . ' WHERE uid=:uid';
        $params = array(':uid' => $uid);
        return Mysql::exec($sql, $params);
    }

    /**
     * set inactive status of one ad by user, status is 0(inactive)
     * it's different from self::disable_by_uid($uid)
     * @param int $ad_id
     * @return boolean
     */
    public static function inactive($ad_id) {
        $arr = array('status' => parent::S_INACTIVE);
        return parent::update($ad_id, $arr);
    }

    /**
     * @param int $uid
     * @return array
     */
    public static function get_recent_ads_by_uid($uid) {
        $where = ' status=' . parent::S_CORRECT . 
                ' OR status=' .  parent::S_ACTIVE . 
                ' OR status=' .  parent::S_CLAIMED . 
                " AND uid=$uid";
        $offset = 0;
        $order_by = 'date_created';
        $direction = 'DESC';
        return parent::get_all($where, $offset, NUM_OF_RECENT_ADS_IN_FRONT_PAGE, $order_by, $direction);
    }

    public static function increase_num_of_views($ad_id) {
        $sql = 'UPDATE ads SET increase_num_of_views=increase_num_of_views+1 WHERE id=:id';
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
     * id=0 is a dummy record 
     * currently top 10
     * according to date_created
     * @return records
     */
    public static function get_latest_ads() {
        $where = 'id>0 AND  status=' . parent::S_CORRECT . 
                ' OR status=' .  parent::S_ACTIVE . 
                ' OR status=' .  parent::S_CLAIMED;
        return parent::get_all($where, 0, 20, 'date_created', 'DESC');
    }

    public static function get_ads_by_uid_and_page_num($uid, $where = '1', $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = "id>0 AND uid=$uid AND status<>" .parent::S_DELETED . "  AND ($where)";
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_ads_by_uid($uid, $where = 1) {
        $where = "id>0 AND status<>" .parent::S_DELETED . " AND  uid=$uid AND ($where)";
        return parent::get_num($where);
    }

    /**
     * for a user, undeleted means status<>S_DELETED, only admin can purge a record
     */
    public static function get_undeleted_ads_by_uid_and_page_num($uid, $where = 1, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = "id>0 AND uid=$uid AND status<>" . parent::S_DELETED . 
                 " AND ($where)";
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_undeleted_ads_by_uid($uid, $where = 1) {
        $where = "id>0 AND uid=$uid AND status=" . parent::S_DELETED . 
                " AND ($where)";
        return parent::get_num($where);
    }

    /**
     * active means not disabled by admin, even if it's claimed by other users
     */
    public static function get_active_ads_by_uid_and_page_num($uid, $where = 1, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = "id>0 AND uid=$uid AND (status=" . parent::S_CORRECT . 
                ' OR status=' .  parent::S_ACTIVE . 
                ' OR status=' .  parent::S_CLAIMED .  ") AND ($where)";
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_ads_by_uid($uid, $where = 1) {
        $where = "id>0 AND uid=$uid AND (status=" . parent::S_CORRECT . 
                ' OR status=' .  parent::S_ACTIVE . 
                ' OR status=' .  parent::S_CLAIMED .  ") AND ($where)";
        return parent::get_num($where);
    }

    public static function get_active_ads_by_tag_id_and_page_num($tag_id, $where = 1, $page_num = 1, $order_by = 'score', $direction = 'ASC') {
        $where = " id>0 AND (status=" . parent::S_CORRECT . 
                ' OR status=' .  parent::S_ACTIVE . 
                ' OR status=' .  parent::S_CLAIMED .  ") AND tids LIKE '%" . TNAME_SEPERATOR . $tag_id . TNAME_SEPERATOR . "%'";
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_ads_by_tag_id($tag_id, $where = 1) {
        $where = "id>0 AND (status=" . parent::S_CORRECT . 
                ' OR status=' .  parent::S_ACTIVE . 
                ' OR status=' .  parent::S_CLAIMED .  ") AND tids LIKE '%" . TNAME_SEPERATOR . $tag_id . TNAME_SEPERATOR . "%'";
        return parent::get_num($where);
    }

    public static function get_ads_by_page_num($where = '1', $page_num = 1, $order_by = 'date_created', $direction = 'ASC') {
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_ads($where = 1) {
        return parent::get_num($where);
    }

}