<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Base\Page as Base_Page;
use \Zx\Model\Mysql as Zx_Mysql;

class Page extends Base_Page{
    /**
     *
     * @param intval $cat_id  category id
     * @return boolean
     */
    public static function exist_page_under_cat($cat_id)
    {
        $where = 'cat_id=' . $cat_id;
        $num = parent::get_num($where);
        if ($num>0) return true;
        else return false;
    }
    /**
     * 
     * @param int $id
     * @return string or boolean when false
     */
    public static function get_title_by_id($id)
    {
        $page = parent::get_one($id);
        if ($page) {
            return $page['title'];
        } else {
            return false;
        }
    }
    /**
     * only one record or false
     * @return 1D array or false
     */
    public static function get_about_us()
    {
        $where = "ac.title='about us'";
        return parent::get_one_by_where($where);
    }
    /**
     * only one record or false
     * @return 1D array or false
     */
    public static function get_term_condition()
    {
        $where = "ac.title='term & condition'";
        return parent::get_one_by_where($where);	
    }
    /**
     * multiple record or false
     * @return 2D array or false
     */
    public static function get_faqs()
    {
        $where = "ac.title='faq'";
        return parent::get_all($where);		
    }
/**
     * get active cats order by category name
     */
    public static function get_active_pages_by_page_num($page_num = 1, $order_by = 'a.display_order', $direction = 'ASC') {
        $where = ' a.status=1 ';
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_pages() {
		$where = ' status=1';
        return parent::get_num();
    }
    public static function get_active_pages_by_cat_id_and_page_num($cat_id, $page_num = 1, $order_by = 'a.display_order', $direction = 'ASC') {
        $where = ' a.status=1 AND a.cat_id=' . $cat_id;
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_pages_by_cat_id() {
		$where = ' a.status=1 AND a.cat_id=' . $cat_id;
        return parent::get_num();
    }
    public static function get_pages_by_page_num($page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        switch ($order_by) {
            case 'id':
            case 'title':
            case 'date_created':
            case 'cat_id':
                $order_by = 'a.' . $order_by;
                break;
            default:
                $order_by = 'a.date_created';
        }
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        $where = '1';
        $start = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $start, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }

    public static function get_num_of_pages() {
        return parent::get_num();
    }

    /**
     * get active cats order by category name
     */
    public static function get_all_pages() {
        return parent::get_all();
    }	
}