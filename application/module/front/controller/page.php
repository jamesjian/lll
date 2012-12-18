<?php
namespace App\Module\Front\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Controller\Route;
use \Zx\View\View;
use \App\Model\Page as Model_Page;

class Page extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/page/';
    }

    //one page
    public function about_us() {
        // $sql = "SELECT * FROM session ";
        //$r = Mysql::select_all($sql);
        //echo $_SESSION['pk'];
        $about_us = Model_Page::get_about_us();
        View::set_view_file($this->view_path . 'about_us.php');
        View::set_action_var('about_us', $about_us);
    }

    //one page
    public function terms() {
        //$term_condition = Model_Page::get_terms();
        View::set_view_file($this->view_path . 'terms.php');
        //View::set_action_var('term_condition', $term_condition);
    }
    //one page
    public function privacy() {
        //$privacy = Model_Page::get_privacy();
        View::set_view_file($this->view_path . 'privacy.php');
        //View::set_action_var('privacy', $privacy);
    }

    //many rows;
    public function faqs() {
        $faqs = Model_Page::get_faqs();
        View::set_view_file($this->view_path . 'faqs.php');
        View::set_action_var('faqs', $faqs);
    }

}
