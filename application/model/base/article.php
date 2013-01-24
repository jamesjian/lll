<?php

namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql as Zx_Mysql;
use App\Model\Articlecategory as Model_Articlecategory;

/*
CREATE TABLE ts8wl_article(
id MEDIUMINT( 8 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
title VARCHAR( 255 ) NOT NULL DEFAULT  '',
cat_id TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT 1,
keyword VARCHAR( 255 ) NOT NULL DEFAULT  '',
abstract TEXT,
content TEXT,
num_of_views MEDIUMINT( 8 ) DEFAULT 0,
STATUS TINYINT( 1 ) NOT NULL DEFAULT 1,
date_created DATETIME
) ENGINE = INNODB DEFAULT CHARSET = utf8
 
 alter table  ts8wl_article add index cat_id (cat_id);
 
 * 
 * alter table article add index cat_id (cat_id);
 * show index from article;
 */

class Article {

    public static $fields = array('id', 'title', 'cat_id',
        'keyword', 'abstract', 'content', 'num_of_views', 'status', 'date_created');
    public static $table = TABLE_ARTICLE;

    const S_ACTIVE = 1;
    const S_INACTIVE = 2;

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM " . self::$table . " b
            LEFT JOIN " . Model_Articlecategory::$table . " bc ON b.cat_id=bc.id
            WHERE b.id=:id
        ";
        $params = array(':id' => $id);
        return Zx_Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM " . self::$table . " b
            LEFT JOIN " . Model_Articlecategory::$table . " bc ON b.cat_id=bc.id
            WHERE $where
        ";
        return Zx_Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'b.date_created', $direction = 'DESC') {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM " . self::$table . " b
            LEFT JOIN " . Model_Articlecategory::$table . " bc ON b.cat_id=bc.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
//\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(bc.id) AS num
            FROM " . self::$table . "  b
            LEFT JOIN " . Model_Articlecategory::$table . " bc ON b.cat_id=bc.id
            WHERE $where
        ";
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