<?php
namespace App\Module\Front\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');
use \Zx\Controller\Route;
use \Zx\View\View as Zx_View;
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
        //$r = Zx_Mysql::select_all($sql);
        //echo $_SESSION['pk'];
        $about_us = Model_Page::get_about_us();
        Zx_View::set_view_file($this->view_path . 'about_us.php');
        Zx_View::set_action_var('about_us', $about_us);
    }

    //one page
    public function terms() {
        //$term_condition = Model_Page::get_terms();
        Zx_View::set_view_file($this->view_path . 'terms.php');
        //View::set_action_var('term_condition', $term_condition);
    }
    //one page
    public function privacy() {
        //$privacy = Model_Page::get_privacy();
        Zx_View::set_view_file($this->view_path . 'privacy.php');
        //View::set_action_var('privacy', $privacy);
    }
    public function score() {
        //$privacy = Model_Page::get_privacy();
        Zx_View::set_view_file($this->view_path . 'score.php');
        //View::set_action_var('privacy', $privacy);
    }

    //many rows;
    public function faqs() {
        $faqs = Model_Page::get_faqs();
        Zx_View::set_view_file($this->view_path . 'faqs.php');
        Zx_View::set_action_var('faqs', $faqs);
    }

}
