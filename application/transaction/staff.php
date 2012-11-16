<?php

namespace App\Transaction;

use \App\Model\Staff as Model_Staff;
use \Zx\Message\Message;

class Staff {

    public static function staff_has_loggedin()
    {
        if (isset($_SESSION['staff'])) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * only for staff 
     * @param string $staff_name
     * @param string $staff_password
     * @return  boolean
     */
    public static function verify_staff($staff_name, $staff_password) {
        $staff_password = md5($staff_password);
        if ($staff_name = Model_Staff::verify_staff($staff_name, $staff_password)) {
            //session
            $_SESSION['staff'] = array(
                'staff_name' => $staff_name,
            );
            return true;
        } else {
            //error message
            Message::set_error_message('staff name or password is wrong');
            return false;
        }
    }
    public static function staff_logout()
    {
        if (isset($_SESSION['staff'])) unset($_SESSION['staff']);
        return true;
    }

}