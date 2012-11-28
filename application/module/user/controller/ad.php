<?php

namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Ad as Model_Ad;

/**

 * homepage: /=>/front/ad/latest/page/1
 * latest: /front/ad/latest/page/3
 * most popular:/front/ad/most_popular/page/3
 * ad under category: /front/adcategory/retrieve/$category_id_3/category_name.php
 * one: /front/ad/content/$id/$ad_url
 * keyword: /front/ad/keyword/$keyword_3
 */
class Ad extends Base {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/user/view/ad/';
        parent::init();
    }

    /**
     * only my ads
     * pagination
     */
    public function my_ads() {
        $user_id = $this->user_id;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        $home_url = HTML_ROOT;
        //$tag_url = FRONT_HTML_ROOT . 'ad/tag/' . $tag['id']; 
        Transaction_Session::set_breadcrumb(0, $home_url, '首页');
        Transaction_Session::set_breadcrumb(1, $category_url, $tag['name']);
        //$cat = Model_Adcategory::get_one($cat_id);
        Transaction_Html::set_title($tag['name']);
        Transaction_Html::set_keyword($tag['name']);
        Transaction_Html::set_description($tag['name']);
        $order_by = 'date_created';
        $direction = 'DESC';
        $ads = Model_Ad::get_active_ads_by_user_id_and_page_num($user_id, $current_page, $order_by, $direction);
        //\Zx\Test\Test::object_log('$ads', $ads, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $num_of_ads = Model_Ad::get_num_of_active_ads_by_user_id($user_id);
        $num_of_pages = ceil($num_of_ads / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'my_ads.php');
        View::set_action_var('user', $this->user);
        View::set_action_var('ads', $ads);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    public function create() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit']) &&
                isset($_POST['title']) && !empty($_POST['title']) &&
                isset($_POST['content']) && !empty($_POST['content']) &&
                isset($_POST['tag_names']) && !empty($_POST['tag_names'])
        ) {
            $title = trim($_POST['title']);
            $tag_names = trim($_POST['tag_names']);
            $content = trim($_POST['content']);

            $arr = array('title' => $title,
                'tag_names' => $tag_names,
                'content' => $content,
            );
            if (Transaction_Ad::create_by_user($arr)) {
                $success = true;
            }
        } else {
            Zx_Message::set_error_message('title, content, tag can not be empty。');
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        }
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_Ad::delete_by_user($id);
        header('Location: ' . $this->list_page);
    }

    public function update() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit'])) {
            if (isset($_POST['id']) && !empty($_POST['id']) &&
                    isset($_POST['title']) && !empty($_POST['title']) &&
                    isset($_POST['content']) && !empty($_POST['content']) &&
                    isset($_POST['tag_names']) && !empty($_POST['tag_names'])
            ) {
                $id = intval($_POST['id']);
                $title = trim($_POST['title']);
                $tag_names = trim($_POST['tag_names']);
                $content = trim($_POST['content']);

                $arr = array('title' => $title,
                    'tag_names' => $tag_names,
                    'content' => $content,
                );
                if (Transaction_Ad::update_by_user($id, $arr)) {
                    //if success
                    header('Location: ' . $this->list_page);
                }
            } else {
                Zx_Message::set_error_message('title, content, tag can not be empty。');
            }
        } else {
            $id = $this->params[0];
        }

        $ad = Model_Ad::get_one($id);
        //must have a valid ad
        if ($ad) {
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('id', $id);
            View::set_action_var('ad', $ad);
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        } else {
            header('Location: ' . $this->list_page);
        }
    }

}