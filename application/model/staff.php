<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Test\Test;
use \App\Model\Base\Staff as Base_Staff;
use \Zx\Model\Mysql;

class Staff extends Base_Staff {

    /**
     * only for staff  group id=1
     * @param string $staff_name
     * @param string $staff_password
     * @return  int or boolean when false
     */
    public static function verify_staff($staff_name, $staff_password) {

        $sql = "SELECT * FROM " . parent::$table . 
            " WHERE name=:name AND password=:password";
		$params = array(':name'=>$staff_name, ':password'=>$staff_password);
        //Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
 //$query = Mysql::interpolateQuery($sql, $params);
   //     \Zx\Test\Test::object_log('query', $query, __FILE__, __LINE__, __CLASS__, __METHOD__);
                
        $staff = Mysql::select_one($sql, $params);
        if ($staff) {
     //       \Zx\Test\Test::object_log('query', 'true', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return $staff['name'];
        } else {
       //     \Zx\Test\Test::object_log('query', 'false', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return false;
        }
    }

}