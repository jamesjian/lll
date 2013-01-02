<?php

namespace App\Module\Front\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Question as Transaction_Question;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Claim as Model_Claim;
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

    public function claim_popup_form() {
        if (Transaction_User::user_has_loggedin()) {
            $loggedin = true;
        } else {
            $loggedin = false;
        }
        View::set_view_file($this->view_path . 'claim_form_popup.php');
        View::set_action_var('loggedin', $loggedin);
        View::do_not_use_template(); //ajax
    }

    /**
     * check logged in first, 
     * if not logged in, verify user name and password,
     * then claim
     * ajax
     */
    public function create() {
        $loggedin = false;
        $success = false;
        $posted = array();

        //App_Test::objectLog('Session',  App_Session::get_all_session(), __FILE__, __LINE__, __CLASS__, __METHOD__);        
        if (!Transaction_User::user_has_loggedin()) {
            //todo verify user name and password
            //$verifiey = verify     .....
            if ($verified) {
                $loggedin = true;
            }
        } else {
            $loggedin=true;
        }
        if ($loggedin) {
            
            if (isset($_POST['uname']) && !empty($_POST['uname']) &&
                    isset($_POST['password']) && !empty($_POST['password'])
            ) {
                $uname = $_POST['uname'];
                $password = $_POST['password'];

                if (Transaction_User::verify_user($uname, $password)) {
                    $message = "您已登陆成功。";
                } else {
                    //if not valid, display form again
                    //maybe disabled by administrator
                    $message = "。";
                }
            } else {
                $errors = array();
            }

        } else {
            //nothing to display
        }
        View::set_view_file($this->view_path . 'claim_result_pop');
        View::set_action_var('message', $message);
        View::do_not_use_template(); //ajax               
    }

}
