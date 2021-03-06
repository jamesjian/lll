<?php

namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql as Zx_Mysql;
use \App\Model\Claimcategory as Model_Claimcategory;

/*
 * claim can be made anyone
  status: 0: created(when report an claim), 2. confirmed(the item is bad), 3. cancelled(the item is good)
  item type //1. question, 2. answer, 3. ad
 * item_id is id rather than id1
  cat id   //1. 造谣诽谤（扣一分）， 2. 种族歧视（扣一分）， 3.色情 4. 暴力， 虐待（人或动物）（扣一分） 5. 违禁物品（毒品， 武器, 人体器官等）（扣一分） 6. 误导欺诈（扣一分）
  7. 广告嫌疑（扣一分） 8. 无内容或答非所问或灌水内容（将被删除， 不扣分）
 * 
  CREATE TABLE claim (
  id unsigned MEDIUMINT(8)   AUTO_INCREMENT PRIMARY KEY,
  item_type unsigned tinyint(3) NOT NULL DEFAULT '1',
  item_id unsigned MEDIUMINT(8)   not null default 0,
  claimant_id unsigned MEDIUMINT(7)   not null 0,  //can be empty
  cat_id unsigned tinyint(3) not null default '1',
  result text,
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 alter table  ts8wl_claim add index cat_id (cat_id); 
 alter table  ts8wl_claim add index claimant_id (claimant_id); 
 */

class Claim {

    public static $fields = array('id', 'item_type', 'item_id', 'claimant_id',
        'cat_id', 'result', 'status', 'date_created');
    public static $table = TABLE_CLAIM;

    //for status

    const S_CREATED = 0;  //when a claim is created
    const S_CORRECT_CLAIM = 1; //if this claim is correct
    const S_WRONG_CLAIM = 2;  //if this claim is wrong

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */

    public static function get_one($id) {
        $sql = "SELECT * FROM " . self::$table . ' c' .
                " LEFT JOIN " . Model_Claimcategory::$table . ' cc ON c.cat_id=cc.id ' .
                " WHERE id=:id";
        $params = array(':id' => $id);
        return Zx_Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT * FROM " . self::$table . ' c' .
                " LEFT JOIN " . Model_Claimcategory::$table . ' cc ON c.cat_id=cc.id ' .
                " WHERE $where
        ";
        return Zx_Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        $sql = "SELECT * FROM " . self::$table . ' c' .
                " LEFT JOIN " . Model_Claimcategory::$table . ' cc ON c.cat_id=cc.id ' .
                " WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT * FROM " . self::$table . ' c' .
                " LEFT JOIN " . Model_Claimcategory::$table . ' cc ON c.cat_id=cc.id ' .
                " WHERE $where";
        $result = Zx_Mysql::select_one($sql);
        if ($result) {
            return $result['num'];
        } else {
            return false;
        }
    }

    public static function create($arr) {
        $arr['date_created'] = date('Y-m-d h:i:s');
        return Zx_Mysql::create(self::$table, self::$fields, $arr);
    }

    public static function update($id, $arr) {
        return Zx_Mysql::update(self::$table, $id, self::$fields, $arr);
    }

    public static function delete($id) {
        return Zx_Mysql::delete(self::$table, $id);
    }

}
