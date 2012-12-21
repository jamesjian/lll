<?php
namespace App\Model\Base;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Model\Mysql;

/*
status: 0: created(when report an claim), 2. confirmed(it's a real claim), 3. cancelled(it's not an claim)
 item type //1. question, 2. answer, 3. ad
 cat id   //1. 造谣诽谤（扣一分）， 2. 种族歧视（扣一分）， 3.色情 4. 暴力， 虐待（人或动物）（扣一分） 5. 违禁物品（毒品， 武器, 人体器官等）（扣一分） 6. 误导欺诈（扣一分）
 7. 广告嫌疑（扣一分） 8. 无内容或答非所问或灌水内容（将被删除， 不扣分）
  CREATE TABLE claim (
 id unsigned MEDIUMINT(8)   AUTO_INCREMENT PRIMARY KEY,
 item_type unsigned tinyint(3) NOT NULL DEFAULT '1',  
 item_id unsigned MEDIUMINT(8)   not null default 0, 
 claimant_id unsigned MEDIUMINT(7)   not null 0,  //can be empty
 cat_id unsigned tinyint(3) not null default '1',
  result text,
  reportable unsigned tinyint(1) not null default 1,// if completely valid, 0, else 1
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
*/
class Claim {
    public static $fields = array('id','item_type','item_id','claimant_id',
        'cat_id','result','status', 'date_created');
    public static $table = TABLE_ABUSE;
    
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
        $sql = "SELECT *
            FROM " . self::$table .  
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

    public static function delete($id) {
        $sql = "Delete FROM " . self::$table ." WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}
