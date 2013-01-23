<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;
/*
CREATE TABLE IF NOT EXISTS `page_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
*/
class Pagecategory {
    public static $fields = array('id','title',
        'description',  'status', 'date_created');
    public static $table = TABLE_PAGE_CATEGORY;
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