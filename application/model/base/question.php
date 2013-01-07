<?php

namespace App\Model\Base;

use \Zx\Model\Mysql;

/*
 * question can be created anonymously, but only be updated by author
 * if anonymously, only be updated by admin 
 *  #AU means australia
  CREATE TABLE question (
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
 *  * todo: answer_history table to record all answers when updated
 */

class Question {
    public static $fields = array('id','id1','title','region', 'uid','uname',
        'tids','tnames','num_of_answers','content',
        'content1', 'num_of_views','num_of_votes', 'status', 'date_created');
    public static $table = TABLE_QUESTION;
    /**for status
     * 1. when created or updated, it's S_ACTIVE, user get score, it can be updated, claimed
     * 2. if  a question has an answer or voted or claimed, it can not be deleted
     *    if claimed, have to wait for admin to check it
     *    if has vote, it's valuable  
     *    if not claimed and no answer (num_of_answers=0) it can be deleted(not purge)  -> S_DELETED
     * 3. only S_ACTIVE and S_CORRECT(will change to S_ACTIVE) can be updated by user
     *    when somebody claim it, it's S_CLAIMED, it cannot be updated, deleted  by user
     *    when somebody claim it and it's checked by admin and not wrong, it's S_CORRECT, 
     *     can be updated (status will change to S_ACTIVE), 
     *     but cannot be claimed and deleted
     *    when somebody claim it and it's checked by admin and it's really bad, it's S_DISABLED,  
     *       cannot be claimed,  updated and deleted
     * 4.  S_ACTIVE->S_CLAIMED->S_CORRECT->            (if updated) S_ACTIVE
     *    (created)       (claimed)       completely correct    
     *     S_ACTIVE->S_CLAIMED->S_DISABLED  
     *    (created)       (claimed)       completely wrong   
     * 5. only purged by admin
     * 
     * S_DISABLED can only be changed to S_DELETED by front user, but can be changed to other status by admin when mistake happened
     * in the front end, only S_DISABLED and S_DELETED will not display, others will display
     * 
    */
    const S_DISABLED=0; //if this question is disabled by admin
    const S_CORRECT=1;   //if this question completely correct, cannot be claimed
    const S_ACTIVE=2;  //if this question is active and can be claimed
    const S_CLAIMED=3; //when it's claimed by user
    const S_DELETED=4; //when it's deleted by user
    
    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT * FROM " . self::$table .  " WHERE id=:id";
        $params = array(':id' => $id);


        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT *  FROM " . self::$table .  " WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        $sql = "SELECT *
            FROM " . self::$table .  " WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

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

    public static function create($arr) {
        $insert_arr = array(); $params = array();
        $arr['date_created'] = date('Y-m-d h:i:s');
                //\Zx\Test\Test::object_log('$arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $insert_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $insert_str = implode(',', $insert_arr);
        $sql = 'INSERT INTO ' . self::$table . ' SET ' . $insert_str;
        //\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        $id = Mysql::insert($sql, $params);
        $arr = array('id1'=>2*$id . md5($id));  //generate id1
        self::update($id, $arr);
        return $id;
    }

    public static function update($id, $arr) {
        $update_arr = array();$params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $update_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }        
        $update_str = implode(',', $update_arr);
        $sql = 'UPDATE ' .self::$table . ' SET '. $update_str . ' WHERE id=:id';
        //\Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $params[':id'] = $id;
        //$query = Mysql::interpolateQuery($sql, $params);
        //\Zx\Test\Test::object_log('query', $query, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM " . self::$table ." WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}