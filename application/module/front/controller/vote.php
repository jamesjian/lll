<?php

namespace App\Module\Front\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Question as Transaction_Question;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Vote as Model_Vote;
use App\Transaction\User as Transaction_User;

/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class Vote extends Base {

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/vote/';
        $this->list_page = FRONT_HTML_ROOT . 'vote/all/';
    }

    /**
     * ajax
     * vote an item
     * create/1/5: first 1 is item type, 1: question, 2: answer, 3:ad, second 5 is item id
     */
    public function create() {
        $success = false;
        $message = '';
        if (($user_id = Transaction_User::get_user_id()) > 0) {
            $item_type = (isset($params[0])) ? intval($params[0]) : 0;
            $question_id = (isset($params[1])) ? intval($params[1]) : 0;
            if ($item_type > 0 && $question_id > 0)
                switch ($item_type) {
                    case 1:
                        $active_item = Model_Question::is_active_question($item_id);
                        $item_name = '问题';
                        break;
                    case 2:
                        $active_item = Model_Question::is_active_answer($item_id);
                        $item_name = '回答';
                        break;
                    case 3:
                        $active_item = Model_Question::is_active_ad($item_id);
                        $item_name = '广告';
                        break;
                    default:
                        $active_item = false;
                }
            if ($active_item) {
                Transaction_Vote::create($user_id, $item_type, $item_id);
                $message = "您已投票成功， 感谢您的支持。";
                $success = true;
            } else {
                $message = '对不起， 该' . $item_name . '无效， 不接受投票， 谢谢！'; // inactive or not existing question
            }
        } else {
            $message = '对不起， 只有注册用户登录后才可以投票。';
        }
        View::set_view_file($this->view_path . 'result.php');
        View::set_action_var('message', $message);
        View::do_not_use_template();
    }

}
