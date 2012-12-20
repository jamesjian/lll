<?php

namespace App\Module\Front\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Answer as Model_Answer;
use \App\Transaction\Answer as Transaction_Answer;
use \App\Model\Question as Model_Question;

/**
 * homepage: /=>/front/answer/latest/page/1
 * latest: /front/answer/latest/page/3
 * most popular:/front/answer/most_popular/page/3
 * answer under category: /front/answercategory/retrieve/$category_id_3/category_name.php
 * one: /front/answer/content/$id/$answer_url
 * keyword: /front/answer/keyword/$keyword_3
 */
class Answer extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/answer/';
    }

    /*     * one answer
     * /front/answer/content/niba
     * use url rather than id in the query string
     */

    public function content() {
        $answer_url = $this->params[0]; //it's url rather than an id
        $answer = Model_Answer::get_one_by_url($answer_url);
        //\Zx\Test\Test::object_log('$answer', $answer, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($answer) {

            $aid = $answer['id'];
            $home_url = HTML_ROOT;
            $category_url = FRONT_HTML_ROOT . 'answer/category/' . $answer['cat_name'];
            Transaction_Session::set_breadcrumb(0, $home_url, '首页');
            Transaction_Session::set_breadcrumb(1, $category_url, $answer['cat_name']);
            Transaction_Session::set_breadcrumb(2, Route::$url, $answer['title']);
            Transaction_Html::set_title($answer['title']);
            Transaction_Html::set_keyword($answer['keyword'] . ',' . $answer['keyword_en']);
            Transaction_Html::set_description($answer['title'] . ' ' . $answer['title_en']);
            Model_Answer::increase_rank($aid);

            View::set_view_file($this->view_path . 'one_answer.php');
            $relate_answers = Model_Answer::get_10_active_related_answers($aid);
            View::set_action_var('answer', $answer);
            View::set_action_var('related_answers', $relate_answers);
        } else {
            //if no answer, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/answer/keyword/$keyword/page/3, 3 is page number
     */
    public function keyword() {
        $keyword = (isset($this->params[0])) ? $this->params[0] : '';
        if ($keyword == '') {
            //goto homepage
            Transaction_Html::goto_home_page();
        } else {
            $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
            $order_by = 'rank';
            $direction = 'DESC';
            $answers = Model_Answer::get_active_answers_by_keyword_and_page_num($keyword, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$answers', $answers, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_answers = Model_Answer::get_num_of_active_answers_by_keyword($keyword);
            $num_of_pages = ceil($num_of_answers / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_keyword.php');
            View::set_action_var('keyword', $keyword);
            View::set_action_var('answers', $answers);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        }
    }

    /**
      retrieve questions under a user
      front/question/retrieve_by_uid/id/page/3/, 3 is page number
     */
    public function retrieve_by_uid() {
        $uid = (isset($this->params[0])) ? $this->params[0] : 0;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($uid != 0 && $user = Model_User::get_one($uid)) {
            $home_url = HTML_ROOT;
            //$tag_url = FRONT_HTML_ROOT . 'question/tag/' . $tag['id']; 
            Transaction_Session::set_breadcrumb(0, $home_url, '首页');
            Transaction_Session::set_breadcrumb(1, $category_url, $tag['name']);
            //$cat = Model_Questioncategory::get_one($cat_id);
            Transaction_Html::set_title($tag['name']);
            Transaction_Html::set_keyword($tag['name']);
            Transaction_Html::set_description($tag['name']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $questions = Model_Answer::get_active_answers_by_uid_and_page_num($uid, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_questions = Model_Answer::get_num_of_active_answers_by_uid($uid);
            $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'answer_list_by_uid.php');
            View::set_action_var('user', $user);
            View::set_action_var('questions', $questions);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        } else {
            //if invalid category
            // \Zx\Test\Test::object_log('$cat_title', 'no', __FILE__, __LINE__, __CLASS__, __METHOD__);

            Transaction_Html::goto_home_page();
        }
    }

    /**
      answer/latest/3, 3 is page number, if missing, 1 is default page number
     * including home page
     */
    public function latest() {
        //\Zx\Test\Test::object_log('lob', 'aaaa', __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::set_title('latest');
        Transaction_Html::set_keyword('latest');
        Transaction_Html::set_description('latest');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $answers = Model_Answer::get_active_answers_by_page_num($current_page, $order_by, $direction);
        $num_of_answers = Model_Answer::get_num_of_active_answers();
        $num_of_pages = ceil($num_of_answers / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_latest.php');
        View::set_action_var('answers', $answers);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      answer/hottest/3, 3 is page number, if missing, 1 is default page number
     */
    public function hottest() {
        Transaction_Html::set_title('hottest');
        Transaction_Html::set_keyword('hottest');
        Transaction_Html::set_description('hottest');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'rank';
        $direction = 'DESC';
        $answers = Model_Answer::get_active_answers_by_page_num($current_page, $order_by, $direction);
        $num_of_answers = Model_Answer::get_num_of_active_answers();
        $num_of_pages = ceil($num_of_answers / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_hottest.php');
        View::set_action_var('answers', $answers);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
     * must have  title, content and tag
     * this create() is in front module, in the transaction, it will check if a user has logged in, 
     * if yes, status is 1(active), if not, status is 0 (inactive), user is default questiong user, waiting for approval
     */
    public function reply() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit']) && isset($_POST['qid']) &&
                isset($_POST['content']) && !empty($_POST['content']) 
        ) {
            $qid = intval($_POST['qid']);
            if (Model_Question::exist_question($qid)) {
                $content = trim($_POST['content']);

                $arr = array(
                    'content' => $content,
                    'qid'=>$qid,
                );
                if (Transaction_Answer::reply_question($arr)) {
                    $success = true;
                }
            } else {
                 Zx_Message::set_error_message('无效问题');
                 //goto previous valid page
                 Transaction_Html::goto_previous_page();
            }
        } else {
            Zx_Message::set_error_message(' content can not be empty。');
        }
        header('Location: ' . FRONT_HTML_ROOT . 'question/content/' . $qid);
        //always go to question or question list page
        
            /**
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
             * 
        }
             */
    }

}
