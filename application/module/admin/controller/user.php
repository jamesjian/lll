<?php
namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\User as Model_User;
use \App\Transaction\User as Transaction_User;
use \Zx\View\View;
use \Zx\Test\Test;

class User extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/admin/view/user/';
        $this->list_page = ADMIN_HTML_ROOT . 'user/retrieve/1/uname/ASC/';
        \App\Transaction\Session::set_ck_upload_path('user');
    }

    /**
     * for ajax
     */
    public function change_status() {
        Test::object_log('pp_product', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);

        $changed = false;
        $uid = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
        if ($uid > 0) {
            if (Transaction_User::change_status($uid, $status)) {
                $changed = true;
            }
        }
        View::set_view_file($this->view_path . 'change_status.php');
        View::set_action_var('changed', $changed);
        View::do_not_use_template();
    }

    /**
     * for admin to create a user, an answer, user user and answer user in one step
     */
    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $uname = isset($_POST['uname']) ? trim($_POST['uname']) : '';
            $password = isset($_POST['password']) ? md5(trim($_POST['password'])) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($uname <> '') {
                $arr = array('uname' => $uname,
                    'password' => $password,
                    'email' => $email,
                    'status' => $status,
                );
                if (Transaction_User::create_user($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
        }
    }

    public function delete() {
        $id = $this->params[0];
        Transaction_User::delete_user($id);
        header('Location: ' . $this->list_page);
    }

    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            \Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                //user name cannot be updated at this time, otherwise, will update all user name fields in other tables
                //if (isset($_POST['uname']))
                //    $arr['uname'] = trim($_POST['uname']);
                if (!empty($_POST['password']))
                    $arr['password'] = md5(trim($_POST['password']));
                if (isset($_POST['email']))
                    $arr['email'] = trim($_POST['email']);
                if (isset($_POST['num_of_questions']))
                    $arr['num_of_questions'] = trim($_POST['num_of_questions']);
                if (isset($_POST['num_of_answers']))
                    $arr['num_of_answers'] = trim($_POST['num_of_answers']);
                if (isset($_POST['num_of_ads']))
                    $arr['num_of_ads'] = trim($_POST['num_of_ads']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_User::update_user($id, $arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
        \Zx\Test\Test::object_log('cats3333', $_SESSION, __FILE__, __LINE__, __CLASS__, __METHOD__);
            
            \App\Transaction\Html::goto_previous_admin_page();
        } else {
            if (!isset($id)) {
                $id = $this->params[0];
            }
            $user = Model_User::get_one($id);
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('user', $user);
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
        \Zx\Test\Test::object_log('cats2222', $_SESSION, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        //\App\Transaction\HTML::set_admin_current_l1_menu('User');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " uname LIKE '%$search%' OR email LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $user_list = Model_User::get_users_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_User::get_num_of_users($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('user_list', $user_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('user_list', $user_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

}