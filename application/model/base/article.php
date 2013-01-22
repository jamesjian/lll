<?php

namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql;
use App\Model\Articlecategory as Model_Articlecategory;

/*
  CREATE TABLE article (id MEDIUMINT(8) unsigned AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL DEFAULT '',
  cat_id int(11) NOT NULL DEFAULT 1,
  keyword varchar(255) not null default '',
  abstract text,
  content text,
  num_of_views MEDIUMINT(8) default 0,
  status tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 * 
 * alter table article add index cat_id (cat_id);
 * show index from article;
 */

class Article {

    public static $fields = array('id', 'title', 'cat_id',
        'keyword', 'abstract', 'content', 'num_of_views', 'status', 'date_created');
    public static $table = TABLE_ARTICLE;
    const S_ACTIVE = 1;  
    const S_INACTIVE = 2;  

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */

    public static function get_one($id) {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM " . self::$table . " b
            LEFT JOIN " . Model_Articlecategory::$table . " bc ON b.cat_id=bc.id
            WHERE b.id=:id
        ";
        $params = array(':id' => $id);
        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM " . self::$table . " b
            LEFT JOIN " . Model_Articlecategory::$table . " bc ON b.cat_id=bc.id
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'b.date_created', $direction = 'DESC') {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM " . self::$table . " b
            LEFT JOIN " . Model_Articlecategory::$table . " bc ON b.cat_id=bc.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

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
        $insert_arr = array();
        $params = array();
        $arr['date_created'] = date('Y-m-d h:i:s');
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
        $update_arr = array();
        $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $update_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }

        $update_str = implode(',', $update_arr);
        $sql = 'UPDATE ' . self::$table . ' SET ' . $update_str . ' WHERE id=:id';
        //\Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $params[':id'] = $id;
        //$query = Mysql::interpolateQuery($sql, $params);
        //\Zx\Test\Test::object_log('query', $query, __FILE__, __LINE__, __CLASS__, __METHOD__);
        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = 'Delete FROM ' . self::$table . ' WHERE id=:id';
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}