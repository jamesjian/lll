<?php
namespace App\Model\Base;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql;

/*
 * user, question, answer, ad, tag, claim
 *  # num_of_questions+num_of_answers+num_of_ads+num_of_question_votes+num_of_answer_votes
 * #score has been consumed by ad
 * num_of_questions * score of question+num_of_answers * score of _answer 
 * - invalid score
 * 
 * 
  CREATE TABLE user (
  id unsigned mediumint(7) AUTO_INCREMENT primary key,
   id1 varchar(44) not null unique,
  uname varchar(30) not null default '',
  password varchar(255) NOT NULL DEFAULT '',
  email varchar(255) not null default '' unique ,
  image varchar(255) not null default '' ,
  num_of_questions unsigned mediumint(6) not null default 0,
  num_of_answers unsigned mediumint(6) not null default 0, 
  num_of_ads unsigned 30(6) not null default 0, 
  score unsigned mediumint(6) not null default 0,  
 invalid_score unsigned MEDIUMINT(6) not null default 0,
 ad_score unsigned MEDIUMINT(6) not null default 0, 
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 */

class User {

    public static $fields = array('id','id1', 'uname', 'password', 'email',
        'image', 'num_of_questions','num_of_answers','num_of_ads',
        'score', 'invalid_score','ad_score', 'status', 'date_created');
    public static $table = TABLE_USER;
    //for status
    const S_REGISTERED = 0;  //when a user registered, but not activated
    const S_ACTIVE = 1; //if this user is active
    const S_DISABLED = 2;  //if this user is disabled by admin
    const M_SUCCESSFUL_REGISTRATION = "感谢您注册账户， 我们已经发送邮件到您的电子邮箱，请查看邮件并激活您的账户。" ;
    const M_SUCCESSFUL_ACTIVATION = "您的账户已经激活成功， 您现在就可以登录您的账户。 " ;
    const M_REPEAT_ACTIVATION = "我们已经发送了激活邮件到您的邮箱， 请您检查邮箱并激活您的账户." ;
    const M_UNSUCCESSFUL_ACTIVATION = "对不起， 您的账户未激活成功， 请重新激活， 或由网站重新发送一个激活邮件。";
    const M_WRONG_VCODE = '您未输入验证码或输入的验证码不正确， 请重新输入';
    const M_INVALID_USERNAME = '用户名或电子邮箱已经被注册， 请用其他用户名或电子邮箱注册。';
    const M_REGISTRATION_SYSTEM_ERROR = '系统出错， 请重新注册。';
    const M_LOGIN_SYSTEM_ERROR = '系统出错， 请重新登录。';
    const M_WRONG_USERNAME = "对不起， 您的用户名或邮箱不正确， 请重新输入.";
    
    public static function get_one($id) {
        $sql = "SELECT * FROM " . self::$table . " WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT *  FROM " . self::$table . " WHERE $where";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'score', $direction = 'DESC') {
        $sql = "SELECT *  FROM " . self::$table . " WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
        //\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num FROM " . self::$table . " WHERE $where";
        $result = Mysql::select_one($sql);
        if ($result) {
            return $result['num'];
        } else {
            return false;
        }
    }

    /**
     * use crypt to store password, 
     * $password = crypt('mypassword'); 
        if (crypt($user_input, $password) == $password) {
            echo "Password verified!";
        }
    
     * @param array $arr
     * @return false or id
     */
    public static function create($arr) {
        $insert_arr = array();
        $params = array();
        $arr['date_created'] = date('Y-m-d h:i:s');
        $arr['password'] = crypt($arr['password']);
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $insert_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $insert_str = implode(',', $insert_arr);
        $sql = 'INSERT INTO ' . self::$table . ' SET ' . $insert_str;
        $id = Mysql::insert($sql, $params);
        $arr = array('id1'=>2*$id . md5($id));  //generate id1
        self::update($id, $arr);
        return $id;
    }

    public static function update($id, $arr) {
        $update_arr = array();
        $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $update_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }

        $update_str = implode(',', $update_arr);
        $sql = 'UPDATE ' . self::$table . ' SET ' . $update_str . ' WHERE id=:id';
        $params[':id'] = $id;

        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM " . self::$table . " WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}