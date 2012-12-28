<?php
namespace App\Module\User\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use App\Transaction\User as Transaction_User;
use App\Transaction\Answser as Transaction_Answser;
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
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/user/view/answer/';
    }

    /**
     * 
     */
    public function vote() {
        $uid =$this->uid;
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

    public function answer() {
        $success = false;
        $uid =$this->uid;
        if (isset($_POST['submit']) &&
                isset($_POST['uid']) &&
                isset($_POST['qid']) &&
                isset($_POST['content'])) {
            $qid = isset($_POST['qid']) ? trim($_POST['qid']) : 0;
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $rank = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($uid > 0 && $qid > 0) {
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
            if (!Model_Question::exist_qid($qid)) {
                Message::set_error_message('无效问题。');
                header('Location: ' . $this->list_page);
            } else {
                Message::set_error_message('user id,qid and content not be empty。');
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
            
        }
        header('Location: ' . Transaction_Session::get_previous_page());
    }
    
    /**
     * link answer id to ad id
     * 2 text fields, one for answer, one for ad
     * 
     * 
     */
    public function link_ad()
    {
        $success = false;
        if (isset($_POST['submit']) &&
                isset($_POST['aids']) &&
                isset($_POST['ad_id'])) {
            $aids = isset($_POST['aids']) ? trim($_POST['aids']) : 0;
            $ad_id = isset($_POST['ad_id']) ? intval($_POST['ad_id']) : 0;

            if ($aids <> '' &&  $ad_id>0) {
                $arr = array('aids' => $aids,
                    'ad_id' => $ad_id,
                    'uid' => $this->uid,
                );
                if (Transaction_Answer::link_ad($arr)) {
                    $success = true;
                }
            }
        } else {
            $ad_id = isset($this->params[0]) ? intval($this->params[0]) : 0;
            if ($ad_id>0 && Model_Ad::ad_belong_to_user($ad_id, $this->uid)) {
                //Message::set_error_message('无效问题。');
                //header('Location: ' . $this->list_page);
            } else {
                Message::set_error_message('user id,qid and content not be empty。');
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'link.php');
        View::set_action_var('ad_id', $ad_id);
        }
        //header('Location: ' . Transaction_Session::get_previous_page());
    }

}