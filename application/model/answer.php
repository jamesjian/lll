<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Answer as Base_Answer;
use \Zx\Model\Mysql;

class Answer extends Base_Answer {
    public static function get_all_keywords()
    {
        $sql = "SELECT keyword, keyword_en FROM answer WHERE status=1";
        $r = Mysql::select_all($sql);
        $arr = array();
        if ($r) {
            foreach ($r as $record) {
                $arr_keyword = explode(',', $record['keyword']);
                foreach ($arr_keyword as $keyword) {
                    if (trim($keyword) != '' && !in_array($keyword, $arr)) {
                        $arr[] = $keyword;
                    }
                }
                $arr_keyword = explode(',', $record['keyword_en']);
                foreach ($arr_keyword as $keyword) {
                    if (trim($keyword) != '' && !in_array($keyword, $arr)) {
                        $arr[] = $keyword;
                    }
                }                
            }
        }
        return $arr;
    }
    /**
     * 
     * @param string $url is a unique column in answer table
     */
    public static function get_one_by_url($url)
    {
        $sql = "SELECT b.*, bc.title as cat_name
            FROM answer b
            LEFT JOIN answer_category bc ON b.cat_id=bc.id
            WHERE b.url='$url'
        ";
        //$params = array(':url'=>$url);
//		$query = Mysql::interpolateQuery($sql, $params);
      //\Zx\Test\Test::object_log('query', $sql, __FILE__, __LINE__, __CLASS__, __METHOD__);        
       return Mysql::select_one($sql);
    }
    /**
     *
     * @param intval $cat_id  category id
     * @return boolean
     */
    public static function exist_answer_under_cat($cat_id)
    {
        $where = 'b.cat_id=' . $cat_id;
        $num = parent::get_num($where);
        if ($num>0) return true;
        else return false;
    }
    /**
      according to category or keyword
      keywords are seperated by '^'
     */
    public static function get_10_active_related_answers($answer_id) {
        $answer = parent::get_one($answer_id);
        if ($answer) {
            $cat_id = $answer['cat_id'];
            $keywords = $answer['keyword'];
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
                $answers = parent::get_all($where, $offset, $row_count, $order_by, $direction);
                return $answers;
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
    public static function get_active_answers_by_keyword($keyword, $page_num=1)
    {
                $where = " b.status=1 AND keyword LIKE '%$keyword%'";
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }
    /**
     * get active cats order by category name
     */
    public static function get_active_answers_by_page_num($page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' b.status=1 ';
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_active_answers($where = '1') {
        $where = ' (b.status=1' . ')  AND (' . $where . ')';
        return parent::get_num();
    }

    /**
     */
    public static function get_active_answers_by_cat_id_and_page_num($cat_id, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' b.status=1 AND b.cat_id=' . $cat_id;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_answers_by_cat_id_and_page_num($cat_id, $where = '1', $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = ' (b.status=1 AND b.cat_id=' . $cat_id . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ARTICLES_IN_CAT_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ARTICLES_IN_CAT_PAGE, $order_by, $direction);
    }

    public static function get_num_of_answers_by_cat_id($cat_id, $where = '1') {
        $where = ' (b.cat_id=' . $cat_id . ')  AND (' . $where . ')';
        return parent::get_num($where);
    }

    public static function get_num_of_active_answers_by_cat_id($cat_id) {
        $where = ' b.status=1 AND b.cat_id=' . $cat_id;
        return parent::get_num($where);
    }
 /**
     */
    public static function get_active_answers_by_user_id_and_page_num($user_id, $where=1, $page_num = 1, $order_by = 'b.display_order', $direction = 'ASC') {
        $where = " a.status=1 AND a.user_id=$user_id AND ($where)" ;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_answers_by_user_id($user_id, $where=1) {
        $where = " a.status=1 AND a.user_id=$user_id AND ($where)" ;
        return parent::get_num($where);
    }    
    public static function get_active_answers_by_question_id_and_page_num($question_id, $where=1, $page_num = 1, $order_by = 'a.date_created', $direction = 'ASC') {
        $where = " a.status=1 AND a.question_id=$question_id AND ($where)" ;
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }
    public static function get_num_of_active_answers_by_question_id($question_id, $where=1) {
        $where = " a.status=1 AND a.question_id=$question_id AND ($where)" ;
        return parent::get_num($where);
    }    
    
    public static function get_answers_by_page_num($where = '1', $page_num = 1, $order_by = 'id', $direction = 'ASC') {
        $page_num = intval($page_num);
        $page_num = ($page_num > 0) ? $page_num : 1;
        $direction = ($direction == 'ASC') ? 'ASC' : 'DESC';
        //$where = '1';
        $start = ($page_num - 1) * NUM_OF_RECORDS_IN_ADMIN_PAGE;
        return parent::get_all($where, $start, NUM_OF_RECORDS_IN_ADMIN_PAGE, $order_by, $direction);
    }

    public static function get_num_of_answers($where = '1') {
        return parent::get_num($where);
    }

    /**
     * get active cats order by category name
     */
    public static function get_all_answers() {
        return parent::get_all();
    }

    /**
     * get active cats order by category name
     */
    public static function get_all_active_answers() {
        $where = 'b.status=1';
        return parent::get_all($where);
    }

    public static function increase_rank($answer_id) {
        $sql = 'UPDATE answer SET rank=rank+1 WHERE id=:id';
        $params = array(':id' => $answer_id);
        return Mysql::exec($sql, $params);
    }

    public static function get_top10() {
        $where = ' b.status=1';
        return parent::get_all($where, 0, 10, 'b.rank', 'DESC');
    }

    public static function get_latest10() {
        $where = ' b.status=1';
        return parent::get_all($where, 0, 10, 'b.date_created', 'DESC');
    }

}