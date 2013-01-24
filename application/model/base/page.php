<?php
namespace App\Model\Base;

use \Zx\Model\Mysql as Zx_Mysql;

/*

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `content` text,
  `cat_id` tinyint(2) DEFAULT '1',
  `status` tinyint(1) DEFAULT '1' COMMENT '1: active, 0: inactive',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 alter table  ts8wl_page add index cat_id (cat_id); 

*/
class Page {
    public static $fields = array('id','title', 'cat_id',
        'content', 'status', 'date_created');
    public static $table = TABLE_PAGE;
    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT a.*, c.title as cat_name
            FROM " . self::$table . " a
            LEFT JOIN " . Model_Pagecategory::$table . " c ON a.cat_id=c.id
            WHERE a.id=:id
        ";
		$params = array(':id'=>$id);
        return Zx_Mysql::select_one($sql, $params);
    }    
	/**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT a.*, c.title as cat_name
            FROM " . self::$table . " a
            LEFT JOIN " . Model_Pagecategory::$table . " c ON a.cat_id=c.id
            WHERE $where
        ";
		$params = array();
        return Zx_Mysql::select_one($sql, $params);
    }

	
    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'a.id', $direction = 'ASC') {
        $sql = "SELECT a.*, c.title as cat_name
            FROM " . self::$table . " a
            LEFT JOIN " . Model_Pagecategory::$table . " c ON a.cat_id=c.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        return Zx_Mysql::select_all($sql);
    }
    public static function get_num($where = '1') {
        $sql = "SELECT a.*, c.title as cat_name
            FROM " . self::$table . " a
            LEFT JOIN " . Model_Pagecategory::$table . " c ON a.cat_id=c.id
            WHERE $where
        ";
        $result = Zx_Mysql::select_one($sql);
		if ($result) {
			return $result['num'];
		} else {
			return false;
		}
    }
       public static function create($arr) {
        $arr['date_created'] = date('Y-m-d h:i:s');
        return Zx_Mysql::create(self::$table, self::$fields, $arr);
    }

    public static function update($id, $arr) {
        return Zx_Mysql::update(self::$table, $id, self::$fields, $arr);
    }

    public static function delete($id) {
        return Zx_Mysql::delete(self::$table, $id);
    }

}