<?php
namespace App\Module\Front\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

/**
 * the original status of an item is "not sure"
 * when an item (question, answer, ad) is claimed, the status will be "claimed"
 * and cannot be claimed again
 * if confirm it's valid, change status to "valid", * if updated, the status will be "not sure" again
 * if confirm it's invalid, it will be "invalid" and not be displayed
 * 
 */
use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Question as Transaction_Question;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Claim as Model_Claim;
use \App\Model\Claimcategory as Model_Claimcategory;
use \App\Transaction\Claim as Transaction_Claim;
use App\Transaction\User as Transaction_User;

/**
  only user can claim and vote
 */
class Claim extends Base {

    public function init() {
        parent::init();
        $this->view_path = FRONT_VIEW_PATH . 'vote/';
        //$this->list_page = FRONT_HTML_ROOT . 'vote/all/';
    }

    /**
     * claim_popup_form/1/5: first 1 is item type, 1: question, 2: answer, 3:ad,  second 5 is item id
     * only loggedin user can claim
     * the popup form provide fields to fill account info
     * 
     */
    public function claim_popup_form() {
        $item_type = $this->params[0];
        $item_id = $this->params[1];        
        if (Transaction_User::user_has_loggedin()) {
            $loggedin = true;
        } else {
            $loggedin = false;
        }
        $cats = Model_Claimcategory::get_cats();
        View::set_view_file($this->view_path . 'claim_popup_form.php');
        View::set_action_var('item_type', $item_type);
        View::set_action_var('item_id', $item_id);
        View::set_action_var('loggedin', $loggedin);
        View::set_action_var('cats', $cats);
        View::do_not_use_template(); //ajax
    }

    /**
     *anybody can claim without login
     * create/1/5: first 1 is item type, 1: question, 2: answer, 3:ad,  second 5 is item id
     * 
     */
    public function create() {
        $loggedin = false;
        $success = false;
        $posted = array();

        //App_Test::objectLog('Session',  App_Session::get_all_session(), __FILE__, __LINE__, __CLASS__, __METHOD__);        
            if (isset($_POST['cat_id']) && !empty($_POST['cat_id'])){
                $cat_id = intval($_POST['cat_id']);
                $item_type = $this->params[0];
                $item_id = $this->params[1];
                if (Transaction_Claim::claim($item_type, $item_id, $cat_id)) {
                     Zx_Message::set_error_message("感谢您的举报， 我们会尽快核实并作出处理。");

                } else {
                    //Transaction_Claim provide error message
                   //Zx_Message::set_error_message("您提交的信息有误， 请重新操作。");
                }
            } 
        View::set_view_file($this->view_path . 'claim_result');
    }

}
