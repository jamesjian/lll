<?php
namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Message\Message;
use \App\Model\User as Model_User;
use \App\Model\Ad as Model_Ad;
use \App\Transaction\Ad as Transaction_Ad;
use \Zx\View\View;
use \Zx\Test\Test;

class Ad extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/admin/view/ad/';
        $this->list_page = ADMIN_HTML_ROOT . 'ad/retrieve/1/title/ASC/';
        \App\Transaction\Session::set_ck_upload_path('ad');
    }

   
    /**
     * must have user id, title, content and tag
     */
    public function create() {
        $success = false;
        if (isset($_POST['submit']) && 
                isset($_POST['user_id']) && 
                isset($_POST['title']) && 
                isset($_POST['content']) && 
                isset($_POST['tag_names'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $tag_names = isset($_POST['tag_names']) ? trim($_POST['tag_names']) : '';
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 1;
            $rank = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($title <> '') {
                $arr = array('title' => $title,
                    'tag_names' => $tag_names,
                    'content' => $content,
                    'rank' => $rank,
                    'status' => $status,
                    'user_id' => $user_id);
                if (Transaction_Ad::create_ad($arr)) {
                    $success = true;
                }
            }
        } else {
            $user_id = isset($this->params[0]) ? intval($this->params[0]) : 0;
            if (!Model_User::exist_user_id($user_id)){
                Message::set_error_message('无效用户ID。');
                header('Location: ' . $this->list_page);
            } else {
                Message::set_error_message('user id, title, content, tag can not be empty。');
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('user_id', $user_id);
        }
    }

    /** only admin has permission to delete or update the ads */
    public function delete() {
        $id = $this->params[0];
        Transaction_Ad::delete_ad($id);
        header('Location: ' . $this->list_page);
    }

    /**
     * 
     */
    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            \Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['title']))
                    $arr['title'] = trim($_POST['title']);
                if (isset($_POST['content']))
                    $arr['content'] = trim($_POST['content']);
                if (isset($_POST['tag_names']))
                    $arr['tag_names'] = trim($_POST['tag_names']);
                if (isset($_POST['rank']))
                    $arr['rank'] = intval($_POST['rank']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Ad::update_ad($id, $arr)) {
                    $success = true;
                }
            }
        } else {
            $id = $this->params[0];
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            $ad = Model_Ad::get_one($id);
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('ad', $ad);
        }
    }

    public function search() {
        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            $link = $this->list_page . trim($_POST['search']);
        } else {
            $link = $this->list_page;
        }
        header('Location: ' . $link);
    }

    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
        \App\Transaction\Html::remember_current_admin_page();
        \App\Transaction\Session::set_admin_current_l1_menu('Ad');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " title LIKE '%$search%' OR tagnames LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $ad_list = Model_Ad::get_ads_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Ad::get_num_of_ads($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('ad_list', $ad_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('ad_list', $ad_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
     * under one category
      retrieve_by_cat_id/cat_id/page/orderby/direction
     */
    public function retrieve_by_user_id() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_current_l1_menu('Ad');
        $user_id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " title LIKE '%$search%' OR tag_names LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $ad_list = Model_Ad::get_ads_by_user_id_and_page_num($user_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Ad::get_num_of_ads($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('ad_list', $ad_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_by_user_id.php');
        View::set_action_var('user_id', $user_id);
        View::set_action_var('ad_list', $ad_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
