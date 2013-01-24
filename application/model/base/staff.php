<?php

namespace App\Model\Base;

use \Zx\Model\Mysql as Zx_Mysql;
/*
CREATE TABLE staff (name varchar(255) PRIMARY KEY,
password varchar(32) NOT NULL DEFAULT '',
) engine=innodb default charset=utf8
*/
class Staff {
    public static $fields = array('name','password');
    public static $table = TABLE_STAFF;
    public static function get_one($id) {
        return Zx_Mysql::get_one(self::$table, $id);
    }

    public static function get_one_by_where($where) {
        return Zx_Mysql::get_one_by_where(self::$table, $where);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        return Zx_Mysql::get_all(self::$table, $where, $offset, $row_count, $order_by, $direction);
    }

    public static function get_num($where = '1') {
        return Zx_Mysql::get_num(self::$table, $where);
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