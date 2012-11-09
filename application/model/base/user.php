<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;
/*
CREATE TABLE user (name varchar(255) PRIMARY KEY,
password varchar(32) NOT NULL DEFAULT '',
group_id int(11) NOT NULL DEFAULT 1
) engine=innodb default charset=utf8
*/
class User {

    public static function get_one($id) {
        $sql = "SELECT *
            FROM user
            WHERE id=$id
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = 999999, $order_by = 'a.id', $direction = 'ASC') {
        $sql = "SELECT *
            FROM user
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        return Mysql::select_all($sql);
    }

    public static function create($arr) {
        $sql = "INSERT INTO user SET " . Mysql::concat_field_name_and_value($arr);
        return Mysql::insert($sql);
    }

    public static function update($id, $arr) {
        $sql = "UPDATE article user " . Mysql::concat_field_name_and_value($arr) .
                ' WHERE id=$id';
        return Mysql::exec($sql);
    }

    public static function delete($id) {
        $sql = "Delete FROM user WHERE id=:id";
		$params = array(':id'=>$id);
        return Mysql::exec($sql, $params);
    }

}