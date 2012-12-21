<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
 * 1. 造谣诽谤（-10)， 2. 种族或宗教歧视（-10)， 3.色情（-10) 4. 暴力， 虐待（人或动物（-10)） 
 * 5. 违禁物品（毒品， 武器, 人体器官等） （-10)
 * 6. 误导欺诈（-10) 7. 广告嫌疑(-1) 8. 与澳洲无关或无实质内容 (0)
 * if less than 10 categories, put into array instead of database
  CREATE TABLE claim_category (id unsigned tinyint(3) AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL DEFAULT '',
 claimant_score tinyint(2) not null default 1,
 defendant_score tinyint(2) not null default 1,
  description text,
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 */

class Claimcategory {
    public static $fields = array('id','title', 'description', 'claimant_score',
       'defendant_score',  'status', 'date_created');
    public static $table = TABLE_ABUSE_CATEGORY;
    
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
        $r = Mysql::select_all($sql);
        return $r;
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num FROM " . self::$table . " WHERE $where";
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
        $sql = "Delete FROM " . self::$table ." WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}