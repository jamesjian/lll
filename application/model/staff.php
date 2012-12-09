<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Test\Test;
use \App\Model\Base\Staff as Base_Staff;
use \Zx\Model\Mysql;

class Staff extends Base_Staff {

    /**
     * crypt see parent::create() method
     * @param string $staff_name
     * @param string $staff_password
     * @return  int or boolean when false
     */
    public static function verify_staff($staff_name, $staff_password) {

        $sql = "SELECT * FROM " . parent::$table . " WHERE name=:name";
        $params = array(':name' => $staff_name);
        Test::object_log('$staff_password', $staff_password, __FILE__, __LINE__, __CLASS__, __METHOD__);
        //$query = Mysql::interpolateQuery($sql, $params);

        $staff = Mysql::select_one($sql, $params);
        if ($staff) {
             \Zx\Test\Test::object_log('$staff', $staff, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if (crypt($staff_password, $staff['password']) == $staff['password']) {
                return $staff['name'];
            } else {
                return false;
            }
        } else {
            //     \Zx\Test\Test::object_log('query', 'false', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return false;
        }
    }

}