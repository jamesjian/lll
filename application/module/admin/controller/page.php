<?php

namespace App\Module\Admin\Controller;

use \App\Model\Page as Model_Page;
use \App\Model\Pagecategory as Model_Pagecategory;
use \App\Transaction\Page as Transaction_Page;
use \Zx\View\View as Zx_View;
use \Zx\Test\Test;

class Page extends Base {

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'page/';
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 1;

            if ($title <> '') {
                $arr = array('title' => $title, 'content' => $content, 'cat_id' => $cat_id);
                if (Transaction_Page::create_page($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . ADMIN_HTML_ROOT . 'page/retrieve/1/title/ASC');
        } else {
            $cats = Model_Pagecategory::get_all_cats();
            //                 \Zx\Test\Test::object_log('$cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            Zx_View::set_view_file($this->view_path . 'create.php');
            Zx_View::set_action_var('cats', $cats);
        }
    }

    public function delete() {
        $id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        if ($id > 0) {
            Transaction_Page::delete_page($id);
        } else {
            Zx_Message::set_error_message('无效记录。');
        }
        Transaction_Html::goto_previous_admin_page();
    }

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
                if (isset($_POST['cat_id']))
                    $arr['cat_id'] = intval($_POST['cat_id']);
                if (Transaction_Page::update_page($id, $arr)) {
                    $success = true;
                }
            }
        } else {
            $id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            if ($id > 0) {
                $page = Model_Page::get_one($id);
                $cats = Model_Pagecategory::get_all_cats();
                \Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);
                Zx_View::set_view_file($this->view_path . 'update.php');
                Zx_View::set_action_var('page', $page);
                Zx_View::set_action_var('cats', $cats);
            } else {
                Zx_Message::set_error_message('无效记录。');
                Transaction_Html::goto_previous_admin_page();
            }
        }
    }

    /**
      /page/orderby/direction
     */
    public function retrieve() {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Page');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $page_list = Model_Page::get_pages_by_page_num($current_page, $order_by, $direction);
        $num_of_page = Model_Page::get_num_of_pages();  //page table stores pages
        $num_of_pages = ceil($num_of_page / NUM_OF_RECORDS_IN_ADMIN_PAGE); //pagination
        Zx_View::set_view_file($this->view_path . 'retrieve.php');
        Zx_View::set_action_var('page_list', $page_list);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

}
