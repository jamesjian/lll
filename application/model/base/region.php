<?php
namespace App\Model\Base;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Model\Mysql;

/*
  primary key is not an integer
  CREATE TABLE IF NOT EXISTS `ts8wl_region` (
  `state` varchar(3) NOT NULL PRIMARY KEY,
  num_of_questions int(11) unsigned not null default 0,
  num_of_ads int(11) unsigned  not null default 0,
  `date_created` datetime
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */

class Region {

    public static $fields = array('state', 'num_of_questions', 'num_of_ads', 'date_created');
    public static $table = TABLE_REGION;

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($state) {
        $sql = "SELECT * FROM " . self::$table . " WHERE state=:state";
        $params = array(':state' => $state);
        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT * FROM " . self::$table . " WHERE  $where";
        $params = array();
        return Mysql::select_one($sql, $params);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'state', $direction = 'ASC') {
        $sql = "SELECT * FROM " . self::$table . " WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(state) AS num
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
    /**
     * we will never create new record
     */ 
    public static function create($arr) {
        return true;
    }
    public static function update($state, $arr) {
        $update_arr = array();
        $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $update_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $update_str = implode(',', $update_arr);
        $sql = 'UPDATE ' . self::$table . ' SET ' . $update_str . ' WHERE state=:state';
        $params[':state'] = $state;
        return Mysql::exec($sql, $params);
    }
    /**
     * we will never delete record in region table
     */ 
    public static function delete($state) {
        $sql = "Delete FROM page WHERE state=:state";
        $params = array(':state' => $state);
        return Mysql::exec($sql, $params);
    }

}