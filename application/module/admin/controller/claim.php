<?php

namespace App\Module\Admin\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answser;
use \App\Model\Ad as Model_Ad;
use \App\Model\Claim as Model_Claim;
use \App\Model\Claimcategory as Model_Claimcategory;
use \App\Transaction\Claim as Transaction_Claim;
use \Zx\View\View as Zx_View;
use \Zx\Test\Test;

//must have item type (1: question, 2: answer, 3: ad)
class Claim extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'claim/';
        $this->list_page = ADMIN_HTML_ROOT . 'claim/retrieve_by_item_type/1/1/title/ASC/'; //default list question claims, first 1 is item type
        //\App\Transaction\Session::set_ck_upload_path('claim');
    }

    /**
     * usually a claim cannot be deleted, it's for reference in the future
     */
    public function delete() {
        $id = $this->params[0];
        Transaction_Claim::delete_claim($id);
        header('Location: ' . $this->list_page);
    }

    /**
     * only change status
       original status is S_CREATED
     * new status will be S_CORRECT_CLAIM if item is BAD
     * or S_WRONG_CLAIM if item is GOOD
     * change status of item and claim
     * add score to item and user
     */
    public function update() {
            \Zx\Test\Test::object_log('$_POST', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);        
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['status']))
                    $status = intval($_POST['status']);
                if (Transaction_Claim::update_claim($id, $status)) {
                    $success = true;
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

            Zx_View::set_view_file($this->view_path . 'update.php');
            Zx_View::set_action_var('claim', $claim);
            Zx_View::set_action_var('cats', $cats);
            Zx_View::set_action_var('item', $item);
        } else {
            header('Location: ' . $this->list_page);
        }
    }
    public function retrieve()
    {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        //\Zx\Test\Test::object_log('cats2222', $_SESSION, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        //\App\Transaction\HTML::set_admin_current_l1_menu('User');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        $where = '1';
        if ($search != '') {
            //$where = " uname LIKE '%$search%' OR email LIKE '%$search%'";
        } 
        $claim_list = Model_Claim::get_records_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Claim::get_num_of_records($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('user_list', $user_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_View::set_view_file($this->view_path . 'retrieve.php');
        Zx_View::set_action_var('claim_list', $claim_list);
        Zx_View::set_action_var('search', $search);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }
    /** 
     * different types of items are listed seperately
     * under one item_type (question, ad, or answer)
      retrieve_by_item_type/item_type/page/orderby/direction
     */
    public function retrieve_by_item_type() {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Claim');
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

        Zx_View::set_view_file($this->view_path . 'retrieve_by_item_type.php');
        Zx_View::set_action_var('item_type', $item_type);
        Zx_View::set_action_var('claim_list', $claim_list);
        Zx_View::set_action_var('search', $search);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }
    /**
     * for user
     */
    public function retrieve_by_uid()
    {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Claim');
        \App\Transaction\Session::set_admin_current_l2_menu('User');
        $uid = isset($this->params[0]) ? intval($this->params[0]) : 1; 
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " a.content LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $claim_list = Model_Claim::get_claims_by_uid_and_page_num($uid, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Claim::get_num_of_claims_by_uid($uid, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('claim_list', $claim_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_View::set_view_file($this->view_path . 'retrieve_active_by_uid.php');
        Zx_View::set_action_var('uid', $uid);
        Zx_View::set_action_var('claim_list', $claim_list);
        Zx_View::set_action_var('search', $search);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);        
    }
    /**
     * for question
     */
    public function retrieve_by_qid()
    {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Claim');
        \App\Transaction\Session::set_admin_current_l2_menu('Question');
        $qid = isset($this->params[0]) ? intval($this->params[0]) : 1; 
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " a.content LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $claim_list = Model_Claim::get_claims_by_qid_and_page_num($qid, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Claim::get_num_of_claims_by_qid($qid, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('claim_list', $claim_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_View::set_view_file($this->view_path . 'retrieve_active_by_qid.php');
        Zx_View::set_action_var('qid', $qid);
        Zx_View::set_action_var('claim_list', $claim_list);
        Zx_View::set_action_var('search', $search);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);              
    }
    /**
     * for answer
     */
    public function retrieve_by_aid()
    {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Claim');
        \App\Transaction\Session::set_admin_current_l2_menu('Answer');
        $aid = isset($this->params[0]) ? intval($this->params[0]) : 1; 
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " a.content LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $claim_list = Model_Claim::get_claims_by_aid_and_page_num($aid, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Claim::get_num_of_claims_by_aid($aid, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('claim_list', $claim_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_View::set_view_file($this->view_path . 'retrieve_active_by_aid.php');
        Zx_View::set_action_var('aid', $aid);
        Zx_View::set_action_var('claim_list', $claim_list);
        Zx_View::set_action_var('search', $search);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);            
    }
    /**
     * for ad
     */
    public function retrieve_by_ad_id()
    {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Claim');
        \App\Transaction\Session::set_admin_current_l2_menu('Ad');
        $ad_id = isset($this->params[0]) ? intval($this->params[0]) : 1; 
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4] : '';
        if ($search != '') {
            $where = " a.content LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $claim_list = Model_Claim::get_claims_by_ad_id_and_page_num($ad_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Claim::get_num_of_claims_by_ad_id($ad_id, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('claim_list', $claim_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_View::set_view_file($this->view_path . 'retrieve_active_by_ad_id.php');
        Zx_View::set_action_var('ad_id', $ad_id);
        Zx_View::set_action_var('claim_list', $claim_list);
        Zx_View::set_action_var('search', $search);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);            
    }

}
