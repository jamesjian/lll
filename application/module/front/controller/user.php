<?php

namespace App\Module\Front\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\View\View;
use \Zx\Test\Test;
use \App\Model\User as Model_User;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;
use \App\Transaction\User as Transaction_User;
use \App\Transaction\Html as Transaction_Html;
use \Zx\Message\Message as Zx_Message;
use \Zx\Controller\Route;
use \App\Transaction\Session as Transaction_Session;

class User extends Base {

    public $view_path;
    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/user/';
        $this->list_page = FRONT_HTML_ROOT . 'user/all/1/';
    }

    public function login1() {
        //Test::object_log('$_POST', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $login = false;
        if (Transaction_User::user_has_loggedin()) {
            $login = true;
        } else {
            if (isset($_POST['submit'])) {
                $uname = (isset($_POST['uname'])) ? trim($_POST['uname']) : '';
                $password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
                if (Transaction_User::verify_user($uname, $password)) {
                    $login = true;
                }
            }
        }
        if ($login) {
            //redirect to admin home page
            header('Location: ' . USER_HTML_ROOT . 'user/home');
        } else {
            View::set_view_file($this->view_path . 'login.php');
        }
    }

    public function logout() {
        if (Transaction_User::user_has_loggedin()) {
            Transaction_User::user_logout();
        }
        Transaction_Html::goto_home_page();
    }

    public function test() {
        //              $view = View::factory($this->view_path . 'validation_message');
        //    $this->view($view);
    }

    /**
     * ajax 
     */
    public function check_account() {
        $result = false;
        $message = '';
        $uname = (isset($_POST['uname'])) ? trim($_POST['uname']) : '';
        $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
        //\Zx\Test\Test::object_log('$uname', $uname, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (\Zx\Tool\Valid::alpha_numeric($uname, true)) {
            //  \Zx\Test\Test::object_log('$uname', 'true', __FILE__, __LINE__, __CLASS__, __METHOD__);
            if (Model_User::exist_uname_or_email($uname) || Model_User::exist_uname_or_email($email)) {
                //                \Zx\Test\Test::object_log('$uname', '1111', __FILE__, __LINE__, __CLASS__, __METHOD__);
                $message = "该账户已被注册, 请输入不同的账户名称";
            } else {
                //              \Zx\Test\Test::object_log('$uname', '2222', __FILE__, __LINE__, __CLASS__, __METHOD__);
                $result = true;
                $message = "该账户未被注册，";
            }
        } else {
            $message = "账户中含有无效字符， 请重新输入";
        }
        //\Zx\Test\Test::object_log('$message', $message, __FILE__, __LINE__, __CLASS__, __METHOD__);
        View::set_view_file($this->view_path . 'check_account_result.php');
        View::set_action_var('result', $result);
        View::set_action_var('message', $message);
        View::do_not_use_template(); //ajax
    }

    public function register() {
        //\Zx\Test\Test::object_log('$uname', 'true', __FILE__, __LINE__, __CLASS__, __METHOD__);
        //\Zx\Test\Test::object_log('$uname', 'true', __FILE__, __LINE__, __CLASS__, __METHOD__);
        //$user name, $password1, $password2, $email 
        $success = false;
        $errors = array();
        $posted = array();
        $vcode = (isset($_SESSION['VCODE'])) ? $_SESSION['VCODE'] : 'INVALID VCODE'; //must have vcode
//App_Test::objectLog('$vcode', $vcode, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (isset($_POST['vcode']) && trim($_POST['vcode']) == $vcode &&
                !empty($_POST['uname']) &&
                \Zx\Tool\Valid::email($_POST['email']) &&
                !empty($_POST['password1']) && trim($_POST['password1']) == trim($_POST['password2'])) {


            $uname = trim($_POST['uname']);
            $email = trim($_POST['email']);
            $vcode = trim($_POST['vcode']);
            $password = trim($_POST['password1']);

            $posted = array(
                'uname' => $uname,
                'email' => $email,
                'vcode' => $vcode,
                'password' => $password,
            );


            if (Transaction_User::register_user($posted)) {
                $success = true;
                $message = "感谢您在" . SITENAME . "注册， 我们已经发送邮件到您的电子邮箱，请查看邮件并激活您的账户。";
                View::set_view_file($this->view_path . 'validation_message.php');
                View::set_action_var('message', $message);
            }
        } elseif (isset($_POST['vcode'])) {
            Zx_Message::set_error_message('您未输入验证码或输入的验证码不正确， 请重新输入');
        }
        if (!$success) {
            View::set_view_file($this->view_path . 'register.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        }
    }

    /**
     * activate user by check query string with substr(md5(uname.email.password), 1, 30)
     * in this application, after activation, go to account home page directly
     */
    public function activate() {

        $uid = intval($this->request->param('id', 0));
        $code = $this->request->param('stuff', '');
        if (Transaction_User::activate_user($uid, $code)) {
            Transaction_Html::goto_user_home_page();
        } else {
            Zx_Message::set_error_message("对不起， 您的账户未激活成功， 请重新激活， 或点击链接获取新的激活邮件， 或注册一个新账户");
            View::set_view_file($this->view_path . 'activation_error.php');
        }
    }

    /**
     * if a user want a new activation link, use this page,
     * make sure this user is not activated, otherwise, not necessary to send link again
     */
    public function activation_link() {
        $success = false;
        $errors = array();
        $posted = array();
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $name = trim($_POST['name']);
            if (Transaction_User::send_another_activation_link($name)) {
                $success = true;
            }
        } else {
            Zx_Message::set_error_message('name cannot be empty');
        }
        if (!$success) {
            //$right_ads = Model_Servicecatrightad::get_right_ads($channel_id); //for right vertical ads
            View::set_view_file($this->view_path . 'activation_link_form.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        } else {
            View::set_view_file($this->view_path . 'validation_message.php');
            View::set_action_var('message', $message);
        }
    }

    /**
     * Todo: better to use a dedicated sessid, otherwise, popup dialog create a new sessid, but the other forms in the page has an old sessid
     * for ajax
     * because there is another normal login form, so use seperate login action to handle the form
     */
    public function login_form_ajax() {
        Zx_Message::set_new_SESSID();
        View::set_view_file($this->view_path . 'login_ajax');

        View::do_not_use_template(); //ajax
    }

    /**
     * if has logged in, return back to previous page
     * if has not logged in, login, if successfull, go to previous page, 
     *                              if fail, display login form again
     */
    public function login() {
        $success = false;
        $errors = array();
        $posted = array();

        //App_Test::objectLog('Session',  App_Session::get_all_session(), __FILE__, __LINE__, __CLASS__, __METHOD__);        
        if (Transaction_User::user_has_loggedin()) {
            Transaction_Html::goto_user_home_page();
        } else {
            //if not logged in
            if (isset($_POST['uname']) && !empty($_POST['uname']) &&
                    isset($_POST['password']) && !empty($_POST['password'])
            ) {
                $uname = $_POST['uname'];
                $password = $_POST['password'];

                if (Transaction_User::verify_user($uname, $password)) {
                    Transaction_Html::goto_user_home_page();
                } else {
                    //if not valid, display form again
                    //maybe disabled by administrator
                    Zx_Message::set_error_message("登录失败. 请检查您的用户名和密码, 如果您输入的用户名尚未激活， 请检查您的邮箱并激活用户后， 重新登录。");
                }
            } else {
                $errors = array();
            }
        }
        if (!$success) {
            //if invalid form or have not displayed, display form 
//            $right_ads = Model_Servicecatrightad::get_right_ads($channel_id); //for right vertical ads
            View::set_view_file($this->view_path . 'login.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        }
    }

    public function forgotten_password() {
        //App_Test::objectLog('$_POST',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $success = false;
        $errors = array();
        $posted = array();
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $posted = array(
                'email' => $email,
            );
            if (Transaction_User::generate_new_password($email)) {
                Transaction_Html::goto_password_sent_page(); // back to login page
            }
        } else {
            $errors = array();
        }
        //$right_ads = Model_Homepagerightad::get_right_ads();  //for right vertical ads
        View::set_view_file($this->view_path . 'forgotten_password.php');
        View::set_action_var('posted', $posted);
        View::set_action_var('errors', $errors);
    }

    public function password_sent() {
        //$right_ads = Model_Homepagerightad::get_right_ads();  //for right vertical ads
        View::set_view_file($this->view_path . 'password_sent.php');
    }

    public function no_permission() {
        View::set_view_file($this->view_path . 'templates/no_permission');
        View::set_action_var('homepage', 'home/index');
        View::set_action_var('logout', 'user/logout');
    }

    public function error_404() {
        View::set_view_file($this->view_path . 'templates/404');
        View::set_action_var('homepage', 'home/index');
        View::set_action_var('logout', 'user/logout');
    }

    /**
     * display verification code in view/user_register.php
     * 
     */
    public function vcode() {
        View::set_view_file($this->view_path . 'vcode.php');
        View::do_not_use_template(); //ajax
    }

    public function vcode_ajax() {
        View::set_view_file($this->view_path . 'vcode.php');
        View::do_not_use_template(); //ajax
    }

    /**
     * ajax for add company form
     */
    public function exist_uname_ajax() {

        $suburb_name = isset($_POST['suburb_name']) ? trim($_POST['suburb_name']) : '';
        $message = '';
        if ($suburb_name != '') {
            if (Model_Suburb::exist_suburb($suburb_name)) {
                $message = "Suburb exists";
            } else {
                $message = '';
            }
        }
        View::set_view_file($this->view_path . 'suburb_name_exist_ajax.php');
        View::set_action_var('message', $message);
        View::do_not_use_template(); //ajax        
    }

    /**
     * list all users 
     * user/all/page number/search
     * email is hidden
     * order by score
     * pagination
     */
    public function all() {
        if (!\App\Transaction\Html::previous_page_is_search_page()) {
            \App\Transaction\Html::remember_current_page();
        }
        Transaction_Html::set_title('All user');
        Transaction_Html::set_keyword('all user');
        Transaction_Html::set_description('all user');
        $current_page = (isset($this->params[0])) ? intval($this->params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'score';
        $direction = 'DESC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        //when display all users, don't display 匿名用户
        if ($search != '') {
            $where = " id>3 AND uname LIKE '%$search%' OR email LIKE '%$search%'";
        } else {
            $where = 'id>3';
        }
        //\Zx\Test\Test::object_log('$current_page', $current_page, __FILE__, __LINE__, __CLASS__, __METHOD__);

        $users = Model_User::get_active_users_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_articles = Model_User::get_num_of_active_users($where);
        $num_of_pages = ceil($num_of_articles / NUM_OF_USERS_IN_FRONT_PAGE);
        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('users', $users);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    public function detail() {
        $uid1 = isset($this->params[0]) ? $this->params[0] : ''; //it's an id1
        $user = Model_User::get_one_by_id1($uid1);
        if ($user) {
            $uid = $user['id'];

            $home_url = HTML_ROOT;
            $category_url = FRONT_HTML_ROOT . 'article/category/' . $user['uname'];
            Transaction_Session::set_breadcrumb(0, $home_url, '首页');
            Transaction_Session::set_breadcrumb(1, Route::$url, $user['uname']);
            Transaction_Html::set_title($user['uname']);
            Transaction_Html::set_keyword($user['uname']);
            Transaction_Html::set_description($user['uname']);

            $recent_questions = Model_Question::get_recent_questions_by_uid($uid);
            $recent_answers = Model_Answer::get_recent_answers_by_uid($uid);
            $recent_ads = Model_Ad::get_recent_ads_by_uid($uid);
            View::set_view_file($this->view_path . 'one_user.php');
            View::set_action_var('user', $user);
            View::set_action_var('recent_questions', $recent_questions);
            View::set_action_var('recent_answers', $recent_answers);
            View::set_action_var('recent_ads', $recent_ads);
        } else {
            //if no article, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * search user name only
     */
    public function search() {
        //todo
        $search = isset($_POST['keyword']) ? trim($search) : '';
    }

}
