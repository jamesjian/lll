<?php

namespace App\Model\Base;

use \Zx\Model\Mysql as Zx_Mysql;

/*
  CREATE TABLE article_category (id int(11) AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL DEFAULT '',
  description text,
  status tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 */

class Articlecategory {
    public static $fields = array('id','title','title_en', 'cat_id',
        'keyword','keyword_en', 'url',
        'description', 'display_order', 'status', 'date_created');
    public static $table = 'article_category';
    
    public static function get_one($id) {
        $sql = "SELECT *
            FROM article_category
            WHERE id=$id
        ";
        return Zx_Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'title', $direction = 'ASC') {
        $sql = "SELECT *
            FROM article_category
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        $r = Zx_Mysql::select_all($sql);
        return $r;
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num
            FROM article_category
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
        $insert_arr = array(); $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $insert_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $insert_str = implode(',', $insert_arr);
        $sql = 'INSERT INTO ' . self::$table . ' SET ' . $insert_str;
        return Zx_Mysql::insert($sql, $params);
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
        return Zx_Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM article_category WHERE id=:id";
        $params = array(':id' => $id);
        return Zx_Mysql::exec($sql, $params);
    }

}