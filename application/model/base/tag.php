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
    public static $fields = array('id', 'name', 'num_of_questions', 'num_of_ads',
        'num_of_views', 'transfer_to', 'status', 'date_created');
    public static $table = TABLE_TAG;

    const S_DISABLED = 0; //if this tag is wrong and disabled by admin such as porn
    const S_ACTIVE = 1;  //if this tag is active 

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