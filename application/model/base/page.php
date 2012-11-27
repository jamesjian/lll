<?php
namespace App\Model\Base;

use \Zx\Model\Mysql;

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
        $sql = "SELECT a.*, ac.title as cat_name
            FROM " . self::$table . " a
            LEFT JOIN page_category ac ON a.cat_id=ac.id
            WHERE a.id=:id
        ";
		$params = array(':id'=>$id);
        return Mysql::select_one($sql, $params);
    }    
	/**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT a.*, ac.title as cat_name
            FROM " . self::$table . " a
            LEFT JOIN page_category ac ON a.cat_id=ac.id
            WHERE $where
        ";
		$params = array();
        return Mysql::select_one($sql, $params);
    }

	
    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'a.display_order', $direction = 'ASC') {
        $sql = "SELECT a.*, ac.title as cat_name
            FROM " . self::$table . " a
            LEFT JOIN page_category ac ON a.cat_id=ac.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        return Mysql::select_all($sql);
    }
    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num
            FROM " . self::$table . " 
            WHERE $where
        ";
        $result = Mysql::select_one($sql);
		if ($result) {
			return $result['num'];
		} else {
			return false;
		}
    }
   public static function create($arr) {
        $insert_arr = array(); $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $insert_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $insert_str = implode(',', $insert_arr);
        $sql = 'INSERT INTO ' . self::$table . ' SET ' . $insert_str;
        return Mysql::insert($sql, $params);
    }

    public static function update($id, $arr) {
        $update_arr = array();$params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $update_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }        
        $update_str = implode(',', $update_arr);
        $sql = 'UPDATE ' .self::$table . ' SET '. $update_str . ' WHERE id=:id';
        $params[':id'] = $id;
        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM page WHERE id=:id";
		$params = array(':id'=>$id);
        return Mysql::exec($sql, $params);
    }

}