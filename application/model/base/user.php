<?php
namespace App\Model\Base;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql;

/*
 * user, question, answer, ad, tag, abuse
 *  # num_of_questions+num_of_answers+num_of_ads+num_of_question_votes+num_of_answer_votes
 * #score has been consumed by ad
  CREATE TABLE user (
  id unsigned mediumint(7) AUTO_INCREMENT primary key,
  uname varchar(30) not null default '',
  password varchar(255) NOT NULL DEFAULT '',
  email varchar(255) not null default '' unique ,
  image varchar(255) not null default '' ,
  num_of_questions unsigned mediumint(6) not null default 0,
  num_of_answers unsigned mediumint(6) not null default 0, 
  num_of_ads unsigned 30(6) not null default 0, 
  score unsigned mediumint(6) not null default 0,  
 invalid_score unsigned MEDIUMINT(6) not null default 0,
 ad_score unsigned MEDIUMINT(6) not null default 0, 
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 */

class User {

    public static $fields = array('id', 'uname', 'password', 'email',
        'image', 'num_of_questions','num_of_answers','num_of_ads',
        'score', 'invalid_score','ad_score', 'status', 'date_created');
    public static $table = TABLE_USER;

    public static function get_one($id) {
        $sql = "SELECT * FROM " . self::$table . " WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT *  FROM " . self::$table . " WHERE $where";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'b.date_created', $direction = 'DESC') {
        $sql = "SELECT *  FROM " . self::$table . " WHERE $where
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

    /**
     * use crypt to store password, 
     * $password = crypt('mypassword'); 
        if (crypt($user_input, $password) == $password) {
            echo "Password verified!";
        }

     * @param array $arr
     * @return false or id
     */
    public static function create($arr) {
        $insert_arr = array();
        $params = array();
        $arr['date_created'] = date('Y-m-d h:i:s');
        $arr['password'] = crypt($arr['password']);
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
        $params[':id'] = $id;

        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM " . self::$table . " WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}