<?php

namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql;

/*
 * score:  total got (when create question/answer, vote), always increase
 * invalid_score: total penalties (when disable), always increase
 * ad_score: used by ad (when create ad/adjust weight), can increase or decrease
 * remaining/available score = score - invalid_score - ad_score
 * 
 * num_of_questions:
 * create a question: add 
 * vote a question: add
 * delete a question(if can delete) by user or admin: subtract
 * disable an answer by admin: subtract 
 * 
 * num_of_answers:
 * create an answer: add
 * vote an answer: add
 * delete an answer(if can delete) by user or admin: subtract
 * disable an answer by admin: subtract 
 * 
 * num_of_ads:
 * create an ad: add
 * delete an ad(if can delete) by user or admin: subtract
 * disable an ad by admin: subtract 
 * 
 * score:
 * create a question: add score
 * delete a question(if can delete) by user or admin: subtract score
 * disable a question by admin: subtract penalty
 * 
 * create an answer: add score
 * delete an answer(if can delete) by user or admin: subtract score
 * disable an answer by admin: subtract penalty
 * 
 * create an ad: no score
 * delete an ad: no score
 * disable an ad: subtract penalty
 * 
 * 
 * 
 * 
  CREATE TABLE ts8wl_user (
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
  alter table  ts8wl_user add index num_of_questions (num_of_questions); 
  alter table  ts8wl_user add index num_of_answers (num_of_answers); 
  alter table  ts8wl_user add index score (score); 

 */

class User {

    public static $fields = array('id', 'id1', 'uname', 'password', 'email',
        'image', 'num_of_questions', 'num_of_answers', 'num_of_ads',
        'score', 'invalid_score', 'ad_score', 'status', 'date_created');
    public static $table = TABLE_USER;

    //for status

    const S_REGISTERED = 0;  //when a user registered, but not activated
    const S_ACTIVE = 1; //if this user is active
    const S_DISABLED = 2;  //if this user is disabled by admin
    const M_SUCCESSFUL_REGISTRATION = "感谢您注册账户， 我们已经发送邮件到您的电子邮箱，请查看邮件并激活您的账户。";
    const M_SUCCESSFUL_ACTIVATION = "您的账户已经激活成功， 您现在就可以登录您的账户。 ";
    const M_REPEAT_ACTIVATION = "我们已经发送了激活邮件到您的邮箱， 请您检查邮箱并激活您的账户.";
    const M_UNSUCCESSFUL_ACTIVATION = "对不起， 您的账户未激活成功， 请重新激活， 或由网站重新发送一个激活邮件。";
    const M_WRONG_VCODE = '您未输入验证码或输入的验证码不正确， 请重新输入';
    const M_INVALID_USERNAME = '用户名或电子邮箱已经被注册， 请用其他用户名或电子邮箱注册。';
    const M_REGISTRATION_SYSTEM_ERROR = '系统出错， 请重新注册。';
    const M_LOGIN_SYSTEM_ERROR = '系统出错， 请重新登录。';
    const M_WRONG_USERNAME = "对不起， 您的用户名或邮箱不正确， 请重新输入.";

    public static function get_one($id) {
        return Zx_Model::get_one(self::$table, $id);
    }

    public static function get_one_by_where($where) {
        return Zx_Model::get_one_by_where(self::$table, $where);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        return Zx_Model::get_all(self::$table, $where, $offset, $row_count, $order_by, $direction);
    }

    public static function get_num($where = '1') {
        return Zx_Model::get_num(self::$table, $where);
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
        $arr['date_created'] = date('Y-m-d h:i:s');
        $arr['password'] = crypt($arr['password']);
        $id = Zx_Model::create(self::$table, self::$fields, $arr);
        $arr = array('id1' => 2 * $id . md5($id));  //generate id1
        self::update($id, $arr);
    }

    public static function update($id, $arr) {
        $arr['password'] = crypt($arr['password']);
        return Zx_Model::update(self::$table, $id, self::$fields, $arr);
    }

    public static function delete($id) {
        return Zx_Model::delete(self::$table, $id);
    }

}