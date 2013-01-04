<?php

namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql;
use App\Model\Question as Model_Question;
use App\Model\Ad as Model_Ad;

/*
  CREATE TABLE answer (
  id unsigned MEDIUMINT(8) AUTO_INCREMENT PRIMARY KEY,
  id1 varchar(44) not null unique,
  qid unsigned MEDIUMINT(8) not null default 0,
  uid unsigned MEDIUMINT(7) not null default 0,
  uname varchar(30) not null '',  #user name is fixed
  ad_id unsigned MEDIUMINT(8) not null default 0,
  content text,
  num_of_votes unsigned mediumint(7) default 0,
  status unsigned tinyint(1) not null default 1,
  date_created datetime) engine=innodb default charset=utf8
 * 
 * todo: answer_history table to record all answers when updated
 */

class Answer {

    public static $fields = array('id', 'id1', 'qid', 'uid', 'uname', 'ad_id',
        'content', 'num_of_votes',  'status', 'date_created');
    public static $table = TABLE_ANSWER;
    /**for status
     * when created or updated, it's STATUS_ACTIVE
     * when somebody claim it, it's STATUS_CLAIMED
     * when somebody claim it and it's checked by admin and not wrong, it's STATUS_CORRECT
     * when somebody claim it and it's checked by admin and it's really bad, it's STATUS_DISABLED
     * even if it's STATUS_CORRECT, but when it's updated, it's STATUS_ACTIVE
     * STATUS_ACTIVE->STATUS_CLAIMED->STATUS_CORRECT     ->  STATUS_ACTIVE
     * (created)       (claimed)       completely correct    updated
     * STATUS_ACTIVE->STATUS_CLAIMED->STATUS_DISABLED  
     * (created)       (claimed)       completely correct    updated
     * 
     * STATUS_DISABLED cannot be changed by front user, but can be changed by admin when mistake happened
     * in the front end, only STATUS_DISABLED will not display, others will display
     * 
    */
    const STATUS_DISABLED=0; //if this answer is disabled by admin
    const STATUS_CORRECT=1;   //if this answer completely correct
    const STATUS_ACTIVE=2;  //if this answer is active and can be claimed
    const STATUS_CLAIMED=3; //when it's claimed by user
    /**
     *
     * @param int $id
     * @return 1D array or boolean when false 
     */
    public static function get_one($id) {
        $sql = "SELECT a.*, q.title, q.tnames, 
            ad.status as ad_status, ad.title as ad_title, ad.content as ad_content, ad.date_end as ad_date_end
            FROM  " . self::$table . " a
            LEFT JOIN " . Model_Question::$table . " q ON q.id=a.qid
            LEFT JOIN " . Model_Ad::$table . "  ad ON a.ad_id=ad.id                
            WHERE a.id=:id
        ";
        $params = array(':id' => $id);


        return Mysql::select_one($sql, $params);
    }

    /**
     *
     * @param string $where
     * @return 1D array or boolean when false 
     */
    public static function get_one_by_where($where) {
        $sql = "SELECT a.*, q.title, q.tnames, 
            ad.status as ad_status, ad.title as ad_title, ad.content as ad_content, ad.date_end as ad_date_end
            FROM  " . self::$table . " a
            LEFT JOIN " . Model_Question::$table . " q ON q.id=a.qid
            LEFT JOIN " . Model_Ad::$table . "  ad ON a.ad_id=ad.id                
            WHERE $where
        ";
        return Mysql::select_one($sql);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        $sql = "SELECT a.*, q.id1 as qid1,  q.title, q.tnames, 
            ad.status as ad_status, ad.title as ad_title, ad.content as ad_content, ad.date_end as ad_date_end
            FROM " . self::$table . "  a
            LEFT JOIN " . Model_Question::$table . "  q ON q.id=a.qid
            LEFT JOIN " . Model_Ad::$table . "  ad ON a.ad_id=ad.id
            WHERE $where
            ORDER BY $order_by $direction
            LIMIT $offset, $row_count
        ";
\Zx\Test\Test::object_log('sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::select_all($sql);
    }

    public static function get_num($where = '1') {
        $sql = "SELECT COUNT(a.id) AS num FROM " . self::$table . " a
            LEFT JOIN " . Model_Question::$table . "  q ON q.id=a.qid
            LEFT JOIN " . Model_Ad::$table . "  ad ON a.ad_id=ad.id
            WHERE $where";
        $result = Mysql::select_one($sql);
        if ($result) {
            return $result['num'];
        } else {
            return false;
        }
    }

    public static function create($arr) {
        //\Zx\Test\Test::object_log('$arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $arr['date_created'] = date('Y-m-d h:i:s');
        $insert_arr = array();
        $params = array();
        foreach (self::$fields as $field) {
            if (array_key_exists($field, $arr)) {
                $insert_arr[] = "$field=:$field";
                $params[":$field"] = $arr[$field];
            }
        }
        $insert_str = implode(',', $insert_arr);
        $sql = 'INSERT INTO ' . self::$table . ' SET ' . $insert_str;
        $id = Mysql::insert($sql, $params);
        $arr = array('id1' => 2 * $id . md5($id));  //generate id1
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
        //\Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $params[':id'] = $id;
        //$query = Mysql::interpolateQuery($sql, $params);
        //\Zx\Test\Test::object_log('query', $query, __FILE__, __LINE__, __CLASS__, __METHOD__);

        return Mysql::exec($sql, $params);
    }

    public static function delete($id) {
        $sql = "Delete FROM " . self::$table . " WHERE id=:id";
        $params = array(':id' => $id);
        return Mysql::exec($sql, $params);
    }

}