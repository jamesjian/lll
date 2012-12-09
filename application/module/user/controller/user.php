<?php
namespace App\Module\User\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\User as Model_User;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;

/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class User extends Base {
    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/user/view/user/';
    }
    /**
     * it's the splash page of the user
     * show some links, some new messages, some new notifications
     */
    public function home() {
        Transaction_Html::remember_current_page();
        $num_of_questions = Model_Question::get_num_of_active_questions_by_uid($this->user_id);
        $num_of_answers = Model_Answer::get_num_of_active_answers_by_uid($this->user_id);
        $num_of_ads = Model_Ad::get_num_of_active_ads_by_uid($this->user_id);
        //some information 
         View::set_view_file($this->view_path . 'home.php');
         View::set_action_var('user', $this->user);
         View::set_action_var('num_of_questions', $num_of_questions);
         View::set_action_var('num_of_answers', $num_of_answers);
         View::set_action_var('num_of_ads', $num_of_ads);
    }
  
    /**
     * after login, update profile
     * cannot update user name, email and password from this form
     */
    public function update_profile() {
        //\Zx\Test\Test::object_log('$posted', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::remember_current_page();
        //$user_id = App_User::get_user_id();
        $user = Model_User::get_user($user_id);
        $success = false;
        $errors = array();
        $posted = array();
        if (isset($_POST['sess']) AND $_POST['sess'] == App_Session::get_new_form_session()) {
            $post = Validation::factory($_POST);
            if ($post->check()) {
                //$user_name = isset($_POST['user_name']) ?  trim($_POST['user_name']) : '';
                $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
                $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
                $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
                $suburb_id = isset($_POST['suburb']) ? intval($_POST['suburb']) : '';
                $city_id = isset($_POST['city']) ? intval($_POST['city']) : 0;
                $state = isset($_POST['state']) ? trim($_POST['state']) : '';
                $posted = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone' => $phone,
                    'suburb_id' => $suburb_id,
                    'city_id' => $city_id,
                    'state' => $state,
                );
                if (App_User::update_profile(App_User::get_user_id(), $posted)) {
                    //App_Http::goto_previous_page();
                    App_Http::goto_my_account_page();
                }
            } else {
                $errors = $post->errors('user');
            }
        }
        if (!$success) {
            $state_list = Model_Region::get_state_only_arr();
            if ($user->state != '') {
                $current_state = $user->state;
            } else {
                $current_state = 'NSW';
            }
            $city_list = Model_Region::get_city_arr_by_state($current_state);
            if ($user->city_name_en != '') {
                $current_city = $user->city_name_en;
            } else {
                $current_city = '';
            }
            if ($user->suburb != '') {
                $current_suburb = $user->suburb . $user->postcode;
            } else {
                $current_suburb = '';
            }
            $suburb_list = Model_Region::get_suburbs_by_state($current_state);
            $view = View::factory($this->view_path . 'update_profile');
            $view->set('posted', $posted);
            $view->set('errors', $errors);
            $view->set('user', $user);
            $view->set('sess', App_Session::set_new_form_session());
            $this->view($view);
        }
    }

   
/**
     * when email is changed, need to activate again
     */
    public function change_email() {
        //\Zx\Test\Test::object_log('settingt',$_SESSION['user'], __FILE__, __LINE__, __CLASS__, __METHOD__);
        $success = false;
        $errors = null;
        $posted = array();
        if (isset($_POST['sess']) AND $_POST['sess'] == App_Session::get_new_form_session()) {

            $post = Validation::factory($_POST);
            $post->rule('email', 'email');


            if ($post->check()) {
                $email = trim($_POST['email']);
                $posted = array(
                    'email' => $email,
                );
                //\Zx\Test\Test::object_log('$posted',$posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
                $user_id = App_User::get_user_id();
                if (App_User::change_email($user_id, $posted)) {
                    $success = true;
                    //App_Http::goto_previous_page();
                    App_Http::goto_logout_page(); // back to my account page
                }
            } else {
                $errors = $post->errors('user');
            }
        }
        if (!$success) {
            $user = Model_User::get_user(App_User::get_user_id());
            $state_list = Model_Region::get_state_only_arr();
            if ($user->state != '') {
                $current_state = $user->state;
            } else {
                $current_state = 'NSW';
            }
            $city_list = Model_Region::get_city_arr_by_state($current_state);
            if ($user->city_name_en != '') {
                $current_city = $user->city_name_en;
            } else {
                $current_city = '';
            }
            if ($user->suburb != '') {
                $current_suburb = $user->suburb . $user->postcode;
            } else {
                $current_suburb = '';
            }
            $suburb_list = Model_Region::get_suburbs_by_state($current_state);
            $view = View::factory($this->view_path . 'update_profile');
            $view->set('posted', $posted);
            $view->set('errors', $errors);
            $view->set('sess', App_Session::set_new_form_session());
            $view->set('user', $user);
            $this->view($view);
        }
    }
    

  /**
     * after login, update profile
     * cannot update user name, email and password from this form
     */
    public function change_profile() {
        //\Zx\Test\Test::object_log('$posted', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        App_Http::remember_this_page();
        $user_id = App_User::get_user_id();
        $user = Model_User::get_user($user_id);
        $success = false;
        $errors = array();
        $posted = array();
        if (isset($_POST['sess']) AND $_POST['sess'] == App_Session::get_new_form_session()) {
            $post = Validation::factory($_POST);
            if ($post->check()) {
                //$user_name = isset($_POST['user_name']) ?  trim($_POST['user_name']) : '';
                $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
                $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
                $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
                $suburb_id = isset($_POST['suburb']) ? intval($_POST['suburb']) : '';
                $city_id = isset($_POST['city']) ? intval($_POST['city']) : 0;
                $state = isset($_POST['state']) ? trim($_POST['state']) : '';
                $posted = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone' => $phone,
                    'suburb_id' => $suburb_id,
                    'city_id' => $city_id,
                    'state' => $state,
                );
                if (App_User::update_profile(App_User::get_user_id(), $posted)) {
                    //App_Http::goto_previous_page();
                    App_Http::goto_my_account_page();
                }
            } else {
                $errors = $post->errors('user');
            }
        }
        if (!$success) {
            $state_list = Model_Region::get_state_only_arr();
            if ($user->state != '') {
                $current_state = $user->state;
            } else {
                $current_state = 'NSW';
            }
            $city_list = Model_Region::get_city_arr_by_state($current_state);
            if ($user->city_name_en != '') {
                $current_city = $user->city_name_en;
            } else {
                $current_city = '';
            }
            if ($user->suburb != '') {
                $current_suburb = $user->suburb . $user->postcode;
            } else {
                $current_suburb = '';
            }
            $suburb_list = Model_Region::get_suburbs_by_state($current_state);
            $view = View::factory($this->view_path . 'change_profile');
            $view->set('posted', $posted);
            $view->set('errors', $errors);
            $view->set('user', $user);
            $view->set('state_list', $state_list);
            $view->set('city_list', $city_list);
            $view->set('suburb_list', $suburb_list);
            $view->set('current_state', $current_state);
            $view->set('current_city', $current_city);
            $view->set('current_suburb', $current_suburb);
            $view->set('sess', App_Session::set_new_form_session());
            $this->view($view);
        }
    }


    public function change_password() {
        //\Zx\Test\Test::object_log('settingt',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (App_User::has_loggedin()) {
            $success = false;
            $errors = array();
            $posted = array();
            $user = Model_User::get_user(App_User::get_user_id());
            if (isset($_POST['sess']) AND $_POST['sess'] == App_Session::get_new_form_session()) {

                $post = Validation::factory($_POST);
                $post->rule('old_password', 'not_empty')
                        ->rule('password1', 'not_empty')
                        ->rule('password1', 'matches', array(':validation', 'password1', 'password2'));

                // \Zx\Test\Test::object_log('$posted',$posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
                if ($post->check()) {
                    $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : '';
                    $new_password = isset($_POST['password1']) ? trim($_POST['password1']) : '';
                    $posted = array(
                        'old_password' => $old_password,
                        'new_password' => $new_password,
                    );
                    //\Zx\Test\Test::object_log('settingt',$posted, __FILE__, __LINE__, __CLASS__, __METHOD__);

                    $user_id = App_User::get_user_id();
                    if (App_User::change_password(App_User::get_user_id(), $posted)) {
                        $success = true;
                        //App_Http::goto_previous_page();
                        App_Session::set_success_message('您的新密码已生效。');
                        App_Http::goto_my_account_page(); // back to my account page
                    }
                } else {
                    $errors = $post->errors('user');
                }
            }
            if (!$success) {
                $state_list = Model_Region::get_state_only_arr();
                if ($user->state != '') {
                    $current_state = $user->state;
                } else {
                    $current_state = 'NSW';
                }
                $city_list = Model_Region::get_city_arr_by_state($current_state);
                if ($user->city_name_en != '') {
                    $current_city = $user->city_name_en;
                } else {
                    $current_city = '';
                }
                if ($user->suburb != '') {
                    $current_suburb = $user->suburb . $user->postcode;
                } else {
                    $current_suburb = '';
                }
                $suburb_list = Model_Region::get_suburbs_by_state($current_state);
                $view = View::factory($this->view_path . 'update_profile');
                $view->set('user', $user);
                $view->set('state_list', $state_list);
                $view->set('city_list', $city_list);
                $view->set('suburb_list', $suburb_list);
                $view->set('current_state', $current_state);
                $view->set('current_city', $current_city);
                $view->set('current_suburb', $current_suburb);
                $view->set('posted', $posted);
                $view->set('errors', $errors);
                $view->set('sess', App_Session::set_new_form_session());
                $this->view($view);
            }
        } else {
            App_Http::goto_login_page();
        }
    }
    public function change_portrait() {
        //\Zx\Test\Test::object_log('settingt',$_FILES, __FILE__, __LINE__, __CLASS__, __METHOD__);

        App_Http::remember_this_page();
        $user_id = App_User::get_user_id();
        $user = Model_User::get_user($user_id);
        if (isset($_POST['sess']) AND $_POST['sess'] == App_Session::get_new_form_session()) {
            if (App_User::change_portrait($user_id)) {
                //App_Http::goto_previous_page();

                $this->request->redirect(MEMHTMLROOT . 'user/change_portrait');
            }
        }
        $view = View::factory($this->view_path . 'change_portrait');
        $view->set('user', $user);
        $view->set('sess', App_Session::set_new_form_session());
        $this->view($view);
    }

    

    


}