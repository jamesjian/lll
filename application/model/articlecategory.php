<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Base\Articlecategory as Base_Articlecategory;
use \Zx\Model\Mysql;
class Articlecategory extends Base_Articlecategory{
    /**
     * get active cats order by category name
     */
    public static function get_cats()
    {
        return parent::get_all();
    }    
    /**
     * get all cats order by category name
     */	
	public static function get_all_cats()
    {
		$where = "1";
        return parent::get_all($where);
    }
    /**
     * get active cats order by category name
     */	
	public static function get_all_active_cats()
    {
		$where = "status=1";
        return parent::get_all($where);
    }
    public static function get_cats_by_page_num($where='1',$page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        switch ($order_by) {
            case 'id':
            case 'title':
            case 'status':
                $order_by = $order_by;
                break;
            default:
                $order_by = 'title';
        }
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        //$where = '1';
        $start = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $start, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }    
    public static function get_num_of_cats($where='1') {
        return parent::get_num($where);
    }    
    public static function get_num_of_active_cats() {
        $where = 'status=1';
        return parent::get_num($where);
    }    
    /**
     * 
     * @param string $title cat tile is unique
     * @return array or false if not exists
     */
    public static function exist_cat_title($title) {
        $sql = "SELECT * FROM article_category WHERE title='$title'";
                \Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::select_one($sql);
    }
}