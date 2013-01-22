<?php

namespace App\Module\User\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\View\View as Zx_View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use App\Transaction\User as Transaction_User;
use App\Transaction\Articlereply as Transaction_Articlereply;
use \App\Model\Article as Model_Article;

/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class Articlereply extends Base {

    public $view_path;
    public $list_page;

    public function init() {
        parent::init();
        $this->view_path = USER_VIEW_PATH . 'articlereply/';
        $this->list_page = USER_HTML_ROOT . 'articlereply/user/' . $this->user['id'];
    }

    /**
     * only my answers
     * pagination
     * 
     */
    public function user() {
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
        $order_by = 'a.date_created';
        $direction = 'DESC';
        $where = '1';
        $answers = Model_Answer::get_active_answers_by_uid_and_page_num($this->uid, $where, $current_page, $order_by, $direction);
        //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $num_of_questions = Model_Answer::get_num_of_active_answers_by_uid($this->uid, $where);
        $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'my_answers.php');
        View::set_action_var('answers', $answers);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    public function reply() {
        $success = false;
        $uid = $this->uid;
        if (isset($_POST['submit']) &&
                isset($_POST['uid']) &&
                isset($_POST['article_id']) &&
                isset($_POST['content'])) {
            $article_id = isset($_POST['article_id']) ? trim($_POST['article_id']) : 0;
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';

            if ($uid > 0 && $qid > 0) {
                $arr = array('uid' => $uid,
                    'article_id' => $article_id,
                    'content' => $content,
                );
                if (Transaction_Articlereply::create($arr)) {
                    $success = true;
                }
            }
        } else {
            $qid = isset($this->params[0]) ? intval($this->params[0]) : 0;
            if (!Model_Question::exist_qid($qid)) {
                Zx_Message::set_error_message('无效页面。');
                header('Location: ' . $this->list_page);
            } else {
                Zx_Message::set_error_message('提交信息不完整。');
            }
        }
        if ($success) {
            header('Location: ' . FRONT_HTML_ROOT . 'article/content/' . $article_id);
        } else {
            Zx_View::set_view_file($this->view_path . 'create.php');
        }
        header('Location: ' . FRONT_HTML_ROOT . 'article/content/' . $article_id);
    }

   


}