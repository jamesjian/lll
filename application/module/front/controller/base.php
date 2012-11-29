<?php

namespace App\Module\Front\Controller;

//this is the base class of front classes
use \Zx\Controller\Route;
use \Zx\View\View;
use \App\Model\Tag as Model_Tag;
use \App\Model\Ad as Model_Ad;

class Base {

    public $template_path = '';
    public $view_path = '';
    public $params = array();

    public function init() {
        $this->params = Route::get_params();
        //$cat_groups = Model_Catgroup::get_all_groups();
        $popular_tags = Model_Tag::get_most_popular_question_tags();
        $latest_ads = Model_Ad::get_latest_ads();
        /*main difference between templates is right column of the pages
         tempalte_tags_ads.php is default one, display most popular tags and latest ads 
         in the right column
         if use a different template
         
        View::set_template_file($this->template_path . 'another_template.php');
        View::set_template_var('another_template_variable', $anothe_tempalte_variable);
         * 
         */
        $this->template_path = APPLICATION_PATH . 'module/front/view/templates/';
        View::set_template_file($this->template_path . 'template_tags_ads.php');
        
        View::set_action_var('popular_tags', $popular_tags);
        View::set_action_var('latest_ads', $latest_ads);
    }

}