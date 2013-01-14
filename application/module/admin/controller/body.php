<?php
namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Message\Message as Zx_Message;
use \App\Model\Body as Model_Body;
use \App\Transaction\Body as Transaction_Body;
use \App\Transaction\Html as Transaction_Html;
use \Zx\View\View;
use \Zx\Test\Test;

/**
 * body can be created by body function or question or ad functions
 * body usually can not be deleted or updated
 * except status
 */
class Body extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'body/';
        $this->list_page = ADMIN_HTML_ROOT . 'body/retrieve/1/en/ASC/';
        //\App\Transaction\Session::set_ck_upload_path('body');
    }
    public function create()
    {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit']) &&
                isset($_POST['en']) && !empty($_POST['en'])) {
            $en = trim($_POST['en']);
            $cn = (isset($_POST['cn'])) ? trim($_POST['cn']) : '';
            $cid = (isset($_POST['cid'])) ? intval($_POST['cid']) : 1;

            $arr = array(
                'en' => $en,
                'cn' => $cn,
                'cid' => $cid,
            );
            if (Transaction_Body::create($arr)) {
                $success = true;
            }
        } else {
            Zx_Message::set_error_message('en can not be empty。');
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
     * if status changed to S_DISABLED, remove all body id and name from questions and ads
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
                if (isset($_POST['en']))
                    $arr['en'] = trim($_POST['en']);
                if (isset($_POST['cn']))
                    $arr['cn'] = trim($_POST['cn']);
                if (isset($_POST['cid']))
                    $arr['cid'] = intval($_POST['cid']);
                if (Transaction_Body::update($id, $arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            $id = isset($_POST['id']) ? intval($_POST['id']) : intval($this->params[0]);
            if ($id>0) {
            $body = Model_Body::get_one($id);
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('body', $body);
            } else {
                header('Location: ' . $this->list_page);
            }
        }
    }
    public function delete() {
        $id = $this->params[0];
        Transaction_Body::delete($id);
        header('Location: ' . $this->list_page);
    }

    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Body');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " en LIKE '%$search%' OR cn LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $body_list = Model_Body::get_bodys_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Body::get_num_of_bodys($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('body_list', $body_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('body_list', $body_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
   }
