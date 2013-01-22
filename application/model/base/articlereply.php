<?php

namespace App\Model\Base;

use \App\Model\Article as Model_Article;
use \Zx\Model\Mysql;

/*
 * article replies are only created by user
 * admin can update its content
  CREATE TABLE article_reply (id mediumint(8) unsigned AUTO_INCREMENT PRIMARY KEY,
  article_id mediumint(8) unsigned default 0,
  uid mediumint(8) unsigned default 0,
  uname varchar(255) not null default '',  #user name is fixed
  content text,
  status tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
  
 */
class Articlereply {
    public static $fields = array('id','article_id','uid','uname', 'content', 'status', 'date_created');
    public static $table = TABLE_ARTICLE_CATEGORY;
    const S_ACTIVE = 1;  
    const S_INACTIVE = 2;  
    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT ar.*, a.title as article_name,
            FROM " . self::$table . " ar
            LEFT JOIN " . Model_Article::$table . " a ON ar.article_id=a.id
            WHERE a.id=:id
        ";
		$params = array(':id'=>$id);
        return Mysql::select_one($sql, $params);
    }    
	/**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT ar.*, ac.title as article_name,
            FROM " . self::$table . " ar
            LEFT JOIN " . Model_Article::$table . " a ON ar.article_id=a.id
            WHERE :where
        ";
		$params = array(':where'=>$where);
        return Mysql::select_one($sql, $params);
    }

	
    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'a.display_order', $direction = 'ASC') {
        $sql = "SELECT ar.*, ac.title, u.uname,
            FROM " . self::$table . " ar
            LEFT JOIN " . Model_Article::$table . " a ON ar.article_id=a.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        return Mysql::select_all($sql);
    }

    public static function create($arr) {
        $arr['date_created'] = date('Y-m-d h:i:s');
        $sql = "INSERT INTO article SET " . Mysql::concat_field_name_and_value($arr);
        return Mysql::insert($sql);
    }

    public static function update($id, $arr) {
        $sql = "UPDATE article SET " . Mysql::concat_field_name_and_value($arr) .
                ' WHERE id=:id';
		$params = array(':id'=>$id);
        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM article WHERE id=:id";
		$params = array(':id'=>$id);
        return Mysql::exec($sql, $params);
    }

}