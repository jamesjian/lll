<?php
namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
 * primary key is a composite primary key (uid, item_type, item_id)
 * only logged in user can vote because it generates "most popular" order
this one is for user vote question/answer/ad, when user vote them, it will be recorded here
prevent one user from voting one item multiple times
  CREATE TABLE ts8wl_vote (
  uid mediumint(8) unsigned not null default 0,
 item_type tinyint(1) unsigned NOT NULL DEFAULT '1', 
 item_id mediumint(8) unsigned not null default 0, 
 * id1 varchar(44) not null ,
 date_created datetime,
   primary key (uid, item_type, item_id)
 ) engine=innodb default charset=utf8
* 
*/
class Vote {
    public static $fields = array('uid','item_type', 'item_id','id1', 'date_created');
    public static $table = TABLE_VOTE;
     /**
     *
     * @param int $uid, $item_type and $item_id is a composite primary key
     * @return 1D array or boolean when false 
     */
    public static function get_one($uid, $item_type, $item_id) {
        $sql = "SELECT *
            FROM " . self::$table . " 
            WHERE uid=:uid AND item_type=:item_type AND item_id=:item_id
        ";
        $params = array(':uid' => $uid,
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
        $arr['date_created'] = date('Y-m-d h:i:s');
        return Zx_Model::create(self::$table, self::$fields, $arr);
    }

    /**
     * usually no update
     */
    public static function update() {
        return true;
    }

    public static function delete($uid, $item_type, $item_id) {
        $sql = "Delete FROM " . self::$table ." WHERE 
            uid=:uid AND item_type=:item_type AND item_id=:item_id
        ";
        $params = array(':uid' => $uid,
            ':item_type'=> $item_type,
            ':item_id'=> $item_id
            );
        return Mysql::exec($sql, $params);
    }

}