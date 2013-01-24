<?php

namespace App\Module\Front\Controller;

//this is the base class of front classes
use \Zx\Controller\Route;
use \Zx\View\View as Zx_View;
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
         
        Zx_View::set_template_file($this->template_path . 'another_template.php');
        Zx_View::set_template_var('another_template_variable', $anothe_tempalte_variable);
         * 
         */
        $this->template_path = APPLICATION_PATH . 'module/front/view/templates/';
        Zx_View::set_template_file($this->template_path . 'template_question_tags_ads.php');
        Zx_View::set_action_var('popular_tags', $popular_tags);
        Zx_View::set_action_var('latest_ads', $latest_ads);
    }
    public function search() {

        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            \App\Transaction\Html::remember_current_page();
            \App\Transaction\Html::remember_current_search_keyword(trim($_POST['search']));  //keep search keyword
            $link = $this->list_page . trim($_POST['search']);
        } else {
            $keyword = \App\Transaction\Html::get_previous_search_keyword();

            if ($keyword && $keyword != '') {
                $link = $this->list_page . $keyword;
            } else {
                $link = $this->list_page;
            }
        }
        header('Location: ' . $link);
    }
}