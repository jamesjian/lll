<?php
namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Articlecategory as Model_Articlecategory;
use \App\Transaction\Articlecategory as Transaction_Articlecategory;
use \Zx\View\View;
use \Zx\Test\Test;

class Articlecategory extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/admin/view/articlecategory/';
        $this->list_page = ADMIN_HTML_ROOT . 'articlecategory/retrieve/1/title/ASC/';
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
            $keyword_en = isset($_POST['keyword_en']) ? trim($_POST['keyword_en']) : '';
            $url = isset($_POST['url']) ? trim($_POST['url']) : '';
            $display_order = isset($_POST['display_order']) ? intval($_POST['display_order']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';

            if ($title <> '') {
                $arr = array('title' => $title, 'title_en' => $title_en,
                    'keyword' => $keyword,
                    'keyword_en' => $keyword_en,
                    'url' => $url, 'description' => $description,
                    'status' => $status,);
                if (Transaction_Articlecategory::create_cat($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            $cats = Model_Articlecategory::get_all_cats();
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('cats', $cats);
        }
    }

    public function delete() {
        $id = (isset($this->params[0])) ? intval($this->params[0]) : 0;
        Transaction_Articlecategory::delete_cat($id);
        header('Location: ' . $this->list_page);
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
                if (isset($_POST['title_en']))
                    $arr['title_en'] = trim($_POST['title_en']);
                if (isset($_POST['keyword']))
                    $arr['keyword'] = trim($_POST['keyword']);
                if (isset($_POST['keyword_en']))
                    $arr['keyword_en'] = trim($_POST['keyword_en']);
                if (isset($_POST['url']))
                    $arr['url'] = trim($_POST['url']);
                if (isset($_POST['description']))
                    $arr['description'] = trim($_POST['description']);
                if (isset($_POST['display_order']))
                    $arr['display_order'] = intval($_POST['display_order']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);

                if (Transaction_Articlecategory::update_cat($id, $arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            if (!isset($id)) {
                $id = $this->params[0];
            }
            $cat = Model_Articlecategory::get_one($id);


            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('cat', $cat);
        }
    }

    public function search() {
        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            $link = ADMIN_HTML_ROOT . 'articlecategory/retrieve/1/title/ASC/' . trim($_POST['search']);
        } else {
            $link = ADMIN_HTML_ROOT . 'articlecategory/retrieve/1/title/ASC/';
        }
        header('Location: ' . $link);
    }

    /**
      /page/orderby/direction
     */
    public function retrieve() {
        \App\Transaction\Session::remember_current_admin_page();
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
        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('cat_list', $cat_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
