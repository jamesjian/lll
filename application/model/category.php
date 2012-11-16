<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
    
 */

class Category {
    public static function get_cats_by_catgroup_id($group_id) {
        $sql = "SELECT * FROM category  WHERE group_id=:group_id";
        $params = array(':group_id' => $group_id);
        return Mysql::exec($sql, $params);
    }

}