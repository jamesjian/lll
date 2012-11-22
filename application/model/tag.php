<?php

namespace App\Model;

use \App\Model\Base\Tag as Base_Tag;
use \Zx\Model\Mysql;

class Tag extends Base_Tag {
    /**
     *
     * @param intval $cat_id  tag id
     * @return boolean
     */
    public static function exist_question_under_tag($tag_id)
    {
        $tag = parent::get_one($tag_id);
        if ($tag && $tag['num_of_questions']>0) {
             return true;
        } else {
            return false;
        }
    }    
    public static function get_all_keywords()
    {
        $sql = "SELECT keyword, keyword_en FROM tag WHERE status=1";
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
     * 
     * @param string $url is a unique column in tag table
     */
    public static function get_one_by_url($url)
    {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM tag b
            LEFT JOIN tag_category bc ON b.cat_id=bc.id
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
    public static function exist_tag_under_cat($cat_id)
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
    public static function get_10_active_related_tags($tag_id) {
        $tag = parent::get_one($tag_id);
        if ($tag) {
            $cat_id = $tag['cat_id'];
            $keywords = $tag['keyword'];
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
                $tags = parent::get_all($where, $offset, $row_count, $order_by, $direction);
                return $tags;
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
    public static function get_active_tags_by_keyword($keyword, $page_num=1)
    {
                $where = " b.status=1 AND keyword LIKE '%$keyword%'";
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }
    /**
     * get active cats order by category name
     */
    public static function get_active_tags_by_page_num($page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' b.status=1 ';
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_tags($where = '1') {
        $where = ' (b.status=1' . ')  AND (' . $where . ')';
        return parent::get_num();
    }

    /**
     */
    public static function get_active_tags_by_cat_id_and_page_num($cat_id, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' b.status=1 AND b.cat_id=' . $cat_id;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_tags_by_cat_id_and_page_num($cat_id, $where = '1', $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' (b.status=1 AND b.cat_id=' . $cat_id . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_tags_by_cat_id($cat_id, $where = '1') {
        $where = ' (b.cat_id=' . $cat_id . ')  AND (' . $where . ')';
        return parent::get_num($where);
    }

    public static function get_num_of_active_tags_by_cat_id($cat_id) {
        $where = ' b.status=1 AND b.cat_id=' . $cat_id;
        return parent::get_num($where);
    }
 /**
     */
    public static function get_active_tags_by_keyword_and_page_num($keyword, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = " b.status=1 AND (b.keyword LIKE '%$keyword%' OR  b.keyword_en LIKE '%$keyword%')" ;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_tags_by_keyword($keyword) {
        $where = " b.status=1 AND (b.keyword LIKE '%$keyword%' OR  b.keyword_en LIKE '%$keyword%')" ;
        return parent::get_num($where);
    }    
    
    public static function get_tags_by_page_num($where = '1', $page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        switch ($order_by) {
            case 'id':
            case 'name':
            case 'num_of_questions':
            case 'num_of_ads':
            case 'rank':
            case 'date_created':
                $order_by = $order_by;
                break;
            default:
                $order_by = 'id';
        }
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        //$where = '1';
        $start = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $start, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_tags($where = '1') {
        return parent::get_num($where);
    }

    /**
     * get active cats order by category name
     */
    public static function get_all_tags() {
        return parent::get_all();
    }

    /**
     * get active cats order by category name
     */
    public static function get_all_active_tags() {
        $where = 'b.status=1';
        return parent::get_all($where);
    }

    public static function increase_rank($tag_id) {
        $sql = 'UPDATE tag SET rank=rank+1 WHERE id=:id';
        $params = array(':id' => $tag_id);
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
    public static function exist_tag_by_tag_name($tag_name){
        $tag_name = strtolower($tag_name);
        $where = " name=$tag_name";
        if ($tag  = parent::get_one_by_where($where)) {
            return $tag_id;
        } else {
            return false;
        }
    }
}