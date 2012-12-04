<?php

namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Message\Message;
use \App\Model\User as Model_User;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Transaction\Answer as Transaction_Answer;
use \Zx\View\View;
use \Zx\Test\Test;

class Answer extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/admin/view/answer/';
        $this->list_page = ADMIN_HTML_ROOT . 'answer/retrieve/1/title/ASC/';
        \App\Transaction\Session::set_ck_upload_path('answer');
    }

   
    /**
     * must enter user id, question_id and content 
     */
    public function create() {
        $success = false;
        if (isset($_POST['submit']) && 
                isset($_POST['user_id']) && 
                isset($_POST['question_id']) && 
                isset($_POST['content'])) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
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
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_Answer::delete_answer($id);
        header('Location: ' . $this->list_page);
    }

    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            \Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['title']))
                    $arr['title'] = trim($_POST['title']);
                if (isset($_POST['title_en']))
                    $arr['title_en'] = trim($_POST['title_en']);
                if (isset($_POST['content']))
                    $arr['content'] = trim($_POST['content']);
                if (isset($_POST['keyword']))
                    $arr['keyword'] = trim($_POST['keyword']);
                if (isset($_POST['keyword_en']))
                    $arr['keyword_en'] = trim($_POST['keyword_en']);
                if (isset($_POST['abstract']))
                    $arr['abstract'] = trim($_POST['abstract']);
                if (isset($_POST['url']))
                    $arr['url'] = trim($_POST['url']);
                if (isset($_POST['cat_id']))
                    $arr['cat_id'] = intval($_POST['cat_id']);
                if (isset($_POST['rank']))
                    $arr['rank'] = intval($_POST['rank']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Answer::update_answer($id, $arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            if (!isset($id)) {
                $id = $this->params[0];
            }
            $answer = Model_Answer::get_one($id);

            $cats = Model_Answercategory::get_cats();
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('answer', $answer);
            View::set_action_var('cats', $cats);
        }
    }


    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
       if (!\App\Transaction\Html::previous_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Answer');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " title LIKE '%$search%' OR tagnames LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $answer_list = Model_Answer::get_answers_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Answer::get_num_of_answers($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('answer_list', $answer_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('answer_list', $answer_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    
    /**
     * answered by one user
      retrieve_by_user_id/user_id/page/orderby/direction
     * 
     * content is content of answer, title is title of question
     */
    public function retrieve_by_user_id() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_current_l1_menu('Answer');
        $user_id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " content LIKE '%$search%' OR title LIKE '%$search%' OR tag_names LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $answer_list = Model_Answer::get_answers_by_user_id_and_page_num($user_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Answer::get_num_of_answers_by_user_id($user_id, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('answer_list', $answer_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_user_id.php');
        View::set_action_var('user_id', $user_id);
        View::set_action_var('answer_list', $answer_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    /**
     * under one question
      retrieve_by_question_id/question_id/page/orderby/direction
      * content is content of answer, title is title of question
     */
    public function retrieve_by_question_id() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_current_l1_menu('Answer');
        $question_id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " content LIKE '%$search%' OR title LIKE '%$search%' OR tag_names LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $answer_list = Model_Answer::get_answers_by_question_id_and_page_num($question_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Answer::get_num_of_answers_by_question_id($question_id,$where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('answer_list', $answer_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_question_id.php');
        View::set_action_var('question_id', $question_id);
        View::set_action_var('answer_list', $answer_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
