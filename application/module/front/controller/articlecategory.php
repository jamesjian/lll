<?php

namespace App\Module\Front\Controller;

use \Zx\Model\Articlecategory as Model_Articlecategory;
use \Zx\Model\Article as Model_Article;
use \App\Transaction\Html as Transaction_Html;

class Articlecategory extends Base {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/articlecategory/';
        parent::init();
    }

    /**
      show one article category and all articles under this category
      front/articlecategory/retrieve/5/page/3, 5 is cat id, 3 is page number
      $params[0] = 5, $params[1] = 'page', $params[2] = 3;
     */
    public function retrieve() {
        $cat_id = (isset($params[0])) ? intval($params[1]) : 1;
        $page_number = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        $cat = Model_Articlecategory::get_one($cat_id);
        \App\Transaction\Session::set_front_current_l1_menu($cat['title']);
        Transaction_Html::set_title($cat['title']);
        Transaction_Html::set_keyword($cat['keyword'] . ',' . $cat['keyword_en']);
        Transaction_Html::set_description($cat['title']);
        $articles = Model_Article::get_active_articles_by_cat_id_and_page_number($cat_id, $page_number);
        $num_of_articles = Model_Article::get_num_of_active_articles_by_cat_id($cat_id);
        $num_of_pages = ceil($num_of_articles / NUM_OF_BLOGS_IN_CAT_PAGE);
        View::set_view_file($this->view_path . 'show.php');
        View::set_action_var('cat', $cat);
        View::set_action_var('articles', $articles);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
