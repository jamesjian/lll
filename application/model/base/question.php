<?php

namespace App\Model\Base;

use \Zx\Model\Mysql as Zx_Mysql;

/*
 * question can be created anonymously, but only be updated by author or admin
 * if anonymously, only be updated by admin 
 *  #AU means australia
  CREATE TABLE ts8wl_question (
  id unsigned mediumint(8) AUTO_INCREMENT PRIMARY KEY,
  id1 varchar(44) not null unique,
  title varchar(255) NOT NULL DEFAULT '',
  region varchar(3) not null default 'AU',
  uid unsigned mediumint(7) not null 0,
  uname varchar(30) not null '',  #user name is fixed
  tids varchar(255) NOT NULL DEFAULT '',
  tnames varchar(255) not null default '', #tag names are fixed
  content text,
  content1 text,
  num_of_answers unsigned smallint(4) default 0,
  num_of_views unsigned int(11) default 0,
  num_of_votes unsigned mediumint(7) default 0,
  status unsigned tinyint(1) not null default 1,  //1: active, 2.
  date_created datetime) engine=innodb default charset=utf8
 alter table  ts8wl_question add index title (title); 
 alter table  ts8wl_question add index region (region); 
 alter table  ts8wl_question add index uid (uid); 
 alter table  ts8wl_question add index num_of_votes (num_of_votes); 
 alter table  ts8wl_question add index num_of_views (num_of_views); 
* 
 *  * todo: answer_history table to record all answers when updated
 */

class Question {

    public static $fields = array('id', 'id1', 'title', 'region', 'uid', 'uname',
        'tids', 'tnames', 'num_of_answers', 'content',
        'content1', 'num_of_views', 'num_of_votes', 'status', 'date_created');
    public static $table = TABLE_QUESTION;

    /*     * for status
     * 1. when created or updated, it's S_ACTIVE, user get score, it can be updated, claimed
     *    only S_ACTIVE can be claimed
     * 2. if  a question has an answer or voted or claimed or correct, it can not be deleted
     *    if claimed, have to wait for admin to check it
     *    if has vote, it's valuable  
     *    if not claimed and no answer (num_of_answers=0) it can be deleted by author(not purge)  -> S_DELETED
     * 3. only S_ACTIVE and S_CORRECT(will change to S_ACTIVE) can be updated by author
     *    when somebody claim it, it's S_CLAIMED, it cannot be updated, deleted  by user
     *    when somebody claim it and it's checked by admin and not wrong, it's S_CORRECT, 
     *     can be updated (status will change to S_ACTIVE), 
     *     but cannot be claimed and deleted
     *    when somebody claim it and it's checked by admin and it's really bad, it's S_DISABLED,  
     *       cannot be claimed,  updated,
     *      can be deleted
     * 4.  S_ACTIVE->S_CLAIMED->S_CORRECT->            (if updated) S_ACTIVE
     *    (created)       (claimed)       completely correct    
     *     S_ACTIVE->S_CLAIMED->S_DISABLED  
     *    (created)       (claimed)       completely wrong   
     * 5. if disabled, it can be deleted by author even if it has answer or vote
     * 6. admin cannot "delete" a question, but can purge a question
     * 7. author only create and delete a question (if can be deleted), with score change
     *    claim only made by logged user
     *    S_DISABLED, S_CORRECT only by admin (with score change)
     *    S_DISABLED can be deleted by author, 
     *    admin can change status between S_DISABLED,S_CORRECT and S_ACTIVE
     * 8. in the front end, only S_DISABLED and S_DELETED will not display, others will display
     * 
     */

    const S_DISABLED = 0; //if this question is disabled by admin
    const S_ACTIVE = 1;  //if this question is active and can be claimed
    const S_CORRECT = 2;   //if this question completely correct, cannot be claimed
    const S_CLAIMED = 3; //when it's claimed by user
    const S_DELETED = 4; //when it's deleted by user

    public static function get_one($id) {
        return Zx_Mysql::get_one(self::$table, $id);
    }

    public static function get_one_by_where($where) {
        return Zx_Mysql::get_one_by_where(self::$table, $where);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        return Zx_Mysql::get_all(self::$table, $where, $offset, $row_count, $order_by, $direction);
    }

    public static function get_num($where = '1') {
        return Zx_Mysql::get_num(self::$table, $where);
    }

    /**
     * generate id1 after record creation
     * @param array $arr
     * @return int
     */
    public static function create($arr) {
        $arr['date_created'] = date('Y-m-d h:i:s');
        $id = Zx_Mysql::create(self::$table, self::$fields, $arr);
        $arr = array('id1' => 2 * $id . md5($id));  //generate id1
        self::update($id, $arr);
        return $id;
    }

    public static function update($id, $arr) {
        return Zx_Mysql::update(self::$table, $id, self::$fields, $arr);
    }

    public static function delete($id) {
        return Zx_Mysql::delete(self::$table, $id);
    }

}