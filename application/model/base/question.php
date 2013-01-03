<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
 * if a question is disabled, it will be moved to disabled_question table
 * if has answer, only create/edit content1, if has no answer, can edit content
 *  #AU means australia
  CREATE TABLE question (
 id unsigned mediumint(8) AUTO_INCREMENT PRIMARY KEY,
 id1 varchar(44) not null unique,
  title varchar(255) NOT NULL DEFAULT '',
 region varchar(3) not null default 'AU',
  uid unsigned mediumint(7) not null 0,
  uname varchar(30) not null '',  #user name is fixed
  tids varchar(255) NOT NULL DEFAULT '',
  tnames varchar(255) not null default '', #tag names are fixed  
  content text,
  content1 text,
  num_of_answers unsigned smallint(4) default 0,
  num_of_views unsigned int(11) default 0,
  num_of_votes unsigned mediumint(7) default 0,
  valid unsigned tinyint(1) not null default 1,
  status unsigned tinyint(1) not null default 1,  //1: active, 2. 
  date_created datetime) engine=innodb default charset=utf8
 */

class Question {
    public static $fields = array('id','id1','title','region', 'uid','uname',
        'tids','tnames','num_of_answers','content',
        'content1', 'num_of_views','num_of_votes', 'valid', 'status', 'date_created');
    public static $table = TABLE_QUESTION;
    const STATUS_DISABLED=0;
    const STATUS_VALID=1;
    const STATUS_INVALID=2;
    const STATUS_NOT_CONFIRMED=3;
    

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT * FROM " . self::$table .  " WHERE id=:id";
        $params = array(':id' => $id);


        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT *  FROM " . self::$table .  " WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        $sql = "SELECT *
            FROM " . self::$table .  " WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

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
        $insert_arr = array(); $params = array();
        $arr['date_created'] = date('Y-m-d h:i:s');
                //\Zx\Test\Test::object_log('$arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $insert_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $insert_str = implode(',', $insert_arr);
        $sql = 'INSERT INTO ' . self::$table . ' SET ' . $insert_str;
        //\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        $id = Mysql::insert($sql, $params);
        $arr = array('id1'=>2*$id . md5($id));  //generate id1
        self::update($id, $arr);
        return $id;
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
        //\Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $params[':id'] = $id;
        //$query = Mysql::interpolateQuery($sql, $params);
        //\Zx\Test\Test::object_log('query', $query, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM " . self::$table ." WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}