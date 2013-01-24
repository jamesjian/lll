<?php
namespace App\Module\Front\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View as Zx_View;
use \Zx\Message\Message as Zx_Message;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Ad as Transaction_Ad;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Ad as Model_Ad;
use \App\Model\Answer as Model_Answer;
use \App\Model\Tag as Model_Tag;

/**
 * ad pages use ad tags in the right column
 * they're different from question page
 */
class Ad extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/ad/';
        $this->list_page =  FRONT_HTML_ROOT . 'ad/all/';
        $popular_tags = Model_Tag::get_most_popular_ad_tags();
        $latest_ads = Model_Ad::get_latest_ads();        
        Zx_View::set_template_file($this->template_path . 'template_ad_tags_ads.php');
        Zx_View::set_template_var('popular_tags', $popular_tags);
        Zx_View::set_template_var('latest_ads', $latest_ads);        
    }

    
    /*     * one ad
     * /front/ad/content/id/slug-url  the page is for pages of answers of this ad
     * use url rather than id in the query string
     */

    public function content() {
        $ad_id1 = isset($this->params[0]) ?  intval($this->params[0]) : 0; //it's an id1
        $ad = Model_Ad::get_one($ad_id);
        //\Zx\Test\Test::object_log('$ad', $ad, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($ad) {
        $ad_id = $ad['id'];
            $home_url = HTML_ROOT;
            Transaction_Html::remember_current_page();  //after reply this ad, return back to this page
            Transaction_Html::set_title($ad['title']);
            Transaction_Html::set_keyword($ad['title'] . str_replace('#',',', $ad['tnames']));
            Transaction_Html::set_description($ad['title']);
            Model_Ad::increase_num_of_views($ad_id);
            
            Zx_View::set_view_file($this->view_path . 'one_ad.php');
            $answers = Model_Answer::get_active_answers_by_ad_id_and_page_num($ad_id, $current_page_num);
            $num_of_answers = Model_Answer::get_num_of_active_answers_by_ad_id($ad_id);
            //$related_ads = Model_Ad::get_10_active_related_ads($ad_id);
            $related_ads = array();
            $latest_ads = array();
            Zx_View::set_action_var('ad', $ad);
            Zx_View::set_action_var('answers', $answers);
            Zx_View::set_action_var('num_of_answers', $num_of_answers);
            Zx_View::set_action_var('related_ads', $related_ads);
            Zx_View::set_action_var('latest10', $latest_ads);
        } else {
            //if no ad, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

    /**
     * front/ad/3, 3 is page number
     */
    /*
    public function all() {
            $current_page = (isset($params[0])) ? intval($params[0]) : 1;  //default page 1
            $order_by = 'date_created';
            $direction = 'DESC';
            $ads = Model_Ad::get_active_ads_by_page_num($current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$ads', $ads, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_ads = Model_Ad::get_num_of_active_ads();
            $num_of_pages = ceil($num_of_ads / NUM_OF_ITEMS_IN_ONE_PAGE);
            Zx_View::set_view_file($this->view_path . 'ad_list.php');
            Zx_View::set_action_var('ads', $ads);
            Zx_View::set_action_var('order_by', $order_by);
            Zx_View::set_action_var('direction', $direction);
            Zx_View::set_action_var('current_page', $current_page);
            Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }
    */
    /**
      retrieve ads under a user
      front/ad/retrieve_by_uid/id/page/3/, 3 is page number
     */
    public function user() {
        $uid = (isset($this->params[0])) ? $this->params[0] : 0;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($uid != 0 && $user = Model_User::get_one($uid)) {
            $home_url = HTML_ROOT;
            //$tag_url = FRONT_HTML_ROOT . 'ad/tag/' . $tag['id']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $category_url,  $tag['name']);
            //$cat = Model_Adcategory::get_one($cat_id);
            Transaction_Html::set_title($tag['name']);
            Transaction_Html::set_keyword($tag['name']);
            Transaction_Html::set_description($tag['name']);
            $order_by = 'score';
            $direction = 'DESC';
            $ads = Model_Ad::get_active_ads_by_uid_and_page_num($uid, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$ads', $ads, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_ads = Model_Ad::get_num_of_active_ads_by_uid($uid);
            $num_of_pages = ceil($num_of_ads / NUM_OF_ITEMS_IN_ONE_PAGE);
            Zx_View::set_view_file($this->view_path . 'ad_list_by_uid.php');
            Zx_View::set_action_var('user', $user);
            Zx_View::set_action_var('ads', $ads);
            //View::set_action_var('order_by', $order_by);
            //View::set_action_var('direction', $direction);
            Zx_View::set_action_var('current_page', $current_page);
            Zx_View::set_action_var('num_of_pages', $num_of_pages);
        } else {
            //if invalid category
            // \Zx\Test\Test::object_log('$cat_title', 'no', __FILE__, __LINE__, __CLASS__, __METHOD__);

            Transaction_Html::goto_ad_home_page();
        }
    }
    /**
      retrieve ads under a user
      front/ad/tag/id/page/3/, id is tag id, 3 is page number
     */
    public function tag() {
        $tag_id = (isset($this->params[0])) ? $this->params[0] : 0;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($tag_id != 0 && $tag = Model_Tag::get_one($tag_id)) {
            $home_url = HTML_ROOT;
            $tag_url = FRONT_HTML_ROOT . 'ad/tag/' . $tag['id']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $tag_url,  $tag['name']);
            //$cat = Model_Adcategory::get_one($cat_id);
            Transaction_Html::set_title($tag['name']);
            Transaction_Html::set_keyword($tag['name']);
            Transaction_Html::set_description($tag['name']);
            $order_by = 'score';
            $direction = 'DESC';
            $where = '1';
            $ads = Model_Ad::get_active_ads_by_tag_id_and_page_num($tag_id, $where, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$ads', $ads, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_ads = Model_Ad::get_num_of_active_ads_by_tag_id($tag_id);
            $num_of_pages = ceil($num_of_ads / NUM_OF_ITEMS_IN_ONE_PAGE);
            Zx_View::set_view_file($this->view_path . 'tag_list.php');
            Zx_View::set_action_var('tag', $tag);
            Zx_View::set_action_var('ads', $ads);
            //View::set_action_var('order_by', $order_by);
            //View::set_action_var('direction', $direction);
            Zx_View::set_action_var('current_page', $current_page);
            Zx_View::set_action_var('num_of_pages', $num_of_pages);
        } else {
            //if invalid category
            // \Zx\Test\Test::object_log('$cat_title', 'no', __FILE__, __LINE__, __CLASS__, __METHOD__);

            Transaction_Html::goto_home_page();
        }
    }
    /**
      retrieve ads under a user
      front/ad/retrieve_by_uid/id/page/3/, 3 is page number
     */
    public function region() {
        $tag_id = (isset($this->params[0])) ? $this->params[0] : 0;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        if ($tag_id != 0 && $tag = Model_Tag::get_one($tag_id)) {
            $home_url = HTML_ROOT;
            //$tag_url = FRONT_HTML_ROOT . 'ad/tag/' . $tag['id']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $category_url,  $tag['name']);
            //$cat = Model_Adcategory::get_one($cat_id);
            Transaction_Html::set_title($tag['name']);
            Transaction_Html::set_keyword($tag['name']);
            Transaction_Html::set_description($tag['name']);
            $order_by = 'date_created';
            $direction = 'DESC';
            $ads = Model_Ad::get_active_ads_by_tag_id_and_page_num($tag_id, $current_page, $order_by, $direction);
            //\Zx\Test\Test::object_log('$ads', $ads, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $num_of_ads = Model_Ad::get_num_of_active_ads_by_tat_id($tag_id);
            $num_of_pages = ceil($num_of_ads / NUM_OF_ITEMS_IN_ONE_PAGE);
            Zx_View::set_view_file($this->view_path . 'ad_list_by_tag_id.php');
            Zx_View::set_action_var('tag', $tag);
            Zx_View::set_action_var('ads', $ads);
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
      ad/latest/3, 3 is page number, if missing, 1 is default page number
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
        $ads = Model_Ad::get_active_ads_by_page_num($current_page, $order_by, $direction);
        $num_of_ads = Model_Ad::get_num_of_active_ads();
        $num_of_pages = ceil($num_of_ads / NUM_OF_ITEMS_IN_ONE_PAGE);
        Zx_View::set_view_file($this->view_path . 'retrieve_latest.php');
        Zx_View::set_action_var('ads', $ads);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
      ad/hottest/3, 3 is page number, if missing, 1 is default page number
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
        $ads = Model_Ad::get_active_ads_by_page_num($current_page, $order_by, $direction);
        $num_of_ads = Model_Ad::get_num_of_active_ads();
        $num_of_pages = ceil($num_of_ads / NUM_OF_ITEMS_IN_ONE_PAGE);
        Zx_View::set_view_file($this->view_path . 'retrieve_hottest.php');
        Zx_View::set_action_var('ads', $ads);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }
 
    
}
