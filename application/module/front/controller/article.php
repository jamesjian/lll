<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View as Zx_View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Article as Model_Article;
use \App\Model\Articlecategory as Model_Articlecategory;

/**
 * homepage: /=>/front/article/latest/page/1
 * latest: /front/article/latest/page/3
 * most popular:/front/article/most_popular/page/3
 * article under category: /front/articlecategory/retrieve/$category_id_3/category_name.php
 * one: /front/article/content/$id/$article_url
 * keyword: /front/article/keyword/$keyword_3
 */
class Article extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = FRONT_VIEW_PATH . 'article/';
    }

    /*     * one article
     * /front/article/content/niba
     * use url rather than id in the query string
     */

    public function content() {
        $article_url = $this->params[0]; //it's url rather than an id

        $article = Model_Article::get_one_by_url($article_url);
        //\Zx\Test\Test::object_log('$article', $article, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($article) {
            
            $article_id = $article['id'];
            $home_url = HTML_ROOT;
            $category_url = FRONT_HTML_ROOT . 'article/category/' . $article['cat_name']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $category_url,  $article['cat_name']);
            Transaction_Session::set_breadcrumb(2, Route::$url,  $article['title']);
            Transaction_Html::set_title($article['title']);
            Transaction_Html::set_keyword($article['keyword'] . ',' . $article['keyword_en']);
            Transaction_Html::set_description($article['title']. ' ' . $article['title_en']);
            Model_Article::increase_rank($article_id);

            Zx_View::set_view_file($this->view_path . 'one_article.php');
            $relate_articles = Model_Article::get_10_active_related_articles($article_id);
            Zx_View::set_action_var('article', $article);
            Zx_View::set_action_var('related_articles', $relate_articles);
        } else {
            //if no article, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/article/keyword/$keyword/page/3, 3 is page number
     */
    public function keyword() {
        $keyword = (isset($this->params[0])) ? $this->params[0] : '';
        if ($keyword == '') {
            //goto homepage
            Transaction_Html::goto_home_page();
        } else {
            $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
            $order_by = 'rank';
            $direction = 'DESC';
            $articles = Model_Article::get_active_articles_by_keyword_and_page_num($keyword, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$articles', $articles, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_articles = Model_Article::get_num_of_active_articles_by_keyword($keyword);
            $num_of_pages = ceil($num_of_articles / NUM_OF_ITEMS_IN_ONE_PAGE);
            Zx_View::set_view_file($this->view_path . 'retrieve_by_keyword.php');
            Zx_View::set_action_var('keyword', $keyword);
            Zx_View::set_action_var('articles', $articles);
            Zx_View::set_action_var('order_by', $order_by);
            Zx_View::set_action_var('direction', $direction);
            Zx_View::set_action_var('current_page', $current_page);
            Zx_View::set_action_var('num_of_pages', $num_of_pages);
        }
    }

    /**
      retrieve articles under a category
      front/article/category/auzhoubaoxian/page/3, 5 is cat id, 3 is page number
      $params[0] = auzhoubaoxian, $params[1] = 'page', $params[2] = 3;
     */
    public function category() {
        $cat_title = (isset($this->params[0])) ? $this->params[0] : '';
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($cat_title != '' && $cat = Model_Articlecategory::exist_cat_title($cat_title)) {
            $home_url = HTML_ROOT;
            $category_url = FRONT_HTML_ROOT . 'article/category/' . $cat['title']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $category_url,  $cat['title']);
            //$cat = Model_Articlecategory::get_one($cat_id);
            Transaction_Html::set_title($cat['title']);
            Transaction_Html::set_keyword($cat['keyword'] . ',' . $cat['keyword_en']);
            Transaction_Html::set_description($cat['title'] . ' ' . $cat['title_en']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $articles = Model_Article::get_active_articles_by_cat_id_and_page_num($cat['id'], $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$articles', $articles, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_articles = Model_Article::get_num_of_active_articles_by_cat_id($cat['id']);
            $num_of_pages = ceil($num_of_articles / NUM_OF_ITEMS_IN_ONE_PAGE);
            Zx_View::set_view_file($this->view_path . 'retrieve_by_cat_id.php');
            Zx_View::set_action_var('cat', $cat);
            Zx_View::set_action_var('articles', $articles);
            Zx_View::set_action_var('order_by', $order_by);
            Zx_View::set_action_var('direction', $direction);
            Zx_View::set_action_var('current_page', $current_page);
            Zx_View::set_action_var('num_of_pages', $num_of_pages);
        } else {
            //if invalid category
            // \Zx\Test\Test::object_log('$cat_title', 'no', __FILE__, __LINE__, __CLASS__, __METHOD__);

            Transaction_Html::goto_home_page();
        }
    }

    /**
      article/latest/3, 3 is page number, if missing, 1 is default page number
     * including home page
     */
    public function latest() {
        //\Zx\Test\Test::object_log('lob', 'aaaa', __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::set_title('latest');
        Transaction_Html::set_keyword('latest');
        Transaction_Html::set_description('latest');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $articles = Model_Article::get_active_articles_by_page_num($current_page, $order_by, $direction);
        $num_of_articles = Model_Article::get_num_of_active_articles();
        $num_of_pages = ceil($num_of_articles / NUM_OF_ITEMS_IN_ONE_PAGE);
        Zx_View::set_view_file($this->view_path . 'retrieve_latest.php');
        Zx_View::set_action_var('articles', $articles);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      article/hottest/3, 3 is page number, if missing, 1 is default page number
     */
    public function hottest() {
        Transaction_Html::set_title('hottest');
        Transaction_Html::set_keyword('hottest');
        Transaction_Html::set_description('hottest');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        if ($current_page < 1)
            $current_page = 1;
        $order_by = 'rank';
        $direction = 'DESC';
        $articles = Model_Article::get_active_articles_by_page_num($current_page, $order_by, $direction);
        $num_of_articles = Model_Article::get_num_of_active_articles();
        $num_of_pages = ceil($num_of_articles / NUM_OF_ITEMS_IN_ONE_PAGE);
        Zx_View::set_view_file($this->view_path . 'retrieve_hottest.php');
        Zx_View::set_action_var('articles', $articles);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

}
