<?php
namespace App\Module\Front\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View;
use \Zx\Message\Message as Zx_Message;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Question as Transaction_Question;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
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
        $this->list_page =  FRONT_HTML_ROOT . 'question/all/';
    }

    /*     * one question
     * /front/question/content/id/page/6/slug-url  the page is for pages of answers of this question
     * use url rather than id in the query string
     */

    public function content() {
        $question_id = $this->params[0]; //it's an id
        $current_page_num = isset($this->params[2]) ?  $this->params[2] : 1;
        $question = Model_Question::get_one($question_id);
        //\Zx\Test\Test::object_log('$question', $question, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($question) {
            
            $home_url = HTML_ROOT;
            Transaction_Html::remember_current_page();  //after reply this question, return back to this page
            Transaction_Html::set_title($question['title']);
            Transaction_Html::set_keyword($question['title'] . str_replace('#',',', $question['tag_names']));
            Transaction_Html::set_description($question['title']);
            Model_Question::increase_rank($question_id);
            
            View::set_view_file($this->view_path . 'one_question.php');
            $answers = Model_Answer::get_active_answers_by_question_id_and_page_num($question_id, $current_page_num);
            $num_of_answers = Model_Answer::get_num_of_active_answers_by_question_id($question_id);
            //$related_questions = Model_Question::get_10_active_related_questions($question_id);
            $related_questions = array();
            $latest_questions = array();
            View::set_action_var('question', $question);
            View::set_action_var('answers', $answers);
            View::set_action_var('num_of_answers', $num_of_answers);
            View::set_action_var('related_questions', $related_questions);
            View::set_action_var('latest10', $latest_questions);
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
      front/question/retrieve_by_user_id/id/page/3/, 3 is page number
     */
    public function user() {
        $user_id = (isset($this->params[0])) ? $this->params[0] : 0;
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
            $questions = Model_Question::get_active_questions_by_user_id_and_page_num($user_id, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_questions = Model_Question::get_num_of_active_questions_by_user_id($user_id);
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
    /**tag id
      retrieve questions under a user
      front/question/retrieve_by_user_id/id/page/3/, 3 is page number
     */
    public function tag() {
        $tag_id = (isset($this->params[0])) ? $this->params[0] : 0;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($tag_id != 0 && $tag = Model_Tag::get_one($tag_id)) {
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
            $questions = Model_Question::get_active_questions_by_tag_id_and_page_num($tag_id, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_questions = Model_Question::get_num_of_active_questions_by_tat_id($tag_id);
            $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'question_list_by_tag.php');
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
        Transaction_Html::set_title('latest');
        Transaction_Html::set_keyword('latest');
        Transaction_Html::set_description('latest');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $questions = Model_Question::get_active_questions_by_page_num($current_page, $order_by, $direction);
        $num_of_questions = Model_Question::get_num_of_active_questions();
        $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_latest.php');
        View::set_action_var('questions', $questions);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      question/hottest/3, 3 is page number, if missing, 1 is default page number
     */
    public function popular() {
        Transaction_Html::set_title('popular');
        Transaction_Html::set_keyword('popular');
        Transaction_Html::set_description('popular');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'rank';
        $direction = 'DESC';
        $questions = Model_Question::get_active_questions_by_page_num($current_page, $order_by, $direction);
        $num_of_questions = Model_Question::get_num_of_active_questions();
        $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_hottest.php');
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
                isset($_POST['tag_names']) && !empty($_POST['tag_names'])
                ) {
            $title = trim($_POST['title']);
            $region = isset($_POST['region']) ? trim($_POST['region']) : 'AU';
            $tag_names = trim($_POST['tag_names']);
            $content = trim($_POST['content']);

                $arr = array('title' => $title,
                    'tag_names' => $tag_names,
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
