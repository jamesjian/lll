<?php

namespace App\Module\Front\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View;
use \Zx\Message\Message as Zx_Message;
use App\Transaction\Tool as Transaction_Tool;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Question as Transaction_Question;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;
use \App\Model\Tag as Model_Tag;

/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class Question extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/question/';
        $this->list_page = FRONT_HTML_ROOT . 'question/latest/1';
    }

    /*     * one question
     * /front/question/content/id/page/6/slug-url  the page is for pages of answers of this question
     * use url rather than id in the query string
     */

    public function content() {
        $qid = $this->params[0]; //it's an id
        $current_page_num = isset($this->params[2]) ? $this->params[2] : 1;
        $question = Model_Question::get_one($qid);
        //\Zx\Test\Test::object_log('$question', $question, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($question) {

            $home_url = HTML_ROOT;
            Transaction_Html::remember_current_page();  //after reply this question, return back to this page
            Transaction_Html::set_title($question['title']);
            Transaction_Html::set_keyword($question['title'] . str_replace('#', ',', $question['tnames']));
            Transaction_Html::set_description($question['title']);
            Model_Question::increase_rank($qid);

            View::set_view_file($this->view_path . 'one_question.php');
            $answers = Model_Answer::get_active_answers_by_qid_and_page_num($qid, $current_page_num);
            $num_of_answers = Model_Answer::get_num_of_active_answers_by_qid($qid);
            //$related_questions = Model_Question::get_10_active_related_questions($qid);
            $n = Model_Answer::get_num_of_inactive_ads($answers);
            $selected_ads = Model_Ad::get_selected_ads($n);
            $related_questions = array();
            $latest_questions = array();
            View::set_action_var('question', $question);
            View::set_action_var('answers', $answers);
            View::set_action_var('num_of_answers', $num_of_answers);
            View::set_action_var('related_questions', $related_questions);
            View::set_action_var('latest10', $latest_questions);
            View::set_action_var('selected_ads', $selected_ads);
        } else {
            //if no question, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/question/3, 3 is page number
     */
    public function all() {
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;  //default page 1
        $order_by = 'date_created';
        $direction = 'DESC';
        $questions = Model_Question::get_active_questions_by_page_num($current_page, $order_by, $direction);
        //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $num_of_questions = Model_Question::get_num_of_active_questions();
        $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'question_list.php');
        View::set_action_var('questions', $questions);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
     * user id
      retrieve questions under a user
      front/question/retrieve_by_uid/id/page/3/, 3 is page number
     */
    public function user() {
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
            $questions = Model_Question::get_active_questions_by_uid_and_page_num($uid, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_questions = Model_Question::get_num_of_active_questions_by_uid($uid);
            $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'question_list_by_user.php');
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

    /*     * tag id
     * question/tag/tagid/latest/pageid
     * question/tag/tagid/answered/pageid
     * question/tag/tagid/unanswered/pageid
     * question/tag/tagid/popular/pageid
     */

    public function tag() {
        $tag_id = (isset($this->params[0])) ? $this->params[0] : 0;
        if ($tag_id != 0 && $tag = Model_Tag::get_one($tag_id)) {
            $way = (isset($this->params[1])) ? $this->params[1] : 'latest';
            $current_page = (isset($this->params[2])) ? $this->params[2] : 1;
            switch ($way) {
                case 'answered':
                    $order_by = 'date_created';
                    $where = 'status=1 AND num_of_answers>0';
                    break;
                case 'unanswered':
                    $order_by = 'date_created';
                    $where = 'status=1 AND num_of_answers=0';
                    break;
                case 'popular':
                    $order_by = 'num_of_votes';
                    $where = 'status=1';
                    break;
                case 'latest':
                default:
                    $order_by = 'date_created';
                    $where = 'status=1';
                    break;
            }
            //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $home_url = HTML_ROOT;
            //$tag_url = FRONT_HTML_ROOT . 'question/tag/' . $tag['id']; 
            Transaction_Session::set_breadcrumb(0, $home_url, '首页');
            Transaction_Session::set_breadcrumb(1, $category_url, $tag['name']);
            //$cat = Model_Questioncategory::get_one($cat_id);
            Transaction_Html::set_title($tag['name']);
            Transaction_Html::set_keyword($tag['name']);
            Transaction_Html::set_description($tag['name']);
            $direction = 'DESC';
            $questions = Model_Question::get_active_questions_by_tag_id_and_page_num($tag_id, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_questions = Model_Question::get_num_of_active_questions_by_tat_id($tag_id);
            $num_of_pages = ceil($num_of_questions / NUM_OF_QUESTIONS_IN_FRONT_PAGE);
            View::set_view_file($this->view_path . 'tag_list.php');
            View::set_action_var('tag', $tag);
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
      question/latest/3, 3 is page number, if missing, 1 is default page number
     * including home page
     */
    public function latest() {
        //\Zx\Test\Test::object_log('lob', 'aaaa', __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::set_title('最新问题');
        Transaction_Html::set_keyword('最新问题');
        Transaction_Html::set_description('最新问题');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $questions = Model_Question::get_active_questions_by_page_num($current_page, $order_by, $direction);
        $num_of_questions = Model_Question::get_num_of_active_questions();
        $num_of_pages = ceil($num_of_questions / NUM_OF_QUESTIONS_IN_FRONT_PAGE);
        View::set_view_file($this->view_path . 'latest_list.php');
        View::set_action_var('questions', $questions);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      question/answered/3, 3 is page number, if missing, 1 is default page number
     * including home page
     */
    public function answered() {
        //\Zx\Test\Test::object_log('lob', 'aaaa', __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::set_title('已回答问题');
        Transaction_Html::set_keyword('已回答问题');
        Transaction_Html::set_description('已回答问题');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $questions = Model_Question::get_active_answered_questions_by_page_num($current_page, $order_by, $direction);
        $num_of_questions = Model_Question::get_num_of_active_answered_questions();
        $num_of_pages = ceil($num_of_questions / NUM_OF_QUESTIONS_IN_FRONT_PAGE);
        View::set_view_file($this->view_path . 'answered_list.php');
        View::set_action_var('questions', $questions);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      question/unanswered/3, 3 is page number, if missing, 1 is default page number
     * including home page
     */
    public function unanswered() {
        //\Zx\Test\Test::object_log('lob', 'aaaa', __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::set_title('未回答问题');
        Transaction_Html::set_keyword('未回答问题');
        Transaction_Html::set_description('未回答问题');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $questions = Model_Question::get_active_unanswered_questions_by_page_num($current_page, $order_by, $direction);
        $num_of_questions = Model_Question::get_num_of_active_unanswered_questions();
        $num_of_pages = ceil($num_of_questions / NUM_OF_QUESTIONS_IN_FRONT_PAGE);
        View::set_view_file($this->view_path . 'unanswered_list.php');
        View::set_action_var('questions', $questions);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      question/hottest/3, 3 is page number, if missing, 1 is default page number
     */
    public function popular() {
        Transaction_Html::set_title('最受关注');
        Transaction_Html::set_keyword('最受关注');
        Transaction_Html::set_description('最受关注');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'num_of_votes';
        $direction = 'DESC';
        $questions = Model_Question::get_active_questions_by_page_num($current_page, $order_by, $direction);
        $num_of_questions = Model_Question::get_num_of_active_questions();
        $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'popular_list.php');
        View::set_action_var('questions', $questions);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
     * must have  title, content and tag
     * this create() is in front module, in the transaction, it will check if a user has logged in, 
     * if yes, status is 1(active), if not, status is 0 (inactive), user is default questiong user, waiting for approval
     */
    public function create() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit']) &&
                isset($_POST['title']) && !empty($_POST['title']) &&
                isset($_POST['content']) && !empty($_POST['content']) &&
                (!empty($_POST['tname1']) || !empty($_POST['tname2']) || 
                 !empty($_POST['tname3']) || !empty($_POST['tname4']) || 
                 !empty($_POST['tname5']))) {
            $title = trim($_POST['title']);
            $region = isset($_POST['region']) ? trim($_POST['region']) : 'AU';
            $tnames = array();
            for ($i=1; $i<=NUM_OF_TNAMES_PER_ITEM; $i++) {
                $index = 'tname' . $i;
                if (isset($_POST[$index])) {
                    $tag = Transaction_Tool::get_clear_string($_POST[$index]);
                    if ( $tag<> '') {
                        //only contain valid tag
                        $tnames[] = $tag;
                    }
                }
            }
            $content = trim($_POST['content']);

            $arr = array('title' => $title,
                'tnames' => $tnames,
                'content' => $content,
                'region' => $region,
            );
            if (Transaction_Question::create($arr)) {
                $success = true;
            }
        } else {
            Zx_Message::set_error_message('title, content, tag can not be empty。');
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        }
    }

}
