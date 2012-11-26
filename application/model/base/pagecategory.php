<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;
/*
CREATE TABLE IF NOT EXISTS `page_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
*/
class Pagecategory {
    public static $fields = array('id','title',
        'description',  'status', 'date_created');
    public static $table = TABLE_PAGE_CATEGORY;
    public static function get_one($id) {
        $sql = "SELECT *
            FROM " . self::$table . " 
            WHERE id=$id
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'title', $direction = 'ASC') {
        $sql = "SELECT *
            FROM " . self::$table . " 
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
                                //\Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

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
        $sql = "Delete FROM page_category WHERE id=:id";
		$params = array(':id'=>$id);
        return Mysql::exec($sql, $params);
    }

}