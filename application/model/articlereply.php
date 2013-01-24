<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Articlereply as Base_Articlereply;
use \Zx\Model\Mysql as Zx_Mysql;

class Articlereply extends Base_Articlereply {

   public static function get_replies_by_page_num($where = '1', $page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        switch ($order_by) {
            case 'id':
            case 'date_created':
            case 'cat_id':
                $order_by = 'ar.' . $order_by;
                break;
            case 'title':
                $order_by = 'a.' . $order_by;
                break;
            case 'uname':
                $order_by = 'u.' . $order_by;
                break;
            default:
                $order_by = 'b.date_created';
        }
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        //$where = '1';
        $start = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $start, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }

    public static function get_num_of_replies($where = '1') {
        return parent::get_num($where);
    }
}