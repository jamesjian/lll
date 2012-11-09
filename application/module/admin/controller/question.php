<?php

namespace App\Module\Admin\Controller;

use \App\Model\Question as Model_Question;
use \App\Model\Questioncategory as Model_Questioncategory;
use \App\Transaction\Question as Transaction_Question;
use \Zx\View\View;
use \Zx\Test\Test;

class Question extends Base {

    public $list_page = '';

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/admin/view/question/';
        $this->list_page = ADMIN_HTML_ROOT . 'question/retrieve/1/title/ASC/';
        \App\Transaction\Session::set_ck_upload_path('question');
        parent::init();
    }

    /**
     * for admin to create a question, an answer, question user and answer user in one step
     */
    public function create_question() {
        $success = false;
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $q_content = isset($_POST['q_content']) ? trim($_POST['q_content']) : '';
            $a_content = isset($_POST['a_content']) ? trim($_POST['a_content']) : '';
            $tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';
            $q_user_name = isset($_POST['q_user_name']) ? intval($_POST['q_user_name']) : 0;
            $a_user_name = isset($_POST['a_user_name']) ? intval($_POST['a_user_name']) : 0;
            $rank = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($title <> '') {
                $arr = array('title' => $title,
                    'q_content' => $q_content,
                    'a_content' => $a_content,
                    'tags' => $tags,
                    'q_user_name' => $q_user_name,
                    'a_user_name' => $a_user_name,
                    'rank' => $rank,
                    'status' => $status,
                    );
                if (Transaction_Question::create_question_and_answer($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            $cats = Model_Questioncategory::get_all_cats();
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('cats', $cats);
        }
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
            $keyword_en = isset($_POST['keyword_en']) ? trim($_POST['keyword_en']) : '';
            $abstract = isset($_POST['abstract']) ? trim($_POST['abstract']) : '';
            $url = isset($_POST['url']) ? trim($_POST['url']) : '';
            $cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 1;
            $rank = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($title <> '') {
                $arr = array('title' => $title,
                    'title_en' => $title_en,
                    'content' => $content,
                    'keyword' => $keyword,
                    'keyword_en' => $keyword_en,
                    'abstract' => $abstract,
                    'url' => $url,
                    'rank' => $rank,
                    'status' => $status,
                    'cat_id' => $cat_id);
                if (Transaction_Question::create_question($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            $cats = Model_Questioncategory::get_all_cats();
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('cats', $cats);
        }
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_Question::delete_question($id);
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
                if (Transaction_Question::update_question($id, $arr)) {
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
            $question = Model_Question::get_one($id);

            $cats = Model_Questioncategory::get_cats();
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('question', $question);
            View::set_action_var('cats', $cats);
        }
    }

    public function search() {
        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            $link = $this->list_page . trim($_POST['search']);
        } else {
            $link = $this->list_page;
        }
        header('Location: ' . $link);
    }

    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_admin_current_l1_menu('Question');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " b.title LIKE '%$search%' OR bc.title LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $question_list = Model_Question::get_questions_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Question::get_num_of_questions($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('question_list', $question_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('question_list', $question_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
     * under one category
      retrieve_by_cat_id/cat_id/page/orderby/direction
     */
    public function retrieve_by_cat_id() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_current_l1_menu('Question');
        $cat_id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " b.title LIKE '%$search%' OR bc.title LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $question_list = Model_Question::get_questions_by_cat_id_and_page_num($cat_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Question::get_num_of_questions($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ARTICLES_IN_CAT_PAGE);
        //\Zx\Test\Test::object_log('question_list', $question_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_cat_id.php');
        View::set_action_var('cat_id', $cat_id);
        View::set_action_var('question_list', $question_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
