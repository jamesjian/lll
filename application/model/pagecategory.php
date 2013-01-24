<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Base\Pagecategory as Base_Pagecategory;
use \Zx\Model\Mysql as Zx_Mysql;
class Pagecategory extends Base_Pagecategory{
    /**
     * get active cats order by category name
     */
    public static function get_cats()
    {
        return parent::get_all();
    }    
    /**
     * get active cats order by category name
     */	
	public static function get_all_cats()
    {
		$where = "status=1";
        return parent::get_all($where);
    }
 public static function get_cats_by_page_num($page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        switch ($order_by) {
            case 'id':
            case 'title':
                $order_by = $order_by;
                break;
            default:
                $order_by = 'title';
        }
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        $where = '1';
        $start = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $start, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }    
    public static function get_num_of_cats() {
        return parent::get_num();
    }  	
}