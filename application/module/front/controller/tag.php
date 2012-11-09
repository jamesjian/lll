<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Tag as Model_Tag;

/**
 * homepage: /=>/front/tag/latest/page/1
 * latest: /front/tag/latest/page/3
 * most popular:/front/tag/most_popular/page/3
 * tag under category: /front/tagcategory/retrieve/$category_id_3/category_name.php
 * one: /front/tag/content/$id/$tag_url
 * keyword: /front/tag/keyword/$keyword_3
 */
class Tag extends Front {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/tag/';
        $this->list_page =  FRONT_HTML_ROOT . 'tag/retrieve/1';
        parent::init();
    }

    /*     * one tag
     * /front/tag/content/niba
     * use url rather than id in the query string
     */

    public function content() {
        $tag_url = $this->params[0]; //it's url rather than an id

        $tag = Model_Tag::get_one_by_url($tag_url);
        //\Zx\Test\Test::object_log('$tag', $tag, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($tag) {
            
            $tag_id = $tag['id'];
            $home_url = HTML_ROOT;
            $category_url = FRONT_HTML_ROOT . 'tag/category/' . $tag['cat_name']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $category_url,  $tag['cat_name']);
            Transaction_Session::set_breadcrumb(2, Route::$url,  $tag['title']);
            Transaction_Html::set_title($tag['title']);
            Transaction_Html::set_keyword($tag['keyword'] . ',' . $tag['keyword_en']);
            Transaction_Html::set_description($tag['title']. ' ' . $tag['title_en']);
            Model_Tag::increase_rank($tag_id);

            View::set_view_file($this->view_path . 'one_tag.php');
            $relate_tags = Model_Tag::get_10_active_related_tags($tag_id);
            View::set_action_var('tag', $tag);
            View::set_action_var('related_tags', $relate_tags);
        } else {
            //if no tag, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/tag/keyword/$keyword/page/3, 3 is page number
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
            $tags = Model_Tag::get_active_tags_by_keyword_and_page_num($keyword, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$tags', $tags, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_tags = Model_Tag::get_num_of_active_tags_by_keyword($keyword);
            $num_of_pages = ceil($num_of_tags / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_keyword.php');
            View::set_action_var('keyword', $keyword);
            View::set_action_var('tags', $tags);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        }
    }

    /**
      retrieve tags under a category
      front/tag/category/auzhoubaoxian/page/3, 5 is cat id, 3 is page number
      $params[0] = auzhoubaoxian, $params[1] = 'page', $params[2] = 3;
     */
    public function category() {
        $cat_title = (isset($this->params[0])) ? $this->params[0] : '';
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($cat_title != '' && $cat = Model_Tagcategory::exist_cat_title($cat_title)) {
            $home_url = HTML_ROOT;
            $category_url = FRONT_HTML_ROOT . 'tag/category/' . $cat['title']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $category_url,  $cat['title']);
            //$cat = Model_Tagcategory::get_one($cat_id);
            Transaction_Html::set_title($cat['title']);
            Transaction_Html::set_keyword($cat['keyword'] . ',' . $cat['keyword_en']);
            Transaction_Html::set_description($cat['title'] . ' ' . $cat['title_en']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $tags = Model_Tag::get_active_tags_by_cat_id_and_page_num($cat['id'], $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$tags', $tags, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_tags = Model_Tag::get_num_of_active_tags_by_cat_id($cat['id']);
            $num_of_pages = ceil($num_of_tags / NUM_OF_ITEMS_IN_ONE_PAGE);
            View::set_view_file($this->view_path . 'retrieve_by_cat_id.php');
            View::set_action_var('cat', $cat);
            View::set_action_var('tags', $tags);
            View::set_action_var('order_by', $order_by);
            View::set_action_var('direction', $direction);
            View::set_action_var('current_page', $current_page);
            View::set_action_var('num_of_pages', $num_of_pages);
        } else {
            //if invalid category
            // \Zx\Test\Test::object_log('$cat_title', 'no', __FILE__, __LINE__, __CLASS__, __METHOD__);

            Transaction_Html::goto_home_page();
        }
    }
    public function search() {
        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            $link = $this->list_page .'/'. trim($_POST['search']);
        } else {
            $link = $this->list_page;
        }
        header('Location: ' . $link);
    }
    /**
      tag/retrieve/3/search, 3 is page number, if missing, 1 is default page number
     * search is a search keyword
     * including home page
     */
    public function retrieve() {
        //\Zx\Test\Test::object_log('lob', 'aaaa', __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::set_title('所有问题类别');
        Transaction_Html::set_keyword('问题类别');
        Transaction_Html::set_description('问题类别');
        $current_page = (isset($params[0])) ? intval($params[0]) : 1;
        $search = (isset($params[1])) ? intval($params[1]) : '';
        if ($current_page < 1)
            $current_page = 1;
        if ($search != '') {
            $where = " name LIKE '%$search%'";
        } else {
            $where = '1';
        }        
        $order_by = 'num_of_questions';
        $direction = 'DESC';
        $tags = Model_Tag::get_active_tags_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_tags = Model_Tag::get_num_of_active_tags();
        $num_of_pages = ceil($num_of_tags / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'tag_list.php');
        View::set_action_var('tags', $tags);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      tag/hottest/3, 3 is page number, if missing, 1 is default page number
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
        $tags = Model_Tag::get_active_tags_by_page_num($current_page, $order_by, $direction);
        $num_of_tags = Model_Tag::get_num_of_active_tags();
        $num_of_pages = ceil($num_of_tags / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'retrieve_hottest.php');
        View::set_action_var('tags', $tags);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
