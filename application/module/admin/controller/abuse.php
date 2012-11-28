<?php

namespace App\Module\Admin\Controller;

use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answser;
use \App\Model\Ad as Model_Ad;
use \App\Model\Abuse as Model_Abuse;
use \App\Model\Abusecategory as Model_Abusecategory;
use \App\Transaction\Abuse as Transaction_Abuse;
use \Zx\View\View;
use \Zx\Test\Test;
//must have item type (1: question, 2: answer, 3: ad)
class Abuse extends Base {

    public $list_page = '';
    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/admin/view/abuse/';
        $this->list_page =  ADMIN_HTML_ROOT . 'abuse/retrieve_by_item_type/1/1/title/ASC/'; //default list question abuses, first 1 is item type
        \App\Transaction\Session::set_ck_upload_path('abuse');
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_Abuse::delete_abuse($id);
        header('Location: ' . $this->list_page);
    }

    /**
     * only change status
     */
    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
        
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            //\Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Abuse::update_abuse($id, $arr)) {
                    header('Location: ' . $this->list_page);
                }
            }
        } else {
            $id = isset($this->params[0]) ?  intval($this->params[0]) : 0;
        }
            $abuse = Model_Abuse::get_one($id);
            if ($abuse) {
            $cats = Model_Abusecategory::get_cats(); //an array
            switch ($abuse['item_type']) {
                case '1': 
                    $item = Model_Question::get_one($abuse['item_id']);
                    break;
                case '2': 
                    $item = Model_Answer::get_one($abuse['item_id']);
                    break;
                case '3': 
                    $item = Model_Ad::get_one($abuse['item_id']);
                    break;
            }
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('abuse', $abuse);
            View::set_action_var('cats', $cats);
            View::set_action_var('item', $item);
            } else {
                header('Location: ' . $this->list_page);
            }
    }
    /**
     * must have item type (1: question, 2: answer, 3: ad)
     */
    public function search() {
        $type_id = $this->params[0];
        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            $link = ADMIN_HTML_ROOT . 'abuse/retrieve_by_item_type/' . $type_id . '1/title/ASC/' . trim($_POST['search']);
        } else {
            $link = $this->list_page;
        }
        header('Location: ' . $link);
    }
 
    /**
     * under one item_type (question, ad, or answer)
      retrieve_by_cat_id/cat_id/page/orderby/direction
     */
    public function retrieve_by_item_type() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_current_l1_menu('Abuse');
        $type_id = isset($this->params[0]) ? intval($this->params[0]) :1; //default is question
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4]: '';
        if ($search != '') {
            $where = " a.content LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $abuse_list = Model_Abuse::get_abuses_by_item_type_and_page_num($type_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Abuse::get_num_of_abuses_by_item_type($type_id, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('abuse_list', $abuse_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_active_by_item_type.php');
        View::set_action_var('type_id', $type_id);
        View::set_action_var('abuse_list', $abuse_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
