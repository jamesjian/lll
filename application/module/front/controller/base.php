<?php

namespace App\Module\Front\Controller;

//this is the base class of front classes
use \Zx\Controller\Route;
use \Zx\View\View;
use \App\Model\Article as Model_Article;
use \App\Model\Articlecategory as Model_Articlecategory;

class Base {

    public $template_path = '';
    public $view_path = '';
    public $params = array();

    public function init() {
        $this->params = Route::get_params();
        //$cat_groups = Model_Catgroup::get_all_groups();
        //$tags = Model_Article::get_all_keywords();
        //$top10 = Model_Article::get_top10();
        //$latest10 = Model_Article::get_latest10();
        //$article_cats = Model_Articlecategory::get_all_active_cats();
        $this->template_path = APPLICATION_PATH . 'module/front/view/templates/';
        View::set_template_file($this->template_path . 'template.php');
        //View::set_template_var('article_cats', $article_cats);
        //View::set_action_var('tags', $tags);
        //View::set_action_var('top10', $top10);
        //View::set_action_var('latest10', $latest10);
    }

}