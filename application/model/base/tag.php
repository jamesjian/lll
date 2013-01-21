<?php
namespace App\Model\Base;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Model\Mysql;

/*
 * if a tag is disabled, they will be removed from tids and tnames field in question table  
  CREATE TABLE tag (
  id unsigned smallint(4) AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL DEFAULT '',
  num_of_questions unsigned mediumint(8) default 0,
  num_of_ads unsigned mediumint(8) default 0,
  num_of_views unsigned int(11) default 0,
  transfer_to varchar(255) not null default '',
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 */

class Tag {
    //num_of_views is only for questions currently
    public static $fields = array('id','name','num_of_questions','num_of_ads',
        'num_of_views','transfer_to', 'status', 'date_created');
    public static $table = TABLE_TAG;
    const S_DISABLED=0; //if this tag is wrong and disabled by admin such as porn
    const S_ACTIVE=1;  //if this tag is active 
    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT * FROM "  . self::$table . " WHERE id=:id";
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
            FROM "  . self::$table . " 
            WHERE $where
        ";
            //\Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'b.date_created', $direction = 'DESC') {
        $sql = "SELECT * FROM "  . self::$table . " 
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
        //\Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        return Mysql::insert($sql, $params);
    }

    /**
     * 
     * @param int $id
     * @param array $arr
     * @return boolean
     */
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