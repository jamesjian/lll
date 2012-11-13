<?php

namespace App\Model;

use \App\Model\Base\Question as Base_Question;
use \Zx\Model\Mysql;

class Question extends Base_Question {
    
    /**
     * 
     * @param string $url is a unique column in question table
     */
    public static function get_one_by_url($url)
    {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM question b
            LEFT JOIN question_category bc ON b.cat_id=bc.id
            WHERE b.url='$url'
        ";
        //$params = array(':url'=>$url);
//		$query = Mysql::interpolateQuery($sql, $params);
      //\Zx\Test\Test::object_log('query', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);        
       return Mysql::select_one($sql);
    }

    /**
      according to category or keyword
      keywords are seperated by '^'
     */
    public static function get_10_active_related_questions($question_id) {
        $question = parent::get_one($question_id);
        if ($question) {
            $cat_id = $question['cat_id'];
            $keywords = $question['keyword'];
            $keyword_arr = array();
            if ($keywords <> '') {
                $keyword_arr = explode('^', $keywords);
            }

            $where = "b.status=1 AND (b.cat_id=$cat_id";
            if (count($keyword_arr) > 0) {
                foreach ($keyword_arr as $keyword) {
                    $where .= " OR b.keyword LIKE '%$keyword%'";
                }
                $where .= ')';
                $offset = 0;
                $row_count = 10;
                $order_by = 'b.date_created';
                $direction = 'DESC';
                $questions = parent::get_all($where, $offset, $row_count, $order_by, $direction);
                return $questions;
            } else {
                return false;
            }
        }
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
        $where = ' b.status=1 ';
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_questions($where = '1') {
        $where = ' (b.status=1' . ')  AND (' . $where . ')';
        return parent::get_num();
    }

    /**
     */
    public static function get_active_questions_by_user_id_and_page_num($user_id, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' status=1 AND user_id=' . $user_id;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_questions_by_user_id_and_page_num($user_id, $where = '1', $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' (user_id=' . $user_id . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_questions_by_user_id($user_id, $where = '1') {
        $where = ' (user_id=' . $user_id . ')  AND (' . $where . ')';
        return parent::get_num($where);
    }

    public static function get_num_of_active_questions_by_user_id($user_id) {
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
            case 'cat_id':
                $order_by = 'b.' . $order_by;
                break;
            default:
                $order_by = 'b.date_created';
        }
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        //$where = '1';
        $start = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $start, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }

    public static function get_num_of_questions($where = '1') {
        return parent::get_num($where);
    }

    public static function increase_rank($question_id) {
        $sql = 'UPDATE question SET rank=rank+1 WHERE id=:id';
        $params = array(':id' => $question_id);
        return Mysql::exec($sql, $params);
    }

    public static function get_latest10() {
        $where = ' status=1';
        return parent::get_all($where, 0, 10, 'date_created', 'DESC');
    }

}