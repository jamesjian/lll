<?php
namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Question as Model_Question;


/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class Question extends User {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/user/view/question/';
        parent::init();
    }
/**
     * only my questions
     * pagination
     */
    public function my_questions()
    {       
        $user_id = $this->user_id;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
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
            View::set_view_file($this->view_path . 'my_questions.php');
            View::set_action_var('user', $this->user);
            View::set_action_var('questions', $questions);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
    }
}