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
    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT *
            FROM " . self::$table .
                " WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT *
            FROM " . self::$table .
                " WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        $sql = "SELECT *  FROM " . self::$table .
                " WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::select_all($sql);
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
        $id = Mysql::insert($sql, $params);
        return $id;
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
        $sql = "Delete FROM " . self::$table . " WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}