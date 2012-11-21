<?php

namespace App\Module\Front\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\View\View;
use \Zx\Test\Test;
use \App\Model\User as Model_User;
use \App\Transaction\User as Transaction_User;
use \App\Transaction\Html as Transaction_Html;
use \Zx\Message\Message as Zx_Message;

class User extends Base {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/user/';
        parent::init();
    }

    public function login1() {
        //Test::object_log('$_POST', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $login = false;
        if (Transaction_User::user_has_loggedin()) {
            $login = true;
        } else {
            if (isset($_POST['submit'])) {
                $user_name = (isset($_POST['user_name'])) ? trim($_POST['user_name']) : '';
                $password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
                if (Transaction_User::verify_user($user_name, $password)) {
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
        if (Transaction_User::has_loggedin()) {
            Transaction_User::logout();
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
        $user_name = (isset($_POST['user_name'])) ? trim($_POST['user_name']) : '';
        $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
        //\Zx\Test\Test::object_log('$user_name', $user_name, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (\Zx\Tool\Valid::alpha_numeric($user_name, true)) {
          //  \Zx\Test\Test::object_log('$user_name', 'true', __FILE__, __LINE__, __CLASS__, __METHOD__);
            if (Model_User::exist_user_name_or_email($user_name) || Model_User::exist_user_name_or_email($email)) {
            //                \Zx\Test\Test::object_log('$user_name', '1111', __FILE__, __LINE__, __CLASS__, __METHOD__);
                $message = "该账户已被注册, 请输入不同的账户名称";
            } else {
              //              \Zx\Test\Test::object_log('$user_name', '2222', __FILE__, __LINE__, __CLASS__, __METHOD__);
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
        //\Zx\Test\Test::object_log('$user_name', 'true', __FILE__, __LINE__, __CLASS__, __METHOD__);
        //\Zx\Test\Test::object_log('$user_name', 'true', __FILE__, __LINE__, __CLASS__, __METHOD__);
        //$user name, $password1, $password2, $email 
        $success = false;
        $errors = array();
        $posted = array();
        $vcode = (isset($_SESSION['VCODE'])) ? $_SESSION['VCODE'] : 'INVALID VCODE'; //must have vcode
//App_Test::objectLog('$vcode', $vcode, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (isset($_POST['vcode']) && trim($_POST['vcode']) == $vcode &&
                !empty($_POST['user_name']) &&
                \Zx\Tool\Valid::email($_POST['email']) &&
                !empty($_POST['password1']) && trim($_POST['password1']) == trim($_POST['password2'])) {


            $user_name = trim($_POST['user_name']);
            $email = trim($_POST['email']);
            $vcode = trim($_POST['vcode']);
            $password = trim($_POST['password1']);

            $posted = array(
                'user_name' => $user_name,
                'email' => $email,
                'vcode' => $vcode,
                'password' => $password,
            );


            if (Transaction_User::register_user($posted)) {
                $success = true;
                $message = "感谢您在" . SITENAME . "注册， 我们已经发送邮件到您的电子邮箱，请查看邮件并激活您的账户。 您很快就可以在fengyunlist.com.au上建立生意、 发布广告、 上传产品及发布需求或进行其他网络推广活动。";
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
     * activate user by check query string with substr(md5(user_name.email.password), 1, 30)
     * in this application, after activation, go to account home page directly
     */
    public function activate() {

        $user_id = intval($this->request->param('id', 0));
        $code = $this->request->param('stuff', '');
        if (Transaction_User::activate_user($user_id, $code)) {
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
            App_Http::goto_my_account_page();
        } else {
            //if not logged in
            if (isset($_POST['user_name']) && !empty($_POST['user_name']) &&
                    isset($_POST['password']) && !empty($_POST['password'])
            ) {
                $user_name = $_POST['user_name'];
                $password = $_POST['password'];

                if (Transaction_User::verify_user($user_name, $password)) {
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
    public function exist_user_name_ajax() {

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
     * email is hidden


     * 
     * pagination
     */
    public function all() {
        Transaction_Html::set_title('All user');
        Transaction_Html::set_keyword('all user');
        Transaction_Html::set_description('all user');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'rank';
        $direction = 'DESC';
        $users = Model_User::get_active_users_by_page_num($current_page, $order_by, $direction);
        $num_of_articles = Model_User::get_num_of_active_users();
        $num_of_pages = ceil($num_of_articles / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('users', $users);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    public function detail() {
        $user_id = $this->params[0];

        $user = Model_User::get_one($user_id);
        //\Zx\Test\Test::object_log('$article', $article, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($user) {

            $home_url = HTML_ROOT;
            $category_url = FRONT_HTML_ROOT . 'article/category/' . $article['cat_name'];
            Transaction_Session::set_breadcrumb(0, $home_url, '首页');
            Transaction_Session::set_breadcrumb(1, $category_url, $article['cat_name']);
            Transaction_Session::set_breadcrumb(2, Route::$url, $article['title']);
            Transaction_Html::set_title($user['name']);
            Transaction_Html::set_keyword($user['name']);
            Transaction_Html::set_description($user['name']);
            //Model_Article::increase_rank($article_id);
            $recent_questions = Model_Question::get_recent_questions_by_user_id($user_id);
            $recent_answers = Model_Question::get_recent_answers_by_user_id($user_id);
            View::set_view_file($this->view_path . 'one_user.php');
            View::set_action_var('user', $user);
            View::set_action_var('recent_questions', $recent_questions);
            View::set_action_var('recent_answers', $recent_answers);
        } else {
            //if no article, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    public function search() {
        //todo
    }

}
