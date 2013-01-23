<?php

namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql;

/*
 
CREATE TABLE body(
id MEDIUMINT( 8 ) unsigned AUTO_INCREMENT PRIMARY KEY ,
en varchar( 255 ) NOT NULL DEFAULT '',
cn varchar( 255 ) NOT NULL DEFAULT '',
cid tinyint( 2 ) NOT NULL default 1
) ENGINE = innodb default CHARSET = utf8
*/

class Body {

    public static $fields = array('id', 'en', 'cn', 'cid');
    public static $table = 'body';
    public static function get_one($id) {
        return Zx_Model::get_one(self::$table, $id);
    }

    public static function get_one_by_where($where) {
        return Zx_Model::get_one_by_where(self::$table, $where);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        return Zx_Model::get_all(self::$table, $where, $offset, $row_count, $order_by, $direction);
    }

    public static function get_num($where = '1') {
        return Zx_Model::get_num(self::$table, $where);
    }

    public static function create($arr) {
        $arr['date_created'] = date('Y-m-d h:i:s');
        return Zx_Model::create(self::$table, self::$fields, $arr);
    }

    public static function update($id, $arr) {
        return Zx_Model::update(self::$table, $id, self::$fields, $arr);
    }

    public static function delete($id) {
        return Zx_Model::delete(self::$table, $id);
    }

}