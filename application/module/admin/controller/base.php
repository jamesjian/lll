<?php

namespace App\Module\Admin\Controller;
//this is the base class of admin classes
use \Zx\Controller\Route;
use \Zx\View\View;
use \App\Transaction\Staff as Transaction_Staff;
class Base {
    public $template_path;
	public $view_path = '';
	public $params = array();
    public function init() {
		$this->params = Route::get_params();
        $this->template_path = APPLICATION_PATH . 'module/admin/view/templates/';
        View::set_template_file($this->template_path . 'template.php');
        View::set_template_var('title', 'this is admin title');
        View::set_template_var('keyword', 'this is admin keyword');
		$action = Route::get_action();
		if ($action == 'login' || $action == 'logout') {
		
		} else {
			if (Transaction_Staff::staff_has_loggedin()) {
			
			} else {
				header('Location: '.HTML_ROOT . 'admin/staff/login');
			}
		}
    }

}