<?php
defined('SYSTEM_PATH') or die('No direct script access.');
namespace App\Model;

use \App\Model\Base\Ad as Base_Ad;
use \Zx\Model\Mysql;

class Ad extends Base_Ad {
       public static function get_num_of_active_ads_by_user_id($user_id, $where=1) {
        $where = " a.status=1 AND a.user_id=$user_id AND ($where)" ;
        return parent::get_num($where);
    }   

}