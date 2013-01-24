<?php

namespace App\Module\Front\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View as Zx_View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Question as Transaction_Question;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Vote as Model_Vote;
use App\Transaction\User as Transaction_User;
use \App\Transaction\Vote as Transaction_Vote;
use \App\Model\Ad as Model_Ad;
use \App\Model\Question as Model_Question;

/**
  only user can claim and vote
 */
class Vote extends Base {

    public function init() {
        parent::init();
        $this->view_path = FRONT_VIEW_PATH . 'vote/';
        //$this->list_page = FRONT_HTML_ROOT . 'vote/all/';
    }

    public function vote_popup_form() {
        if (Transaction_User::user_has_loggedin()) {
            $loggedin = true;
        } else {
            $loggedin = false;
        }
        Zx_View::set_view_file($this->view_path . 'vote_popup_form.php');
        Zx_View::set_action_var('item_type', $item_type);
        Zx_View::set_action_var('item_id', $item_id);
        Zx_View::set_action_var('loggedin', $loggedin);
        Zx_View::do_not_use_template(); //ajax        
    }

    /**
      check logged in first, 
     * if not logged in, verify user name and password,
     * vote a question or an answer (no ad) , the item must be active
     * create/1/5: first 1 is item type, 1: question, 2: answer,  second 5 is item id
     */
    public function create() {
       $loggedin = false;
        $success = false;
        $posted = array();

        //App_Test::objectLog('Session',  App_Session::get_all_session(), __FILE__, __LINE__, __CLASS__, __METHOD__);        
        if (Transaction_User::user_has_loggedin()) {
            $loggedin=true;
        } else {
            if (isset($_POST['uname']) && !empty($_POST['uname']) &&
                    isset($_POST['password']) && !empty($_POST['password'])
            ) {
                $uname = $_POST['uname'];
                $password = $_POST['password'];

                if (Transaction_User::verify_user($uname, $password)) {
                    //Transaction_Html::goto_user_home_page();
                    $loggedin = true;
                } else {
                    Zx_Message::set_error_message("登录失败. 请检查您的用户名和密码, 如果您输入的用户名尚未激活， 请检查您的邮箱并激活用户后， 重新登录。");
                }
            } 
        } 
        if ($loggedin) {
            $user = $_SESSION['user'];
            if (isset($_POST['confirm']) && !empty($_POST['confirm'])){
                //$_POST['confirm']  is a checkbox, if $_POST['confirm'] is not empty, it's valid
                $item_type = $this->params[0];
                $item_id = $this->params[1];   //for question and answer it's id1
                if (Transaction_Vote::create($item_type, $item_id)) {
                     Zx_Message::set_error_message("感谢您的关注。");

                } else {
                   Zx_Message::set_error_message("您提交的信息有误， 请重新操作。");
                }
            } 
        } 
        Zx_View::set_view_file($this->view_path . 'vote_result.php');
    }

}
