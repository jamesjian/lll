<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;
use App\Model\Question as Model_Question;

/*
  CREATE TABLE answer (
  id unsigned MEDIUMINT(8) AUTO_INCREMENT PRIMARY KEY,
  question_id unsigned MEDIUMINT(8) not null default 0,
  user_id unsigned MEDIUMINT(8) not null default 0,
    user_name varchar(30) not null '',  #user name is fixed
  content text,
  rank unsigned MEDIUMINT(8) not null default 0,
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 */

class Answer {
    public static $fields = array('id','user_id', 'user_name',
        'content', 'rank', 'status', 'date_created');
    public static $table = TABLE_ANSWER;
    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT a.*, q.title, q.tag_names
            FROM  " . self::$table .  " a
            LEFT JOIN " . Model_Question::$table . " q ON q.id=a.question_id
            WHERE id=:id
        ";
        $params = array(':id' => $id);


        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT a.*, q.title, q.tag_names
            FROM  " . self::$table .  " a
            LEFT JOIN " . Model_Question::$table . " q ON q.id=a.question_id
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        $sql = "SELECT a.*, q.title, q.tag_names
            FROM " . self::$table .  "  a
            LEFT JOIN " . Model_Question::$table . "  q ON q.id=a.question_id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num FROM" . self::$table . "WHERE $where";
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