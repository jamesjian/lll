<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Body as Base_Body;
use \Zx\Model\Mysql;

class Body extends Base_Body {
    public static function get_bodys_by_page_num($where = '1', $page_num = 1, $order_by = 'date_created', $direction = 'ASC') {
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_bodys($where = 1) {
        return parent::get_num($where);
    }
    /**
     * 
     * @param int $id
     * @param string $tag_name
     * @return boolean 
     */
    public static function duplicate_en($id, $en) {
        $dbh = Mysql::get_dbh();
        $en = $dbh->quote(strtolower($en));
        $where = spritf(" en='%s' AND id<>%n ", $en, $id);
        if ($tag = parent::get_one_by_where($where)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 
     * @param type $en
     * @return record or false
     */
    public static function exist_by_en($en) {
        $dbh = Mysql::get_dbh();
        $en = $dbh->quote(strtolower($en));
        $where = sprintf(" en=%s",$en);
        if ($tag = parent::get_one_by_where($where)) {
            return $tag;
        } else {
            return false;
        }
    }    
}