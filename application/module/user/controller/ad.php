<?php

namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Ad as Model_Ad;
use \App\Transaction\Ad as Transaction_Ad;

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
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/user/view/ad/';
    }
    
    public function adjust_weight()
    {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit']) &&
                isset($_POST['ad_id']) && !empty($_POST['ad_id']) &&
                isset($_POST['weight']) && !empty($_POST['weight'])) {
            $ad_id = intval($_POST['ad_id']);
            $weight = intval($_POST['weight']);

            $arr = array('ad_id' => $ad_id,
                'weight' => $weight,
            );
            if (Transaction_Ad::adjust_weight($arr)) {
                $success = true;
            }
        } else {
            Zx_Message::set_error_message('invalid form。');
            Transaction_Html::goto_previous_user_page();
        }
        if ($success) {
            Transaction_Html::goto_previous_user_page();
        } else {
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        }
    }
    /**
     * only my ads
     * pagination
     */
    public function my_ads() {
        Transaction_Html::remember_user_page();
        $uid = $this->uid;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($params[2])) ? intval($params[2]) : 1;  //default page 1
        $home_url = HTML_ROOT;
        //$tag_url = FRONT_HTML_ROOT . 'ad/tag/' . $tag['id']; 
        $order_by = 'date_created';
        $direction = 'DESC';
        $ads = Model_Ad::get_active_ads_by_uid_and_page_num($uid, $current_page, $order_by, $direction);
        //\Zx\Test\Test::object_log('$ads', $ads, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $num_of_ads = Model_Ad::get_num_of_active_ads_by_uid($uid);
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
                isset($_POST['tnames']) && !empty($_POST['tnames'])
        ) {
            $title = trim($_POST['title']);
            $tnames = trim($_POST['tnames']);
            $score = (isset($_POST['score']))?intval($_POST['score']) : 1; //at least 1
            $content = trim($_POST['content']);

            $arr = array('title' => $title,
                'tnames' => $tnames,
                'score' => $score,
                'content' => $content,
                'uid'=>$this->uid,
            );
            if (Model_User::available_score($this->uid) && Transaction_Ad::create_by_user($arr)) {
                $success = true;
            }
        } else {
            Zx_Message::set_error_message('请完整填写广告标题， 内容和分类。');
        }
        if ($success) {
            Transaction_Html::goto_previous_user_page();
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
                    isset($_POST['tnames']) && !empty($_POST['tnames'])
            ) {
                $id = intval($_POST['id']);
                $title = trim($_POST['title']);
                $tnames = trim($_POST['tnames']);
                $content = trim($_POST['content']);

                $arr = array('title' => $title,
                    'tnames' => $tnames,
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