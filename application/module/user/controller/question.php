<?php

namespace App\Module\User\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use \Zx\Message\Message as Zx_Message;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Question as Model_Question;
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
     * only my questions
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
        $where = '1';
        $questions = Model_Question::get_active_questions_by_uid_and_page_num($uid, $where, $current_page, $order_by, $direction);
        //\Zx\Test\Test::object_log('$questions', $questions, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $num_of_questions = Model_Question::get_num_of_active_questions_by_uid($uid);
        $num_of_pages = ceil($num_of_questions / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'my_questions.php');
        View::set_action_var('questions', $questions);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
     * only owner of the question and S_ACTIVE/S_CORRECT can be updated
     */
    public function update() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit'])) {
            $qid = (isset($_POST['qid'])) ? intval($_POST['qid']) : 0;
            if ($qid > 0) {
                $question = Model_Question::get_one($qid);
                if ($question && $question['uid'] == $this->uid) {
                    //must be the owner of the question and correct status
                    if (isset($_POST['title']) && !empty($_POST['title']) &&
                            isset($_POST['content']) && !empty($_POST['content']) &&
                            (!empty($_POST['tname1']) || !empty($_POST['tname2']) ||
                            !empty($_POST['tname3']) || !empty($_POST['tname4']) ||
                            !empty($_POST['tname5']))) {
                        if (isset($_POST['title'])) { $arr['title'] = trim($_POST['title']);}                        
                        if (isset($_POST['region'])) { $arr['region'] = trim($_POST['region']);}                        
                        if (isset($_POST['content'])) { $arr['content'] = trim($_POST['content']);}                        
                        $tnames = array();
                        for ($i = 1; $i <= NUM_OF_TNAMES_PER_ITEM; $i++) {
                            $index = 'tname' . $i;
                            if (isset($_POST[$index])) {
                                $tag = Transaction_Tool::get_clear_string($_POST[$index]);
                                if ($tag <> '') {
                                    //only contain valid tag
                                    $tnames[] = $tag;
                                }
                            }
                        }
                        if (count($tnames)>0) {
                            $arr['tnames'] = $tnames;
                        }
                        Transaction_Question::update($qid, $arr);
                        $success = true;
                    } else {
                        Zx_Message::set_error_message('标题， 内容和关键词请填写完整。');
                    }
                } else {
                    Zx_Message::set_error_message('该问题目前不允许更新， 请登录您的账户查看原因');
                    $success = true; //for this submission, it's successful.
                }
            } else {
                $success = true; //for this submission, it's successful.
                Zx_Message::set_error_message('无效记录。');
            }
        } else {
            $qid = (isset($params[0])) ? intval($params[0]) : 0;
            $question = Model_Question::get_one($qid);
            if ($question &&
                    ($question['status'] == Model_Question::S_ACTIVE ||
                    $question['status'] == Model_Question::S_CORRECT)) {
                
            } else {
                Zx_Message::set_error_message('无效记录或该回答被举报或被删除或被禁止显示， 目前无法更新。');
            }
        }
        if ($success) {
            Transaction_Html::goto_previous_user_page();
        } else {
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('question', $question);
        }
    }

    /**
     * if a question has an answer or voted or claimed or correct, it can not be deleted
     * only set staus to S_DELETED
     */
    public function delete() {
        $qid = (isset($params[0])) ? intval($params[0]) : 0;
        if ($qid > 0 && Model_Question::question_belong_to_user($qid, $this->uid
                        && Model_Question::can_be_deleted($qid)
        )) {
            Transaction_Question::delete_question($qid);
        } else {
            Zx_Message::set_error_message('该问题不能被删除。');
        }
        Transaction_Html::goto_previous_user_page();
    }

}