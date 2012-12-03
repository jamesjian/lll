<?php

namespace App\Module\Admin\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

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
                header('Location: ' . HTML_ROOT . 'admin/staff/login');
            }
        }
    }

    public function search() {

        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            \App\Transaction\Html::remember_current_admin_page();
            \App\Transaction\Html::remember_current_admin_search_keyword(trim($_POST['search']));  //keep search keyword
            $link = $this->list_page . trim($_POST['search']);
        } else {
            $keyword = \App\Transaction\Html::get_previous_admin_search_keyword();

            if ($keyword && $keyword != '') {
                $link = $this->list_page . $keyword;
            } else {
                $link = $this->list_page;
            }
        }
        header('Location: ' . $link);
    }

}