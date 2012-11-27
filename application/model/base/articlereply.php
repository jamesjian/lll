<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

class Blogreply {

    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT a.*, ac.title as cat_name,
            FROM " . self::$table . " a
            LEFT JOIN article_category ac ON a.cat_id=ac.id
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
        $sql = "SELECT a.*, ac.title as cat_name,
            FROM " . self::$table . " a
            LEFT JOIN article_category ac ON a.cat_id=ac.id
            WHERE :where
        ";
		$params = array(':where'=>$where);
        return Mysql::select_one($sql, $params);
    }

	
    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'a.display_order', $direction = 'ASC') {
        $sql = "SELECT a.*, ac.title as cat_name,
            FROM " . self::$table . " a
            LEFT JOIN article_category ac ON a.cat_id=ac.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        return Mysql::select_all($sql);
    }

    public static function create($arr) {
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