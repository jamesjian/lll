<?php

namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Message\Message as Zx_Message;;
use \App\Model\User as Model_User;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Transaction\Answer as Transaction_Answer;
use \App\Transaction\Html as Transaction_Html;
use \Zx\View\View;
use \Zx\Test\Test;

class Answer extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'answer/';
        $this->list_page = ADMIN_HTML_ROOT . 'answer/retrieve/1/title/ASC/';
        \App\Transaction\Session::set_ck_upload_path('answer');
    }

   
    /**
     * must enter user id, qid and content 
     */
    public function create() {
        $success = false;
        if (isset($_POST['submit']) && 
                isset($_POST['uid']) && 
                isset($_POST['qid']) && 
                isset($_POST['content'])) {
            $uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
            $qid = isset($_POST['qid']) ? trim($_POST['qid']) : 0;
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $rank = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($uid >0 && $qid> 0) {
                $arr = array('uid' => $uid,
                    'qid' => $qid,
                    'content' => $content,
                    'rank' => $rank,
                    'status' => $status,
                    );
                if (Transaction_Answer::create_answer($arr)) {
                    $success = true;
                }
            }
        } else {
            $qid = isset($this->params[0]) ? intval($this->params[0]) : 0;
            if (!Model_Question::exist_qid($qid)){
                Zx_Message::set_error_message('无效问题。');
                header('Location: ' . $this->list_page);
            } else {
                Zx_Message::set_error_message('user id,qid and content not be empty。');
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

    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     * 
     * 
     */
    public function retrieve() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
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
      retrieve_by_uid/uid/page/orderby/direction
     * 
     * content is content of answer, title is title of question
     */
    public function retrieve_by_uid() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_current_l1_menu('Answer');
        $uid = isset($this->params[0]) ? intval($this->params[0]) : 0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " content LIKE '%$search%' OR title LIKE '%$search%' OR tnames LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $answer_list = Model_Answer::get_answers_by_uid_and_page_num($uid, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Answer::get_num_of_answers_by_uid($uid, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('answer_list', $answer_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_uid.php');
        View::set_action_var('uid', $uid);
        View::set_action_var('answer_list', $answer_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    /**
     * under one question
      retrieve_by_qid/qid/page/orderby/direction
      * content is content of answer, title is title of question
     */
    public function retrieve_by_qid() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        //\App\Transaction\Html::set_current_l1_menu('Answer');
        $qid = isset($this->params[0]) ? intval($this->params[0]) : 0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " content LIKE '%$search%' OR title LIKE '%$search%' OR tnames LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $answer_list = Model_Answer::get_answers_by_qid_and_page_num($qid, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Answer::get_num_of_answers_by_qid($qid,$where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('answer_list', $answer_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_qid.php');
        View::set_action_var('qid', $qid);
        View::set_action_var('answer_list', $answer_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    /**
     * no status involved
     */
    public function update() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit'])) {
            $aid = (isset($_POST['aid'])) ? intval($_POST['aid']) : 0;
            if ($aid > 0) {
                //must be the owner of the question and correct status
                $answer = Model_Answer::get_one($aid);
                if ($answer) {
                    if (isset($_POST['content']) && !empty($_POST['content'])) {
                        $arr['content'] = trim($_POST['content']);
                        Transaction_Answer::update_by_admin($aid, $arr);
                        $success = true; //for this submission, it's always successful.
                    } else {
                        Zx_Message::set_error_message('内容请填写完整。');
                    }
                } else {
                    //invalid record
                    $success = true; //for this submission, it's successful.
                    Zx_Message::set_error_message('无效记录。');
                }
            } else {
                //invalid record
                $success = true; //for this submission, it's successful.
                Zx_Message::set_error_message('无效记录。');
            }
        } else {
            $aid = (isset($params[0])) ? intval($params[0]) : 0;
            $answer = Model_Answer::get_one($aid);
            if ($answer) {
                //prepare for update
            } else {
                $success = true; //for this submission, it's successful.
                Zx_Message::set_error_message('无效记录。');
            }
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('answer', $answer);
        }
    }
    /**
     * only status involved
     */
    public function update_status() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit'])) {
            $aid = (isset($_POST['aid'])) ? intval($_POST['aid']) : 0;
            if ($aid > 0) {
                //must be the owner of the question and correct status
                $answer = Model_Answer::get_one($aid);
                if ($answer) {
                    if (isset($_POST['status'])) {
                            $arr['status'] = intval($_POST['status']);
                        Transaction_Answer::update_status_by_admin($aid, $arr);
                        $success = true; //for this submission, it's always successful.
                    } else {
                        Zx_Message::set_error_message('请选择status。');
                    }
                } else {
                    //invalid record
                    $success = true; //for this submission, it's successful.
                    Zx_Message::set_error_message('无效记录。');
                }
            } else {
                //invalid record
                $success = true; //for this submission, it's successful.
                Zx_Message::set_error_message('无效记录。');
            }
        } else {
            $aid = (isset($params[0])) ? intval($params[0]) : 0;
            $answer = Model_Answer::get_one($aid);
            if ($answer) {
                //prepare for update
            } else {
                $success = true; //for this submission, it's successful.
                Zx_Message::set_error_message('无效记录。');
            }
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            $statuses = Model_Answer::get_statuses();
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('answer', $answer);
            View::set_action_var('statuses', $statuses);
        }
    }    
}
