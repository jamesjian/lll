<?php
namespace App\Module\User\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Controller\Route;
use \Zx\View\View as Zx_View;
use App\Transaction\Session as Transaction_Session;
use \Zx\Message\Message;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Score as Model_Score;
use \App\Transaction\Question as Transaction_Question;

/* * 
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */

class Question extends Base {

    public $view_path;
    public $list_page;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/user/view/question/';
        $this->list_page = USER_HTML_ROOT . 'question/user/' . $this->user['id'];
    }

    /**
     * only my scores
     * pagination
     */
    public function user() {
        $uid = $this->uid;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        $home_url = HTML_ROOT;
        //$tag_url = FRONT_HTML_ROOT . 'question/tag/' . $tag['id']; 
        Transaction_Session::set_breadcrumb(0, $home_url, '首页');
        //Transaction_Session::set_breadcrumb(1, $category_url, $tag['name']);
        //$cat = Model_Questioncategory::get_one($cat_id);
        //Transaction_Html::set_title($tag['name']);
        //Transaction_Html::set_keyword($tag['name']);
        //Transaction_Html::set_description($tag['name']);
        $order_by = 'date_created';
        $direction = 'DESC';
        $where ='1';
        $questions = Model_Question::get_records_by_uid_and_page_num($uid, $where, $current_page, $order_by, $direction);
        //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $num_of_questions = Model_Question::get_num_of_records_by_uid($uid);
        $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
        Zx_View::set_view_file($this->view_path . 'my_scores.php');
        Zx_View::set_action_var('questions', $questions);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
     * only owner of the question and no answer for it can be updated
     */
    public function update() {
        $qid = (isset($params[0])) ? intval($params[0]) : 0;
        if ($qid > 0 && Model_Question::question_belong_to_user($qid, $this->uid)) {
            $question = Model_Question::get_one($qid);
            if ($question['num_of_answers'] == 0) {

            } else {
                Message::set_error_message('已有人回答该问题， 只能补充信息');
            }
            Zx_View::set_view_file($this->view_path . 'update.php');
            Zx_View::set_action_var('question', $question);            
        } else {
            Transaction_Html::goto_previous_user_page();
        }
    }
    /**
     * only owner of the question and no answer for it can be updated
     */
    public function delete() {
        $qid = (isset($params[0])) ? intval($params[0]) : 0;
        if ($qid > 0 && Model_Question::question_belong_to_user($qid, $this->uid)) {
            $question = Model_Question::get_one($qid);
            if ($question['num_of_answers'] == 0) {
                Transaction_Question::delete_question($qid);
            } else {
                Message::set_error_message('已有人回答该问题， 不能删除');
            }
        } 
            Transaction_Html::goto_previous_user_page();
        
    }

}