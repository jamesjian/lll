<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\User as Base_User;
use \Zx\Model\Mysql;
use \Zx\Test\Test;

class User extends Base_User {
    public static function available_score($uid)
    {
        $user = parent::get_one($uid);
        if ($user && $user['score'] - $user['invalid_score'] - $user['ad_score']>0) {
            return true;
        } else  {
            return false;
        }
    }
    /**
     * the id of user table are not consecutive, so use limit to get one record
     * @return array
      学习使用百度
      
      妈妈可以学学如何使用百度搜索想要的东西。 
      在浏览器的地址栏输入
      
     http://www.baidu.com/
      
      
     然后在“百度一下”的左边长条框里输入想要找的内容， 比如说“骨质疏松症”， 然后点击“百度一下”， 就会列出与“骨质疏松症”有关的许多内容的简介， 
     页面的下部有不同的页数， 表明内容列表有很多页， 可以点击这些页号看其它页。 
     
     如果想找复杂一些的内容， 比如如何治疗骨质疏松症， 可以输入“骨质疏松症 治疗”或者“治疗 骨质疏松症”， 词汇之间用空格分开。 
      
     网上内容非常丰富， 常常可以发现想不到的东西。 坏处就是不能长时间看电脑， 否则眼睛会疲劳。 
     */
    public static function get_random_user()
    {
        $n = rand(4, 30000); //the maximum must be less than the lenth of user table
        $q = "SELECT * FROM " . parent::$table . " WHERE 1 LIMIT $n , 1";
        $user = Mysql::select_one($q);
        return $user;
    }
    /**
     * 
     * @return int default user for anonymous user
     */
    public static function get_default_question_user() {
        return parent::get_one(1); //匿名提问用户
    }

    /**
     * 
     * @return int default user for anonymous user
     */
    public static function get_default_answer_user() {
        return parent::get_one(2); //匿名回答用户
    }

    public static function increase_num_of_questions($uid) {
        $sql = "UPDATE " . parent::$table . " SET num_of_questions=num_of_questions+1 WHERE id=:id";
        $params = array(':id' => $uid);
        return Mysql::exec($sql, $params);
    }

    public static function increase_num_of_answers($uid) {
        $sql = "UPDATE " . parent::$table . " SET num_of_answers=num_of_answers+1 WHERE id=:id";
        $params = array(':id' => $uid);
        return Mysql::exec($sql, $params);
    }

    public static function disable_user($uid) {
        $arr['status'] = 0;
        return parent::update($uid, $arr);
    }

    /**
     *
     * @param integer $uid
     * @param string $password 
     * @return boolean
     */
    public static function password_is_correct($uid, $password) {
        $user = parent::get_one($uid);
        if ($user AND $user['password'] == md5($password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $uname
     * @return $user object or false
     */
    public static function get_user_by_uname($uname) {
        $sql = "SELECT * FROM " . parent::$table . " WHERE name=:name";
        $params = array(':name' => $uname);
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
        $sql = "SELECT * FROM " . parent::$table . " WHERE email=:email";
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
     * @param $uid
     * @return boolean if uname exists in users table, return true, else false
     */
    public static function exist_uid($uid) {
        $user = parent::get_one($uid);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $uname 
     * @return boolean if uname exists in users table, return user id, else false
     */
    public static function exist_uname($uname) {
        $sql = "SELECT * FROM " . parent::$table . " WHERE uname=:name";
        $params = array(':name' => $uname);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return $user['id'];
        } else {
            return false;
        }
    }

    /**
     * @param $email
     * @return boolean if email exists in users table, return user id, else false
     */
    public static function exist_email($email) {
        $sql = "SELECT * FROM " . parent::$table . " WHERE email=:email";
        $params = array(':email' => $email);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return $user['id'];
        } else {
            return false;
        }
    }

    /**
     * @param $name might be user name or email
     * @return boolean if name exists in email or uname fields in user table, return user id, else false
     */
    public static function exist_uname_or_email($name) {
        $sql = "SELECT * FROM " . parent::$table . " WHERE uname=:name OR email=:email";
        $params = array(':name' => $name, ':email' => $name);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
            return $user['id'];
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $uname
     * @return integer or boolean
     */
    public static function get_uid_by_email($email) {
        if ($user = self::get_user_by_email($email)) {
            return $user['id'];
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $uname
     * @return integer or boolean
     */
    public static function get_uid_by_uname($uname) {
        if ($user = self::get_user_by_uname($uname)) {
            return $user['id'];
        } else {
            return false;
        }
    }

    /**
     * check if user name matches password and enabled in user table
     * crypt see parent::create() method
     * @param <string> $uname  can be an email
     * @param <string> $password is md5 value
     * @return <boolean> if valid in user table, return true; otherwise return false;
     */
    public static function verify_user($uname, $password) {

        $sql = "SELECT *  FROM " . parent::$table . " 
            WHERE (uname=:name OR email=:name)  AND status=1";
        $params = array(':name' => $uname);
        //Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $user = Mysql::select_one($sql, $params);
        if ($user) {
          if (crypt($password, $user['password']) == $user['password']) {
            return $user;
          } else {
              return false;
          }
        } else {
            return false;
        }
    }

    /**
     * check if duplicate users exist in user table, for update user
     * @param <integer> $uid
     * @param <string> $uname
     * @return <type> if uname exists and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_uname($uid, $uname) {
        $user = self::get_user_by_uname($uname);
        if ($user && $user['id'] <> $uid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if duplicate users exist in user table, for update user
     * @param <integer> $uid
     * @param <string> $email
     * @return <type> if email exists and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_email($uid, $email) {
        $user = self::get_user_by_email($email);
        if ($user && $user['id'] <> $uid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if duplicate name or email exist in user table, for update user
     * @param <integer> $uid
     * @param <string> $name
     * @return <type> if name exists in uname or email fields and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_uname_or_email($uid, $name) {
        $sql = "SELECT *
            FROM " . parent::$table . " 
            WHERE (uname=:name OR email=:email) ";
        $params = array(':name' => $name, ':email' => $name);
        //Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $user = Mysql::select_one($sql, $params);
        if ($user && $user['id'] <> $uid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if a user has company
     * @param type $uid
     * @return boolean 
     */
    public static function user_has_question($uid) {
        $user = parent::get_one($uid);
        if ($user && $user->num_of_questions > 0)
            return true;
        else
            return false;
    }

    /**
     * check if a user has company
     * @param type $uid
     * @return boolean 
     */
    public static function user_has_answer($uid) {
        $user = parent::get_one($uid);
        if ($user && $user->num_of_answers > 0)
            return true;
        else
            return false;
    }

    /**
     * if has question or answer or others, cannot delete it
     * @param int $uid
     * @return boolean
     */
    public static function can_be_deleted($uid) {
        $user = parent::get_one($uid);
        if ($user && ($user['num_of_questions'] > 0 || $user['num_of_answers'] > 0 )) {
            return false;
        } else {
            return true;
        }
    }

    public static function get_users_by_page_num($where='1', $page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        $start = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $start, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_users() {
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

