<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Score as Base_Score;
use \Zx\Model\Mysql;

class Score extends Base_Score {
 public static function get_records_by_uid_and_page_num($uid, $where = '1', $page_num = 1, $order_by = 'date_created', $direction = 'ASC') {
        $where = ' (uid=' . $uid . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_records_by_uid($uid, $where = '1') {
        $where = ' (uid=' . $uid . ')  AND (' . $where . ')';
        return parent::get_num($where);
    }
}