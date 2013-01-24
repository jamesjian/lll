<?php

namespace App\Model\Base;

use \App\Model\Article as Model_Article;
use \Zx\Model\Mysql as Zx_Mysql;

/*
 * article replies are only created by user
 * admin can update its content
CREATE TABLE ts8wl_article_reply(
id MEDIUMINT( 8 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY ,
article_id MEDIUMINT( 8 ) UNSIGNED DEFAULT 0,
uid MEDIUMINT( 8 ) UNSIGNED DEFAULT 0,
uname VARCHAR( 255 ) NOT NULL DEFAULT  '',
#user name is fixed
content TEXT,
STATUS TINYINT( 1 ) NOT NULL DEFAULT 1,
date_created DATETIME
) ENGINE = INNODB DEFAULT CHARSET = utf8
 alter table  ts8wl_article_reply add index article_id (article_id);

 */

class Articlereply {

    public static $fields = array('id', 'article_id', 'uid', 'uname', 'content', 'status', 'date_created');
    public static $table = TABLE_ARTICLE_REPLY;

    const S_ACTIVE = 1;
    const S_INACTIVE = 2; //keep it but not display it

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT ar.*, a.title as article_name
            FROM " . self::$table . " ar
            LEFT JOIN " . Model_Article::$table . " a ON ar.article_id=a.id
            WHERE a.id=:id
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
        $sql = "SELECT ar.*, ac.title as article_name
            FROM " . self::$table . " ar
            LEFT JOIN " . Model_Article::$table . " a ON ar.article_id=a.id
            WHERE :where
        ";
        $params = array(':where' => $where);
        return Zx_Mysql::select_one($sql, $params);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'a.display_order', $direction = 'ASC') {
        $sql = "SELECT ar.*, ac.title
            FROM " . self::$table . " ar
            LEFT JOIN " . Model_Article::$table . " a ON ar.article_id=a.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        return Zx_Mysql::select_all($sql);
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