<?php

namespace App\Module\Admin\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Articlecategory as Model_Articlecategory;
use \App\Transaction\Articlecategory as Transaction_Articlecategory;
use \Zx\View\View as Zx_View;
use App\Transaction\Html as Transaction_Html;
use \Zx\Test\Test;

class Articlecategory extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'articlecategory/';
        $this->list_page = ADMIN_HTML_ROOT . 'articlecategory/retrieve/1/title/ASC/';
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($title <> '') {
                $arr = array('title' => $title,
                    'description' => $description,
                    'status' => $status,);
                if (Transaction_Articlecategory::create_cat($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            $cats = Model_Articlecategory::get_all_cats();
            Zx_View::set_view_file($this->view_path . 'create.php');
            Zx_View::set_action_var('cats', $cats);
        }
    }

    public function delete() {
        $id = (isset($this->params[0])) ? intval($this->params[0]) : 0;
        Transaction_Articlecategory::delete_cat($id);
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
                if (isset($_POST['description']))
                    $arr['description'] = trim($_POST['description']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Articlecategory::update_cat($id, $arr)) {
                    $success = true;
                }
            }
        } else {
            $id = (isset($this->params[0])) ?  intval($this->params[0]) : 0;
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            $cat = Model_Articlecategory::get_one($id);
            if ($cat) {
                Zx_View::set_view_file($this->view_path . 'update.php');
                Zx_View::set_action_var('cat', $cat);
            } else {
                Transaction_Html::goto_previous_admin_page();
            }
        }
    }

    /**
     * no real pagination, page is only for convenience
      /page/orderby/direction
     */
    public function retrieve() {
        \App\Transaction\Session::set_admin_current_l1_menu('Article Category');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " title LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $cat_list = Model_Articlecategory::get_cats_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Articlecategory::get_num_of_cats($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        Zx_View::set_view_file($this->view_path . 'retrieve.php');
        Zx_View::set_action_var('cat_list', $cat_list);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

}
