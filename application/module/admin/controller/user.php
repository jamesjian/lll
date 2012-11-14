<?php
defined('SYSTEM_PATH') or die('No direct script access.');
namespace App\Module\Admin\Controller;

use \App\Model\User as Model_User;
use \App\Transaction\User as Transaction_User;
use \Zx\View\View;
use \Zx\Test\Test;

class User extends Base {

    public $list_page = '';

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/admin/view/user/';
        $this->list_page = ADMIN_HTML_ROOT . 'user/retrieve/1/user_name/ASC/';
        \App\Transaction\Session::set_ck_upload_path('user');
        parent::init();
    }
    /**
     * for ajax
     */
    public function change_user_status() {
        //App_Test::objectLog('pp_product',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        $changed = false;
        if ($valid) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
            $status = isset($_POST['status']) ? trim($_POST['status']) : '';
            if ($user_id > 0 AND ($status == 'enable' OR $status == 'disable')) {
                if (App_Registereduser::change_status($user_id, $status)) {
                    $changed = true;
                }
            }
        }
        $view = View::factory($this->view_path . 'change_status');
        $view->set('changed', $changed);
        $this->ajax_view($view);
    }
    /**
     * for admin to create a user, an answer, user user and answer user in one step
     */
    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
            $password = isset($_POST['password']) ? md5(trim($_POST['password'])) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $rank = isset($_POST['rank']) ? intval($_POST['rank']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($user_name <> '') {
                $arr = array('user_name' => $user_name,
                    'password' => $password,
                    'email' => $email,
                    'rank' => $rank,
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
                if (isset($_POST['user_name']))
                    $arr['user_name'] = trim($_POST['user_name']);
                if (isset($_POST['password']))
                    $arr['password'] = md5(trim($_POST['password']));
                if (isset($_POST['email']))
                    $arr['email'] = trim($_POST['email']);
                if (isset($_POST['rank']))
                    $arr['rank'] = trim($_POST['rank']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_User::update_user($id, $arr)) {
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
            $user = Model_User::get_one($id);
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);

            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('user', $user);
        }
    }

    public function search() {
        if (isset($_POST['search']) && trim($_POST['search']) != '') {
            $link = $this->list_page . trim($_POST['search']);
        } else {
            $link = $this->list_page;
        }
        header('Location: ' . $link);
    }

    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
        \App\Transaction\Session::remember_current_admin_page();
        \App\Transaction\Session::set_admin_current_l1_menu('User');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        if ($search != '') {
            $where = " user_name LIKE '%$search%' OR email LIKE '%$search%'";
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