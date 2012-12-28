<?php

namespace App\Module\User\Controller;

//this is the base class of admin classes
use \Zx\Controller\Route;
use \Zx\View\View;
use \App\Transaction\User as Transaction_User;
use \App\Model\User as Model_User;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;

class Base {

    public $template_path;
    public $view_path = '';
    public $params = array();
    public $uid = 0;
    public $user = NULL;

    public function init() {
        $this->params = Route::get_params();
        $this->template_path = APPLICATION_PATH . 'module/user/view/templates/';
        View::set_template_file($this->template_path . 'template.php');
        View::set_template_var('title', 'this is user title');
        View::set_template_var('keyword', 'this is user keyword');
        $action = Route::get_action();
        if ($action == 'login' || $action == 'logout') {
            
        } else {
            if (Transaction_User::user_has_loggedin()) {
                $this->uid = Transaction_User::get_uid(); //it should be valid
                $this->user = Model_User::get_one($this->uid); //it should be valid
                //for user menu
                $num_of_questions = Model_Question::get_num_of_active_questions_by_uid($this->uid);
                $num_of_answers = Model_Answer::get_num_of_active_answers_by_uid($this->uid);
                $num_of_ads = Model_Ad::get_num_of_active_ads_by_uid($this->uid);
                View::set_template_var('user', $this->user);
                View::set_template_var('num_of_questions', $num_of_questions);
                View::set_template_var('num_of_answers', $num_of_answers);
                View::set_template_var('num_of_ads', $num_of_ads);
                
            } else {
                header('Location: ' . HTML_ROOT . 'user/user/login');
            }
        }
    }

}