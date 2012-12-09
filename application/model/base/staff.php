<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;
/*
CREATE TABLE staff (name varchar(255) PRIMARY KEY,
password varchar(32) NOT NULL DEFAULT '',
) engine=innodb default charset=utf8
*/
class Staff {
    public static $fields = array('name','password');
    public static $table = TABLE_STAFF;
    public static function get_one($id) {
        $sql = "SELECT *
            FROM " . self::$table . " 
            WHERE id=$id
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = 999999, $order_by = 'a.id', $direction = 'ASC') {
        $sql = "SELECT *
            FROM " . self::$table . " 
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        return Mysql::select_all($sql);
    }

    public static function create($arr) {
        $sql = "INSERT INTO  " . self::$table . " SET " . Mysql::concat_field_name_and_value($arr);
        return Mysql::insert($sql);
    }

    public static function update($id, $arr) {
        $sql = "UPDATE " . self::$table . Mysql::concat_field_name_and_value($arr) .
                ' WHERE id=$id';
        return Mysql::exec($sql);
    }

    public static function delete($id) {
        $sql = "Delete FROM " . self::$table . " WHERE id=:id";
		$params = array(':id'=>$id);
        return Mysql::exec($sql, $params);
    }

}