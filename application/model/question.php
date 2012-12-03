<?php

namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Base\Question as Base_Question;
use \Zx\Model\Mysql;

class Question extends Base_Question {
    public static function exist_question($id){
         $question = parent::get_one($id);
         if ($question) {
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
     * tag_ids like '1@2@3', tag_names like 'x1@x2@x3'
     * use id to find related questions
     */
    public static function get_10_active_related_questions($question_id) {
        $questions = array();
        $question = parent::get_one($question_id);
        if ($question) {
            $tag_ids = $question['tag_ids'];
            
            $tag_id_arr = array();
            if ($tag_ids <> '') {
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
    public static function get_active_questions_by_page_num($page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' status=1 ';
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_questions($where = '1') {
        $where = ' (status=1' . ')  AND (' . $where . ')';
        return parent::get_num();
    }

    /**
     */
    public static function get_active_questions_by_uid_and_page_num($user_id, $where=1,$page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' status=1 AND user_id=' . $user_id;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_questions_by_uid_and_page_num($user_id, $where = '1', $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' (user_id=' . $user_id . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_questions_by_uid($user_id, $where = '1') {
        $where = ' (user_id=' . $user_id . ')  AND (' . $where . ')';
        return parent::get_num($where);
    }

    public static function get_num_of_active_questions_by_uid($user_id) {
        $where = ' status=1 AND user_id=' . $user_id;
        return parent::get_num($where);
    }
 /**
     */
    public static function get_active_questions_by_tag_and_page_num($tag, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = " status=1 AND tag_names LIKE '%$tag%'" ;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_questions_by_keyword($keyword) {
        $where = " status=1 AND  tag_names LIKE '%$tag%'" ;
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

    public static function increase_rank($question_id) {
        $sql = 'UPDATE ' . self::$table .  ' SET rank=rank+1 WHERE id=:id';
        $params = array(':id' => $question_id);
        return Mysql::exec($sql, $params);
    }

    public static function increase_num_of_answers($question_id) {
        $sql = "UPDATE " . parent::$table . " SET num_of_answers=num_of_answers+1 WHERE id=:id";
        $params = array(':id' => $question_id);
        return Mysql::exec($sql, $params);
    }
    public static function get_latest10() {
        $where = ' status=1';
        return parent::get_all($where, 0, 10, 'date_created', 'DESC');
    }

}