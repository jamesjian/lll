<?php

namespace App\Module\Admin\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Pagecategory as Model_Pagecategory;
use \App\Transaction\Pagecategory as Transaction_Pagecategory;
use \App\Transaction\Html as Transaction_Html;
use \Zx\View\View as Zx_View;
use \Zx\Test\Test;
use \Zx\Message\Message as Zx_Message;

class Pagecategory extends Base {

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'pagecategory/';
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';

            if ($title <> '') {
                $arr = array('title' => $title, 'description' => $description);
                if (Transaction_Pagecategory::create_cat($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            $cats = Model_Pagecategory::get_all_cats();
            Zx_View::set_view_file($this->view_path . 'create.php');
            Zx_View::set_action_var('cats', $cats);
        }
    }

    public function delete() {
        $id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        if ($id > 0) {
            Transaction_Pagecategory::delete_cat($id);
        } else {
            Zx_Message::set_error_message('无效记录。');
        }
        Transaction_Html::goto_previous_admin_page();
    }

    public function update() {
        $success = false;
        $posted = array();
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            \Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if ($id <> 0) {
                if (isset($_POST['title']))
                    $posted['title'] = trim($_POST['title']);
                if (isset($_POST['description']))
                    $posted['description'] = trim($_POST['description']);
                if (Transaction_Pagecategory::update_cat($id, $posted)) {
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
                $cat = Model_Pagecategory::get_one($id);
                Zx_View::set_view_file($this->view_path . 'update.php');
                Zx_View::set_action_var('cat', $cat);
                Zx_View::set_action_var('posted', $posted);
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
        if (!Transaction_Html::previous_admin_page_is_search_page()) {
            Transaction_Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Page Category');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $cat_list = Model_Pagecategory::get_cats_by_page_num($current_page, $order_by, $direction);
        $num_of_cats = Model_Pagecategory::get_num_of_cats();
        $num_of_pages = ceil($num_of_cats / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        Zx_View::set_view_file($this->view_path . 'retrieve.php');
        Zx_View::set_action_var('cat_list', $cat_list);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

}
