<?php

namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Base\Catgroup as Base_Catgroup;
use \Zx\Model\Mysql;

class Catgroup extends Base_Catgroup {
    public static function get_all_groups()
    {
        $sql = "SELECT * FROM catgroup WHERE status=1 ORDER BY display_order ASC";
        $r = Mysql::select_all($sql);
        
        $arr = array();
        if ($r) {
            foreach ($r as $record) {
                $arr_keyword = explode(',', $record['keyword']);
                foreach ($arr_keyword as $keyword) {
                    if (trim($keyword) != '' && !in_array($keyword, $arr)) {
                        $arr[] = $keyword;
                    }
                }
                $arr_keyword = explode(',', $record['keyword_en']);
                foreach ($arr_keyword as $keyword) {
                    if (trim($keyword) != '' && !in_array($keyword, $arr)) {
                        $arr[] = $keyword;
                    }
                }                
            }
        }
        return $arr;
    }
    /**
     * 
     * @param string $url is a unique column in catgroup table
     */
    public static function get_one_by_url($url)
    {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM catgroup b
            LEFT JOIN catgroup_category bc ON b.cat_id=bc.id
            WHERE b.url='$url'
        ";
        //$params = array(':url'=>$url);
//		$query = Mysql::interpolateQuery($sql, $params);
      //\Zx\Test\Test::object_log('query', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);        
       return Mysql::select_one($sql);
    }
    /**
     *
     * @param intval $cat_id  category id
     * @return boolean
     */
    public static function exist_catgroup_under_cat($cat_id)
    {
        $where = 'b.cat_id=' . $cat_id;
        $num = parent::get_num($where);
        if ($num>0) return true;
        else return false;
    }
    /**
      according to category or keyword
      keywords are seperated by '^'
     */
    public static function get_10_active_related_catgroups($catgroup_id) {
        $catgroup = parent::get_one($catgroup_id);
        if ($catgroup) {
            $cat_id = $catgroup['cat_id'];
            $keywords = $catgroup['keyword'];
            $keyword_arr = array();
            if ($keywords <> '') {
                $keyword_arr = explode('^', $keywords);
            }

            $where = "b.status=1 AND (b.cat_id=$cat_id";
            if (count($keyword_arr) > 0) {
                foreach ($keyword_arr as $keyword) {
                    $where .= " OR b.keyword LIKE '%$keyword%'";
                }
                $where .= ')';
                $offset = 0;
                $row_count = 10;
                $order_by = 'b.date_created';
                $direction = 'DESC';
                $catgroups = parent::get_all($where, $offset, $row_count, $order_by, $direction);
                return $catgroups;
            } else {
                return false;
            }
        }
    }
    /**
     * 
     * @param string $keyword
     * @param intval $page_num
     * @return array
     */
    public static function get_active_catgroups_by_keyword($keyword, $page_num=1)
    {
                $where = " b.status=1 AND keyword LIKE '%$keyword%'";
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }
    /**
     * get active cats order by category name
     */
    public static function get_active_catgroups_by_page_num($page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' b.status=1 ';
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_catgroups($where = '1') {
        $where = ' (b.status=1' . ')  AND (' . $where . ')';
        return parent::get_num();
    }

    /**
     */
    public static function get_active_catgroups_by_cat_id_and_page_num($cat_id, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' b.status=1 AND b.cat_id=' . $cat_id;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_catgroups_by_cat_id_and_page_num($cat_id, $where = '1', $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' (b.status=1 AND b.cat_id=' . $cat_id . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_catgroups_by_cat_id($cat_id, $where = '1') {
        $where = ' (b.cat_id=' . $cat_id . ')  AND (' . $where . ')';
        return parent::get_num($where);
    }

    public static function get_num_of_active_catgroups_by_cat_id($cat_id) {
        $where = ' b.status=1 AND b.cat_id=' . $cat_id;
        return parent::get_num($where);
    }
 /**
     */
    public static function get_active_catgroups_by_keyword_and_page_num($keyword, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = " b.status=1 AND (b.keyword LIKE '%$keyword%' OR  b.keyword_en LIKE '%$keyword%')" ;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_catgroups_by_keyword($keyword) {
        $where = " b.status=1 AND (b.keyword LIKE '%$keyword%' OR  b.keyword_en LIKE '%$keyword%')" ;
        return parent::get_num($where);
    }    
    
    public static function get_catgroups_by_page_num($where = '1', $page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        switch ($order_by) {
            case 'id':
            case 'title':
            case 'rank':
            case 'display_order':
            case 'date_created':
            case 'cat_id':
                $order_by = 'b.' . $order_by;
                break;
            default:
                $order_by = 'b.date_created';
        }
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        //$where = '1';
        $start = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $start, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }

    public static function get_num_of_catgroups($where = '1') {
        return parent::get_num($where);
    }

    /**
     * get active cats order by category name
     */
    public static function get_all_catgroups() {
        return parent::get_all();
    }

    /**
     * get active cats order by category name
     */
    public static function get_all_active_catgroups() {
        $where = 'b.status=1';
        return parent::get_all($where);
    }

    public static function increase_rank($catgroup_id) {
        $sql = 'UPDATE catgroup SET rank=rank+1 WHERE id=:id';
        $params = array(':id' => $catgroup_id);
        return Mysql::exec($sql, $params);
    }

    public static function get_top10() {
        $where = ' b.status=1';
        return parent::get_all($where, 0, 10, 'b.rank', 'DESC');
    }

    public static function get_latest10() {
        $where = ' b.status=1';
        return parent::get_all($where, 0, 10, 'b.date_created', 'DESC');
    }

}