<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use \Zx\Model\Article as Model_Article;

class Article extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/article/';
    }

    //one article
    public function about_us() {
        // $sql = "SELECT * FROM session ";
        //$r = Mysql::select_all($sql);
        //echo $_SESSION['pk'];
        $about_us = Model_Article::get_about_us();
        View::set_view_file($this->view_path . 'about_us.php');
        View::set_action_var('about_us', $about_us);
    }

    //one article
    public function term_condition() {
        $term_condition = Model_Article::get_term_condition();
        View::set_view_file($this->view_path . 'term_condition.php');
        View::set_action_var('term_condition', $term_condition);
    }

    //many articles;
    public function faqs() {
        $faqs = Model_Article::get_faqs();
        View::set_view_file($this->view_path . 'faq.php');
        View::set_action_var('faqs', $faqs);
    }

}
