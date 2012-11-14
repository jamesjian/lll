<?php

namespace App\Module\User\Controller;
//this is the base class of admin classes
use \Zx\Controller\Route;
use \Zx\View\View;
use \App\Transaction\User as Transaction_User;
class Base {
    public $template_path;
	public $view_path = '';
	public $params = array();
    public function init() {
		$this->params = Route::get_params();
        $this->template_path = APPLICATION_PATH . 'module/user/view/templates/';
        View::set_template_file($this->template_path . 'template.php');
        View::set_template_var('title', 'this is user title');
        View::set_template_var('keyword', 'this is user keyword');
		$action = Route::get_action();
		if ($action == 'login' || $action == 'logout') {
		
		} else {
			if (Transaction_Staff::staff_has_loggedin()) {
			
			} else {
				header('Location: '.HTML_ROOT . 'user/user/login');
			}
		}
    }

}