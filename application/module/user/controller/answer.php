<?php

defined('SYSPATH') or die('No direct script access.');

namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use App\Transaction\User as Transaction_User;
use \App\Model\Answer as Model_Answer;

/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class Answer extends Base {
    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/user/view/answer/';
        parent::init();
    }
    /**
     * only my answers
     * pagination
     */
    public function my_answers()
    {       
        $user_id = Transaction_User::get_user_id();
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($user_id != 0 && $user = Model_User::get_one($user_id)) {
            $home_url = HTML_ROOT;
            //$tag_url = FRONT_HTML_ROOT . 'question/tag/' . $tag['id']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $category_url,  $tag['name']);
            //$cat = Model_Questioncategory::get_one($cat_id);
            Transaction_Html::set_title($tag['name']);
            Transaction_Html::set_keyword($tag['name']);
            Transaction_Html::set_description($tag['name']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $questions = Model_Answer::get_active_answers_by_user_id_and_page_num($user_id, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_questions = Model_Answer::get_num_of_active_answers_by_user_id($user_id);
            $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'my_answers.php');
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
    public function answer()
    {
        $success = false;
        $user_id = Transaction_User::get_user_id();
        if (isset($_POST['submit']) && 
                isset($_POST['user_id']) && 
                isset($_POST['question_id']) && 
                isset($_POST['content'])) {
            $question_id = isset($_POST['question_id']) ? trim($_POST['question_id']) : 0;
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $rank = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($user_id >0 && $question_id> 0) {
                $arr = array('user_id' => $user_id,
                    'question_id' => $question_id,
                    'content' => $content,
                    'rank' => $rank,
                    'status' => $status,
                    );
                if (Transaction_Answer::create_answer($arr)) {
                    $success = true;
                }
            }
        } else {
            $question_id = isset($this->params[0]) ? intval($this->params[0]) : 0;
            if (!Model_Question::exist_question_id($question_id)){
                Message::set_error_message('无效问题。');
                header('Location: ' . $this->list_page);
            } else {
                Message::set_error_message('user id,question_id and content not be empty。');
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
        }
        header('Location: ' . Transaction_Session::get_previous_page());
    }
}