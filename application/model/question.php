<?php

namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Base\Question as Base_Question;
use \Zx\Model\Mysql;

class Question extends Base_Question {
    /**
     * make sure id1 is valid
     * @param string $id1  
     * @return record
     */
    public static function get_one_by_id1($id1)
    {
       
                $sql = "SELECT *  FROM " . parent::$table . " WHERE id1=:id1";
        $params = array(':id1' => $id1);
        return Mysql::select_one($sql, $params);
    }    
    /**
     * only one page
     * @param int $uid
     * @return array
     */
    public static function get_recent_questions_by_uid($uid) {
        $where = " status=1 AND uid=$uid";
        $offset = 0; 
        $order_by = 'date_created';
        $direction = 'DESC';
        return parent::get_all($where, $offset, NUM_OF_RECENT_QUESTIONS_IN_FRONT_PAGE, $order_by, $direction);
    }    
    public static function exist_question($id){
         $question = parent::get_one($id);
         if ($question) {
             return true;
         } else {
             return false;
         }
         
    }
    /**
     * if has answer, can not be deleted
     * @param int $id
     * @return boolean
     */
    public static function can_be_deleted($id){
         $question = parent::get_one($id);
\Zx\Test\Test::object_log('$question', $question, __FILE__, __LINE__, __CLASS__, __METHOD__);         
         if ($question && $question['num_of_answers']==0) {
             return true;
         } else {
             return false;
         }
         
    }
    /**
     * 
     * @param string $url is a unique column in question table
     */
    public static function get_one_by_url($url)
    {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM " . self::$table .  " b LEFT JOIN question_category bc ON b.cat_id=bc.id
            WHERE b.url='$url'
        ";
        //$params = array(':url'=>$url);
//		$query = Mysql::interpolateQuery($sql, $params);
      //\Zx\Test\Test::object_log('query', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);        
       return Mysql::select_one($sql);
    }

    /**
      according to category or keyword
      keywords are seperated by '@'
     * 
     * tids like '1@2@3', tnames like 'x1@x2@x3'
     * use id to find related questions
     */
    public static function get_10_active_related_questions($qid) {
        $questions = array();
        $question = parent::get_one($qid);
        if ($question) {
            $tids = $question['tids'];
            
            $tag_id_arr = array();
            if ($tids <> '') {
                $tag_id_arr = explode('@', $keywords);
            }
            if (count($tag_id_arr) > 0) {
                foreach ($tag_id_arr as $tag_id) {
                    $where .= " OR b.keyword LIKE '%$tag_id@%'";
                }
                $where .= ')';
                $offset = 0;
                $row_count = 10;
                $order_by = 'date_created';
                $direction = 'DESC';
                $questions = parent::get_all($where, $offset, $row_count, $order_by, $direction);
                return $questions;
            } else {
                return false;
            }
        }
        return $questions;
    }
    /**
     * 
     * @param string $keyword
     * @param intval $page_num
     * @return array
     */
    public static function get_active_questions_by_keyword($keyword, $page_num=1)
    {
                $where = " b.status=1 AND keyword LIKE '%$keyword%'";
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }
    /**
     * get active cats order by category name
     */
    public static function get_active_popular_questions_by_page_num($page_num = 1, $order_by = '', $direction = 'ASC') {
        $where = ' status=1';
        $offset = ($page_num - 1) * NUM_OF_QUESTIONS_IN_FRONT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_QUESTIONS_IN_FRONT_PAGE, $order_by, $direction);
    }


    /**
     * get active cats order by category name
     */
    public static function get_active_answered_questions_by_page_num($page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' status=1  AND num_of_answers>0 ';
        $offset = ($page_num - 1) * NUM_OF_QUESTIONS_IN_FRONT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_QUESTIONS_IN_FRONT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_answered_questions() {
        $where = ' status=1 AND num_of_answers>0 ';
        return parent::get_num();
    }
    /**
     * get active cats order by category name
     */
    public static function get_active_unanswered_questions_by_page_num($page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' status=1  AND num_of_answers=0 ';
        $offset = ($page_num - 1) * NUM_OF_QUESTIONS_IN_FRONT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_QUESTIONS_IN_FRONT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_unanswered_questions() {
        $where = ' status=1 AND num_of_answers=0 ';
        return parent::get_num();
    }
    /**
     * get active cats order by category name
     */
    public static function get_active_questions_by_page_num($page_num = 1, $order_by = 'date_created', $direction = 'ASC') {
        $where = ' status=1 ';
        $offset = ($page_num - 1) * NUM_OF_QUESTIONS_IN_FRONT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_QUESTIONS_IN_FRONT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_questions($where = '1') {
        $where = ' (status=1' . ')  AND (' . $where . ')';
        return parent::get_num();
    }

    /**
     */
    public static function get_active_questions_by_uid_and_page_num($uid, $where=1,$page_num = 1, $order_by = 'date_created', $direction = 'ASC') {
        $where = ' status=1 AND uid=' . $uid;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_questions_by_uid_and_page_num($uid, $where = '1', $page_num = 1, $order_by = 'date_created', $direction = 'ASC') {
        $where = ' (uid=' . $uid . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_questions_by_uid($uid, $where = '1') {
        $where = ' (uid=' . $uid . ')  AND (' . $where . ')';
        return parent::get_num($where);
    }

    public static function get_num_of_active_questions_by_uid($uid) {
        $where = ' status=1 AND uid=' . $uid;
        return parent::get_num($where);
    }
 /**
  * @param string $tag_name 
     */
    public static function get_active_questions_by_tag_name_and_page_num($tag_name, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = " status=1 AND tnames LIKE '%". TNAME_SEPERATOR . $tag_name. TNAME_SEPERATOR . "%'" ;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
 /**
  * @param string $tag_id
     */    
    public static function get_active_questions_by_tag_id_and_page_num($tag_id, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = " status=1 AND tids LIKE '%". TNAME_SEPERATOR . $tag_id. TNAME_SEPERATOR . "%'" ;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_questions_by_tag_name($tag_name) {
        $where = " status=1 AND  tnames LIKE '%". TNAME_SEPERATOR . $tag_name. TNAME_SEPERATOR . "%'" ;
        return parent::get_num($where);
    }    
    public static function get_num_of_active_questions_by_tag_id($tag_id) {
        $where = " status=1 AND  tids LIKE '%". TNAME_SEPERATOR . $tag_id. TNAME_SEPERATOR . "%'" ;
        return parent::get_num($where);
    }    
    public static function get_num_of_active_questions_by_keyword($keyword) {
        $where = " status=1 AND  tnames LIKE '%$tag%'" ;
        return parent::get_num($where);
    }    
    
    public static function get_questions_by_page_num($where = '1', $page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        switch ($order_by) {
            case 'id':
            case 'title':
            case 'rank':
            case 'display_order':
            case 'date_created':
            
                $order_by = '' . $order_by;
                break;
            default:
                $order_by = 'date_created';
        }
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        //$where = '1';
        $start = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $start, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_questions($where = '1') {
        return parent::get_num($where);
    }

    public static function increase_num_of_views($qid) {
        $sql = 'UPDATE ' . parent::$table .  ' SET num_of_views=num_of_views+1 WHERE id=:id';
        $params = array(':id' => $qid);
        return Mysql::exec($sql, $params);
    }
    public static function decrease_num_of_answers($qid) {
        $sql = 'UPDATE ' . parent::$table .  ' SET num_of_answers=num_of_answers-1 WHERE id=:id';
        \Zx\Test\Test::object_log('$sql', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);
        \Zx\Test\Test::object_log('$qid', $qid, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $params = array(':id' => $qid);
        return Mysql::exec($sql, $params);
    }

    public static function increase_num_of_answers($qid) {
        $sql = "UPDATE " . parent::$table . " SET num_of_answers=num_of_answers+1 WHERE id=:id";
        $params = array(':id' => $qid);
        return Mysql::exec($sql, $params);
    }
    public static function get_latest10() {
        $where = ' status=1';
        return parent::get_all($where, 0, 10, 'date_created', 'DESC');
    }

}