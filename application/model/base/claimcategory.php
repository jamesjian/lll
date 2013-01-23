<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
 * 1. 造谣诽谤（-10)， 2. 种族或宗教歧视（-10)， 3.色情（-10) 4. 暴力， 虐待（人或动物（-10)） 
 * 5. 违禁物品（毒品， 武器, 人体器官等） （-10)
 * 6. 误导欺诈（-10) 7. 广告嫌疑(-1) 8. 与澳洲无关或无实质内容 (0)
 * if less than 10 categories, put into array instead of database
  CREATE TABLE claim_category (
 id tinyint(3) unsigned AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL DEFAULT '',
 claimant_score tinyint(2) unsigned not null default 1,
 defendant_score tinyint(2) unsigned not null default 1,
  description text,
  status tinyint(1) unsigned not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 */

class Claimcategory {
    public static $fields = array('id','title', 'description', 'claimant_score',
       'defendant_score',  'status', 'date_created');
    public static $table = TABLE_CLAIM_CATEGORY;
    
       public static function get_one($id) {
        return Zx_Model::get_one(self::$table, $id);
    }

    public static function get_one_by_where($where) {
        return Zx_Model::get_one_by_where(self::$table, $where);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        return Zx_Model::get_all(self::$table, $where, $offset, $row_count, $order_by, $direction);
    }

    public static function get_num($where = '1') {
        return Zx_Model::get_num(self::$table, $where);
    }

    public static function create($arr) {
        $arr['date_created'] = date('Y-m-d h:i:s');
        return Zx_Model::create(self::$table, self::$fields, $arr);
    }

    public static function update($id, $arr) {
        return Zx_Model::update(self::$table, $id, self::$fields, $arr);
    }

    public static function delete($id) {
        return Zx_Model::delete(self::$table, $id);
    }

}