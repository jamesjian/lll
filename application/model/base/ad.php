<?php

namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql;

/*
  id=0 is a dummy ad
  active ads means not disabled by admin
 * including completely correct, claimed or acitve (not claimed)
 * 
 * 
  update ts8wl_answer set id1=concat(convert(2*id, char(11)), md5(id))
  CREATE TABLE ad (
  id unsigned MEDIUMINT(8)  AUTO_INCREMENT PRIMARY KEY,
  id1 varchar(44) not null unique,
  title varchar(255) NOT NULL DEFAULT '',
  uid unsigned MEDIUMINT(7)  not null default 0,
  uname varchar(255) not null default '',  #user name is fixed
  tids varchar(255) NOT NULL DEFAULT '',
  tnames varchar(255) not null default '', #tag names are fixed
  content text,
  score  unsigned MEDIUMINT(6)  not null default 0, #assigned by user according to num of ads
  num_of_views unsigned MEDIUMINT(8) not null default 0,
  status unsigned tinyint(1) not null default 1,
  date_created datetime,
  date_start datetime,
  date_end datetime
  ) engine=innodb default charset=utf8

 */

class Ad {

    public static $fields = array('id', 'id1', 'title', 'uid', 'uname',
        'tids', 'tnames', 'content', 'score', 'num_of_views', 
        'status', 'date_created', 'date_start', 'date_end');
    public static $table = TABLE_AD;
    /**for status
     * when created or updated, it's STATUS_ACTIVE
     * when somebody claim it, it's STATUS_CLAIMED
     * when somebody claim it and it's checked by admin and not wrong, it's STATUS_CORRECT
     * when somebody claim it and it's checked by admin and it's really bad, it's STATUS_DISABLED
     * even if it's STATUS_CORRECT, but when it's updated, it's STATUS_ACTIVE
     * STATUS_ACTIVE->STATUS_CLAIMED->STATUS_CORRECT     ->  STATUS_ACTIVE
     * (created)       (claimed)       completely correct    updated
     * STATUS_ACTIVE->STATUS_CLAIMED->STATUS_DISABLED  
     * (created)       (claimed)       completely correct    updated
     * 
     * STATUS_DISABLED cannot be changed by front user, but can be changed by admin when mistake happened
     * in the front end, only STATUS_DISABLED will not display, others will display
     * 
    */
    const S_DISABLED=0; //if this ad is wrong and disabled by admin
    const S_CORRECT=1;   //if this ad completely correct
    const S_ACTIVE=2;  //if this ad is active and can be claimed
    const S_CLAIMED=3; //when it's claimed by user
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
        $arr = array('id1' => 2 * $id . md5($id));  //generate id1
        self::update($id, $arr);
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