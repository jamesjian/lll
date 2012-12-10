<?php
namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
this one is for user vote question/answer/ad, when user vote them, it will be recorded here
prevent one user from voting one item multiple times
  CREATE TABLE usertoanswer (
  user_id mediumint(8) not null 0,
 item_type tinyint(1) NOT NULL DEFAULT '1',  //1. question, 2. answer, 3. ad
 item_id mediumint(8) not null default 0, 
   primary key (user_id, item_type, item_id)
 } engine=innodb default charset=utf8
*/
class Vote {
    public static $fields = array('user_id','item_type', 'item_id');
    public static $table = TABLE_VOTE;
     /**
     *
     * @param int $user_id, $item_type and $item_id is a composite primary key
     * @return 1D array or boolean when false 
     */
    public static function get_one($user_id, $item_type, $item_id) {
        $sql = "SELECT *
            FROM " . self::$table . " 
            WHERE user_id=:user_id AND item_type=:item_type AND item_id=:item_id
        ";
        $params = array(':user_id' => $user_id,
            ':item_type'=> $item_type,
            ':item_id'=> $item_id
            );
        return Mysql::select_one($sql, $params);
    }

    /**
     
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT *
            FROM " . self::$table . " 
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        $sql = "SELECT *
            FROM " . self::$table . " 
            WHERE $where
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
        $insert_arr = array(); $params = array();
        $arr['date_created'] = date('Y-m-d h:i:s');
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

    public static function delete($user_id, $item_type, $item_id) {
        $sql = "Delete FROM " . self::$table ." WHERE 
            user_id=:user_id AND item_type=:item_type AND item_id=:item_id
        ";
        $params = array(':user_id' => $user_id,
            ':item_type'=> $item_type,
            ':item_id'=> $item_id
            );
        return Mysql::exec($sql, $params);
    }

}