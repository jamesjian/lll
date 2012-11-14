<?php

namespace App\Model;

use \App\Model\Base\User as Base_User;
use \Zx\Model\Mysql;
use \Zx\Test\Test;

class User extends Base_User {

    public static function increase_num_of_questions($user_id) {
        $sql = 'UPDATE user SET num_of_questions=num_of_questions+1 WHERE id=:id';
        $params = array(':id' => $user_id);
        return Mysql::exec($sql, $params);
    }
    public static function increase_num_of_answers($user_id) {
        $sql = 'UPDATE user SET num_of_answers=num_of_answers+1 WHERE id=:id';
        $params = array(':id' => $user_id);
        return Mysql::exec($sql, $params);
    }

    public static function disable_user($user_id) {
        $arr['status'] = 0;
        return parent::update($user_id, $arr);
    }

    /**
     *
     * @param integer $user_id
     * @param string $password 
     * @return boolean
     */
    public static function password_is_correct($user_id, $password) {
        $user = parent::get_one($user_id);
        if ($user AND $user['password'] == md5($password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $user_name
     * @return $user object or false
     */
    public static function get_user_by_user_name($user_name) {
        $sql = "SELECT * FROM user WHERE name=:name";
        $params = array(':name' => $user_name);
        //Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
    /**
     * @param string $email
     * @return $user object or false
     */
    public static function get_user_by_email($email) {
        $sql = "SELECT * FROM user WHERE email=:email";
        $params = array(':email' => $email);
        //Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    /**
     * @param $user_id
     * @return boolean if user_name exists in users table, return true, else false
     */
    public static function exist_user_id($user_id) {
        $user = parent::get_one($user_id);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $user_name 
     * @return boolean if user_name exists in users table, return true, else false
     */
    public static function exist_user_name($user_name) {
        $sql = "SELECT * FROM user WHERE name=:name";
        $params = array(':name' => $user_name);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $email
     * @return boolean if email exists in users table, return true, else false
     */
    public static function exist_email($email) {
        $sql = "SELECT * FROM user WHERE email=:email";
        $params = array(':email' => $email);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $name might be user name or email
     * @return boolean if name exists in email or user_name fields in user table, return true, else false
     */
    public static function exist_user_name_or_email($name) {
        $sql = "SELECT * FROM user WHERE user_name=:name OR email=:email";
        $params = array(':name' => $name, ':email' => $name);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    
    /**
     *
     * @param string $user_name
     * @return integer or boolean
     */
    public static function get_user_id_by_email($email) {
        if ($user = self::get_user_by_email($email)) {
            return $user['id'];
        } else {
            return false;
        }
    }
    /**
     *
     * @param string $user_name
     * @return integer or boolean
     */
    public static function get_user_id_by_user_name($user_name) {
        if ($user = self::get_user_by_user_name($user_name)) {
            return $user['id'];
        } else {
            return false;
        }
    }
       /**
     * check if user name matches password and enabled in user table
     * @param <string> $user_name  can be an email
     * 
     * @param <string> $password is md5 value
     * @return <boolean> if valid in user table, return true; otherwise return false;
     */
    public static function valid_user($user_name, $password) {
        
        $sql = "SELECT *
            FROM user
            WHERE (user_name=:name OR email=:name) AND password=:password AND status=1";
        $params = array(':name' => $user_name, ':password' => $password);
        //Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    /**
     * check if duplicate users exist in user table, for update user
     * @param <integer> $user_id
     * @param <string> $user_name
     * @return <type> if user_name exists and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_user_name($user_id, $user_name) {
        $user = self::get_user_by_user_name($user_name);
        if ($user && $user['id']<>$user_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if duplicate users exist in user table, for update user
     * @param <integer> $user_id
     * @param <string> $email
     * @return <type> if email exists and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_email($user_id, $email) {
        $user = self::get_user_by_email($email);
        if ($user && $user['id']<>$user_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if duplicate name or email exist in user table, for update user
     * @param <integer> $user_id
     * @param <string> $name
     * @return <type> if name exists in user_name or email fields and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_user_name_or_email($user_id, $name) {
        $sql = "SELECT *
            FROM user
            WHERE (user_name=:name OR email=:email) ";
        $params = array(':name' => $name, ':email' => $name);
        //Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $user = Mysql::select_one($sql, $params);
        if ($user && $user['id']<>$user_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if a user has company
     * @param type $user_id
     * @return boolean 
     */
    public static function user_has_question($user_id) {
        $user = parent::get_one($user_id);
        if ($user && $user->num_of_questions > 0)
            return true;
        else
            return false;
    }
    /**
     * check if a user has company
     * @param type $user_id
     * @return boolean 
     */
    public static function user_has_answer($user_id) {
        $user = parent::get_one($user_id);
        if ($user && $user->num_of_answers > 0)
            return true;
        else
            return false;
    }

    /**
     * if has question or answer or others, cannot delete it
     * @param int $user_id
     * @return boolean
     */
    public static function can_be_deleted($user_id) {
        $user = parent::get_one($user_id);
        if ($user && ($user['num_of_questions'] > 0 || $user['num_of_answers'] > 0 )) {
            return false;
        } else {
            return true;
        }
    }

    public static function get_users_by_page_num($page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        $where = '1';
        $start = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $start, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_pages() {
        return parent::get_num();
    }
 public static function get_active_users_by_page_num($page_num = 1, $order_by = 'rank', $direction = 'ASC') {
        $where = ' status=1 ';
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_users($where = '1') {
        $where = " status=1  AND ($where)";
        return parent::get_num();
    }

}

