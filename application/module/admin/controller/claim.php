<?php

namespace App\Module\Admin\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answser;
use \App\Model\Ad as Model_Ad;
use \App\Model\Claim as Model_Claim;
use \App\Model\Claimcategory as Model_Claimcategory;
use \App\Transaction\Claim as Transaction_Claim;
use \Zx\View\View;
use \Zx\Test\Test;

//must have item type (1: question, 2: answer, 3: ad)
class Claim extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/admin/view/claim/';
        $this->list_page = ADMIN_HTML_ROOT . 'claim/retrieve_by_item_type/1/1/title/ASC/'; //default list question claims, first 1 is item type
        \App\Transaction\Session::set_ck_upload_path('claim');
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_Claim::delete_claim($id);
        header('Location: ' . $this->list_page);
    }

    /**
     * only change status
       original status is "not confirmed"
     * new status will be "confirmed" if item is bad
     * or "cancelled" if item is good
     * 
     * change status of item and claim
     * add score to item and user
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
                if (Transaction_Claim::update_claim($id, $status)) {
                    header('Location: ' . $this->list_page);
                }
            }
        } else {
            $id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        }
        $claim = Model_Claim::get_one($id);
        if ($claim) {
            $cats = Model_Claimcategory::get_cats(); //an array
            switch ($claim['item_type']) {
                case '1':
                    $item = Model_Question::get_one($claim['item_id']);
                    break;
                case '2':
                    $item = Model_Answer::get_one($claim['item_id']);
                    break;
                case '3':
                    $item = Model_Ad::get_one($claim['item_id']);
                    break;
            }
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('claim', $claim);
            View::set_action_var('cats', $cats);
            View::set_action_var('item', $item);
        } else {
            header('Location: ' . $this->list_page);
        }
    }

    /**
     *     
     * different types of items are listed seperately
     * under one item_type (question, ad, or answer)
      retrieve_by_item_type/item_type/page/orderby/direction
     */
    public function retrieve_by_item_type() {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_current_l1_menu('Claim');
        $item_type = isset($this->params[0]) ? intval($this->params[0]) : 1; //default is question
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " a.content LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $claim_list = Model_Claim::get_claims_by_item_type_and_page_num($item_type, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Claim::get_num_of_claims_by_item_type($item_type, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('claim_list', $claim_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve_active_by_item_type.php');
        View::set_action_var('type_id', $type_id);
        View::set_action_var('claim_list', $claim_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}
