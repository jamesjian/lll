<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Base\Abcategory as Base_Abusecategory;
use \Zx\Model\Mysql;
class Abusecategory extends Base_Abusecategory{
    /**
     * get active cats order by category name
     */
    public static function get_cats()
    {
        return array('1'=>'造谣诽谤', '2'=>'种族或宗教歧视', '3'=>'色情', 
            '4'=>'暴力， 虐待（人或动物）',
            '5'=>'违禁物品（毒品， 武器， 人体器官等）', '6'=>'误导欺诈',
            '7'=>'与澳洲无关或无实质内容');
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
            case 'title_en':
            case 'url':
            case 'display_order':
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
        $sql = "SELECT * FROM abuse_category WHERE title='$title'";
                \Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::select_one($sql);
    }
}