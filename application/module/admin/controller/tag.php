<?php
namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Message\Message;
use \App\Model\Tag as Model_Tag;
use \App\Transaction\Tag as Transaction_Tag;
use \App\Transaction\Html as Transaction_Html;
use \Zx\View\View;
use \Zx\Test\Test;

/**
 * tag can be created by tag function or question or ad functions
 * tag usually can not be deleted or updated
 * except status
 */
class Tag extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/admin/view/tag/';
        $this->list_page = ADMIN_HTML_ROOT . 'tag/retrieve/1/name/ASC/';
        //\App\Transaction\Session::set_ck_upload_path('tag');
    }
    public function create()
    {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit']) &&
                isset($_POST['name']) && !empty($_POST['name'])) {
            $name = trim($_POST['name']);

            $arr = array('name' => $name,
            );
            if (Transaction_Tag::create($arr)) {
                $success = true;
            }
        } else {
            Zx_Message::set_error_message('name can not be empty。');
        }
        if ($success) {
             Transaction_Html::goto_previous_admin_page();
        } else {
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        }
    }
    /**
     * if status changed to S_DISABLED, remove all tag id and name from questions and ads
     * must be very careful,because it's impossible to be restored.
     * 
     */
    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            //\Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['num_of_questions']))
                    $arr['num_of_questions'] = trim($_POST['num_of_questions']);
                if (isset($_POST['num_of_answers']))
                    $arr['num_of_answers'] = trim($_POST['num_of_answers']);
                if (isset($_POST['num_of_views']))
                    $arr['num_of_views'] = intval($_POST['num_of_views']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Tag::update($id, $arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            $id = isset($_POST['id']) ? intval($_POST['id']) : intval($this->params[0]);
            $tag = Model_Tag::get_one($id);
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('tag', $tag);
        }
    }

    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Tag');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " name LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $tag_list = Model_Tag::get_tags_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Tag::get_num_of_tags($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('tag_list', $tag_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('tag_list', $tag_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    /**
     * from one existing tag to one or multiple existing or non-existing tags
     * tags are seperated by    TNAME_SEPERATOR
     */
    public function transfer()
    {
               $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit']) &&
                isset($_POST['tsource']) && isset($_POST['tdest'])) {
            $tag_source = trim($_POST['tsource']);
            $tag_dest = trim($_POST['tdest']);
            $posted = array('tsource'=>$tsource, 'tdest'=>$tdest);
            if (Transaction_Tag::transfer_tag($tag_source, $tag_dest)) {
                $success = true;
            }
        } else {
            Zx_Message::set_error_message('tag can not be empty。');
        }
        if ($success) {
             Transaction_Html::goto_previous_admin_page();
        } else {
            View::set_view_file($this->view_path . 'transfer.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        }
    }

}
