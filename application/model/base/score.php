<?php
namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql as Zx_Model;
/**
 * todo: next step
 * this is a transaction table of score
 * record all transactions (increase and decrease of score)
 * 
 * every question 1
 * every answer 2
 * every vote 1
 * every confirmed claim 
 *   if score of claim is 10, claimant+10, defendant-10
 * num of ads <= num of valid answers (when an answer is disabled,
 *  if the ad is only for this answer, the ad is disabled)
 *   
 * sum of score of ads = score 
 * old_score and new_score are no less than 0, if less than 0, it's set to 0
 * "change " can be negative

 * 
 * item_type: 1: question, 2: answer, 3: ad
 * CREATE TABLE ts8wl_score (
 id  INT(11) unsigned  AUTO_INCREMENT PRIMARY KEY,
 uid MEDIUMINT(8) unsigned, 
 item_type  tinyint(1) unsigned  not null default 1, 
 item_id  MEDIUMINT(8) unsigned  not null default 0, 
 claim_id MEDIUMINT(8) unsigned  not null default 0, 
 type  tinyint(2) unsigned  not null default 1, 
 description varchar(255) not NULL DEFAULT '',  
  old_score MEDIUMINT(8) unsigned default 0,
  difference tinyint(3),
  new_score MEDIUMINT(8) unsigned default 0,
  status  tinyint(1) unsigned not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 * 
 */
class Score {
    public static $fields = array('id','uid','item_id','claim_id','type',  
        'description', 'old_score','difference', 'new_score','status','date_created');
    public static $table = TABLE_SCORE;
    //status
    const S_ACTIVE = 1;  //
    //type of operation
    const T_CREATE_QUESTION = 1;  // increase score
    const T_CREATE_ANSWER = 2;// increase score
    const T_CREATE_AD = 3; //decrease score
    
    const T_CORRECT_CLAIM_QUESTION = 4;// increase score
    const T_CORRECT_CLAIM_ANSWER = 5;// increase score
    const T_CORRECT_CLAIM_AD = 6;    // increase score
    
    const T_DISABLE_QUESTION = 7;//decrease score
    const T_DISABLE_ANSWER = 8;//decrease score
    const T_DISABLE_AD = 9;    //decrease score
    
    const T_DELETE_QUESTION = 10;//decrease score
    const T_DELETE_ANSWER = 11;//decrease score
    
    const T_VOTE_QUESTION = 12;// increase score
    const T_VOTE_ANSWER = 13;// increase score
    
    const T_EXTEND_AD = 14; //decrease score


    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT * FROM " . self::$table .  " WHERE id=:id";
        $params = array(':id' => $id);


        return Zx_Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT *  FROM " . self::$table .  " WHERE $where
        ";
        return Zx_Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        $sql = "SELECT *
            FROM " . self::$table .  " WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(id) AS num FROM " . self::$table . " WHERE $where";

        $result = Zx_Mysql::select_one($sql);
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
        
        $id = Zx_Mysql::insert($sql, $params);
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
        //$query = Zx_Mysql::interpolateQuery($sql, $params);
        //\Zx\Test\Test::object_log('query', $query, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Zx_Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM " . self::$table ." WHERE id=:id";
        $params = array(':id' => $id);
        return Zx_Mysql::exec($sql, $params);
    }

}
