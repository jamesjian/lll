<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * for admin/user
 * 

 */
class Controller_Admin_User extends Controller_Admin_Appcontroller {

    public function before() {
        //App_Test::objectLog('$_POST','1111', __FILE__, __LINE__, __CLASS__, __METHOD__);
        parent::before();
        $this->view_path = ADMIN . "user/";
        $this->list_page = ADMIN . "user/list_registered_user/page/1";
        $this->template->title = "User --  " . TITLE;
        $action = $this->request->action();
        if ($action != 'login' &&
                $action != 'logout' &&
                $action != 'no_permission') {
            $valid = App_Staff::has_admin_permission('manage user');
            if (!$valid) {
                $this->request->redirect($this->list_page);
            }
        }
    }
public function action_aindex() {
        App_Session::set_breadcrumb(1, Request::detect_uri(), USER);
        App_Session::set_menu(USER);
        App_Session::set_submenu('');
        App_Http::remember_this_admin_page();
        $view = View::factory($this->view_path . 'aindex');
        $this->view($view);
    }

    /**
     */
    public function action_alist() {
        $current_page = $this->request->param('page_id', 1);
        $current_keyword = $this->request->param('keyword', 'date_created');
        $current_direction = $this->request->param('direction', 'DESC');
        $current_search_keyword = $this->request->param('search_keyword', '');
        $rows_per_page = 10;
        $row_count = $rows_per_page;
        $offset = ($current_page - 1) * $row_count;
        if ($current_search_keyword <> '') {
//only one word at this moment
            $where = " user_name LIKE '%$current_search_keyword%'";
        } else {
            $where = 1;
        }
        $results = Model_User::get_records($offset, $row_count, $current_keyword, $current_direction, $where);
        $total_number = Model_User::get_num_of_records($where);
        $link = ADMINHTMLROOT . 'user/alist/';
        $total_pages = ceil($total_number / $rows_per_page);
        $view = View::factory($this->view_path . 'alist');
        $view->set('results', $results);
        $view->set('link', $link);
        $view->set('current_page', $current_page);
        $view->set('total_pages', $total_pages);
        $view->set('current_keyword', $current_keyword);
        $view->set('current_direction', $current_direction);
        $view->set('current_search_keyword', $current_search_keyword);
        $this->ajax_view($view);
    }

    public function action_aget_create_form() {
        $cat_list = Model_Category::get_cat_list_by_channel_id(4); //contain cat1 and cat2 info
        $state_list = Model_Region::get_state_only_arr();
        $current_state = 'NSW';
        $city_list = Model_Region::get_city_arr_by_state($current_state);
        $suburb_list = Model_Region::get_suburbs_by_state($current_state);
        $view = View::factory($this->view_path . 'acreate_form');
        $view->set('cat_list', $cat_list);
        $view->set('state_list', $state_list);
        $view->set('city_list', $city_list);
        $view->set('suburb_list', $suburb_list);
        $this->ajax_view($view);
    }

    public function action_aget_update_form() {
        $message = '';
        $thread_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($thread_id > 0 && Model_Requirement::exist_by_id($thread_id)) {
            $thread = Model_Requirement::get_record($thread_id);
        } else {
            $thread = false;
            $message = "记录不存在。";
        }
        $cat_list = Model_Category::get_cat_list_by_channel_id(4); //contain cat1 and cat2 info
        $existing_cat2 = Model_Category::get_record($thread->cat2_id);
        $state_list = Model_Region::get_state_only_arr();
        $current_state = $thread->state;
        if (!in_array($current_state, $state_list)) $current_state = 'NSW';
        $city_list = Model_Region::get_city_arr_by_state($current_state);
        $suburb_list = Model_Region::get_suburbs_by_state($current_state);
        $view = View::factory($this->view_path . 'aupdate_form');
        $view->set('cat_list', $cat_list);
        $view->set('existing_cat2', $existing_cat2);  //don't use cat2, it's used in view file
        $view->set('state_list', $state_list);
        $view->set('city_list', $city_list);
        $view->set('suburb_list', $suburb_list);            //$view->set('exist_current_orderitem', $exist_current_orderitem);
        $view->set('thread', $thread);
        $view->set('message', $message);
        $this->ajax_view($view);
    }

    public function action_aget_delete_form() {
        $message = '';
        $thread_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($thread_id > 0 && Model_Requirement::exist_by_id($thread_id)) {
            $thread = Model_Requirement::get_record($thread_id);
        } else {
            $thread = false;
            $message = "记录不存在。";
        }
        $view = View::factory($this->view_path . 'adelete_form');
        $view->set('thread', $thread);
        $view->set('thread_id', $thread_id);
        $view->set('message', $message);
        $this->ajax_view($view);
    }

    public function action_adelete() {
        $message = '';
        $result = false;
        $thread_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($thread_id > 0 && Model_Requirement::exist_by_id($thread_id)) {
            App_Requirement::delete_record($thread_id);
            $result = true;
            $message = "记录已成功删除.";
        } else {
            $message = "记录不存在.";
        }
        $view = View::factory($this->view_path . 'aresult');
        $view->set('result', $result);
        $view->set('message', $message);
        $this->ajax_view($view);
    }

    public function action_adetail() {
        $message = '';
        $result = false;
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id > 0 && Model_User::exist_user_id($id)) {
            $user = Model_User::get_user($id);
            $result = true;
            $message = "";
        } else {
            $message = "记录不存在.";
        }
        $view = View::factory($this->view_path . 'adetail');
        $view->set('result', $result);
        $view->set('message', $message);
        $view->set('user', $user);
        $this->ajax_view($view);
    }

    public function action_acreate() {
        //App_Test::objectLog('$_POST', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $result = false;
        $message = '';
        $post = Validation::factory($_POST);
        $post->rule('title', 'not_empty')
                ->rule('user_id', 'not_empty')     //must have valid user id
                ->rule('description', 'not_empty')
                ->rule('cat2_ids', 'not_empty');
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        if ($post->check() && $user = Model_User::get_record($user_id)) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $company_id = isset($_POST['company_id']) ? intval($_POST['company_id']) : 0;  //can be empty
            $cat2_id = isset($_POST['cat2_ids']) ? trim($_POST['cat2_ids']) : '';  //only one category
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
            $abstract = isset($_POST['abstract']) ? trim($_POST['abstract']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $num_of_days = isset($_POST['num_of_days']) ? intval($_POST['num_of_days']) : 1;
            $size = isset($_POST['size']) ? intval($_POST['size']) : 1;   //default small project            
            $status = 1; //1 is active
            $user_name = $user->user_name;
            //App_Test::objectLog('$user_name', $user, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $posted = array(
                'title' => $title,
                'user_id' => $user_id,
                'cat2_id' => $cat2_id,
                //'cat2_ids' => $cat2_id,
                'keyword' => $keyword,
                'abstract' => $abstract,
                'description' => $description,
                'num_of_days' => $num_of_days,
                'size' => $size,                
                'user_id' => $user_id, //current user id
                'user_name' => $user_name, //current user name
                'status' => $status,
            );
            if (!Model_Company::company_belong_to_user($company_id, $user_id)) {
                //company must belong to this user
                $company_id = 0;
            }
            $posted['company_id'] = $company_id;
            if ($company_id == 0) {
                //if no company,  check address info and contact info
                $address = isset($_POST['address']) ? trim($_POST['address']) : '';
                $state = isset($_POST['state']) ? trim($_POST['state']) : 'NSW';
                $city_id = isset($_POST['city']) ? intval($_POST['city']) : 0;
                $suburb_id = isset($_POST['suburb']) ? intval($_POST['suburb']) : 0;
                $email = isset($_POST['email']) ? trim($_POST['email']) : '';
                $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
                $fax = isset($_POST['fax']) ? trim($_POST['fax']) : '';
                $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
                $website = isset($_POST['website']) ? trim($_POST['website']) : '';
                $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
                $posted1 = array(
                    'address' => $address,
                    'state' => $state,
                    'city_id' => $city_id,
                    'suburb_id' => $suburb_id,
                    'contact' => $contact,
                    'email' => $email,
                    'phone' => $phone,
                    'fax' => $fax,
                    'mobile' => $mobile,
                    'website' => $website,
                );
                $posted = array_merge($posted, $posted1);
            }
//App_Test::objectLog('$posted', $posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if ($company_id == 0) {
                if (App_Requirement::create_record_by_admin_without_cid($posted)) {
                    $result = true;
                    $message = "记录已添加.";
                } else {
                    $message = "记录添加失败.";
                }
            } else {
                if (App_Requirement::create_record_by_admin_with_cid($posted)) {
                    $result = true;
                    $message = "记录已添加.";
                } else {
                    $message = "记录添加失败.";
                }
            }
        } else {
            $message = "用户不存在或提交内容有误.";
        }
        $view = View::factory($this->view_path . 'aresult');
        $view->set('result', $result);
        $view->set('message', $message);
        $this->ajax_view($view);
    }

    /**
     * cannot change user id and company id
     */
    public function action_aupdate() {
        App_Test::objectLog('$_POST', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        App_Test::objectLog('$_FILE', $_FILES, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $result = false;
        $message = '';
        $post = Validation::factory($_POST);
        $post->rule('title', 'not_empty')
                ->rule('description', 'not_empty')
                ->rule('thread_id', 'not_empty')
                ->rule('cat2_ids', 'not_empty');
        //App_Test::objectLog('$_POST',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $thread_id = intval($_POST['thread_id']);
        if (isset($_POST['title']))
            $posted['title'] = trim($_POST['title']);

        if (isset($_POST['cat2_ids'])) {
            $posted['cat2_ids'] = $posted['cat_id'] = $posted['cat2_id'] = intval($_POST['cat2_ids']);     //only one category
        }

        if (isset($_POST['keyword']))
            $posted['keyword'] = trim($_POST['keyword']);
        if (isset($_POST['abstract']))
            $posted['abstract'] = trim($_POST['abstract']);
        if (isset($_POST['description']))
            $posted['description'] = trim($_POST['description']);
            if (isset($_POST['num_of_days']))
                $posted['num_of_days'] = intval($_POST['num_of_days']);
            if (isset($_POST['size']))
                $posted['size'] = intval($_POST['size']);        
        if ($post->check()) {
            $thread = Model_Requirement::get_record($thread_id);
            if ($thread->company_id == 0) {
                //if no company, have different address and contact info
                if (isset($_POST['address']))
                    $posted['address'] = trim($_POST['address']);
                if (isset($_POST['state']))
                    $posted['state'] = trim($_POST['state']);
                if (isset($_POST['city']))
                    $posted['city_id'] = intval($_POST['city']);
                if (isset($_POST['suburb']))
                    $posted['suburb_id'] = intval($_POST['suburb']);
                if (isset($_POST['phone']))
                    $posted['phone'] = trim($_POST['phone']);
                if (isset($_POST['mobile']))
                    $posted['mobile'] = trim($_POST['mobile']);
                if (isset($_POST['fax']))
                    $posted['fax'] = trim($_POST['fax']);
                if (isset($_POST['email']))
                    $posted['email'] = trim($_POST['email']);
                if (isset($_POST['contact']))
                    $posted['contact'] = trim($_POST['contact']);
                if (isset($_POST['website']))
                    $posted['website'] = trim($_POST['website']);
                if (App_Requirement::update_record_by_admin_without_cid($thread_id, $posted)) {
                    $result = true;
                    $message = "记录已更新.";
                } else {
                    $message = "记录更新失败.";
                }
            } else {

                if (App_Requirement::update_record_by_admin_with_cid($thread_id, $posted)) {
                    $result = true;
                    $message = "记录已更新.";
                } else {
                    $message = "记录更新失败.";
                }
            }
        } else {
            $message = "提交内容有误.";
        }

        $view = View::factory($this->view_path . 'aresult');
        $view->set('result', $result);
        $view->set('message', $message);
        $this->ajax_view($view);
    }    
    /**
     * only password for myself
     */
    public function action_change_my_password() {
        $success = false;
        $errors = array();
        $posted = array();
        if (isset($_POST['password1'])) {
            $post = Validation::factory($_POST);
            $post->rule('old_password', 'not_empty')
                    ->rule('new_password1', 'not_empty')
                    ->rule('new_password1', 'matches', array(':validation', 'password1', 'password2'));

            // App_Test::objectLog('$posted',$posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if ($post->check()) {
                $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : '';
                $new_password = isset($_POST['new_password1']) ? trim($_POST['new_password1']) : '';
                $posted = array(
                    'old_password' => $old_password,
                    'new_password' => $new_password,
                );
                if (App_Staff::change_admin_password($posted)) {
                    $success = true;
                    App_Http::goto_admin_home_page(); // back to my account page
                }
            } else {
                $errors = $post->errors('user');
            }
        }
        if (!$success) {
            $errors = $post->errors('user');
            $view = View::factory($this->view_path . 'change_password');
            $view->set('posted', $posted);
            $view->set('errors', $errors);
            $view->set('sess', App_Session::set_new_form_session());
            $this->view($view);
        }
    }

    /**
     * ajax for get user name/user id by user id/user name
     */
    public function action_get_user_name_ajax() {

        $is_user_id = true;
        $id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';

        if (is_numeric($id)) {
            //it's user id
            $user = Model_User::get_record($id);
        } else {
            $user = Model_User::get_user_by_user_name($id);
            $is_user_id = false;
        }

        $view = View::factory($this->view_path . 'get_user_name_ajax');
        $view->set('is_user_id', $is_user_id);
        $view->set('user', $user);
        $this->ajax_view($view);
    }

    /**
     * only list admin user
     */
    public function action_list_admin_user() {
        //table
        App_Session::set_breadcrumb(1, Request::detect_uri(), USER);
        //App_Message::set_breadcrumb(1, HTMLLINKROOT . 'venue/list_venue', 'Venue list');
        App_Session::set_menu(USER);
        App_Session::set_submenu('ADMIN');
        App_Http::remember_this_admin_page();
        $row_count = Model_Systemparameter::get_items_per_page();
        $page_number = $this->request->param('page_id', 1);
        $keyword = $this->request->param('keyword', 'user_name');
        $direction = $this->request->param('direction', 'ASC');

        $offset = ($page_number - 1) * $row_count;
        $where = '';
        if (isset($_GET['user_name']) && trim($_GET['user_name']) != '') {
            $where .= ' user_name LIKE "%' . $_GET['user_name'] . '%" AND ';
        }
        if (isset($_GET['email']) && trim($_GET['email']) != '') {
            $where .= ' email LIKE "%' . $_GET['email'] . '%" AND ';
        }
        $where = substr($where, 0, -4); //remove the last 'AND '
        if (trim($where) == '')
            $where = '1 '; //if no search               
        $results = Model_User::get_admin_users($offset, $row_count, $keyword, $direction, $where);
        $total_number = Model_User::get_num_of_admin_users($where);
        $route_params_arr = array('keyword' => $keyword, 'direction' => $direction);
        $page_links = parent::page_links($total_number, $row_count, $route_params_arr);

        $view = View::factory($this->view_path . 'admin_user_list');
        $view->set('items_per_page', $row_count);   //for systemparameter/set_items_per_page_form
        $view->set('results', $results);
        $view->set('page_links', $page_links);
        $this->set_list_params($view, $page_number, $keyword, $direction);
        $this->view($view);
    }

    /**
     * only list registered user
     */
    public function action_list_registered_user() {
        //table
        //App_Session::get_all_session();
        //App_Test::objectLog('$page_number','$page_number', __FILE__, __LINE__, __CLASS__, __METHOD__);
        App_Session::set_breadcrumb(1, Request::detect_uri(), USER);
        //App_Message::set_breadcrumb(1, HTMLLINKROOT . 'venue/list_venue', 'Venue list');
        App_Session::set_menu(USER);
        App_Session::set_submenu('注册用户');
        App_Http::remember_this_admin_page();
        $row_count = Model_Systemparameter::get_items_per_page();
        $page_number = $this->request->param('page_id', 1);
        $keyword = $this->request->param('keyword', 'date_created');
        $direction = $this->request->param('direction', 'DESC');
        //App_Test::objectLog('$page_number',$page_number, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $offset = ($page_number - 1) * $row_count;
        $where = '';
        if (isset($_GET['user_name']) && trim($_GET['user_name']) != '') {
            $where .= ' user_name LIKE "%' . $_GET['user_name'] . '%" AND ';
        }
        if (isset($_GET['email']) && trim($_GET['email']) != '') {
            $where .= ' email LIKE "%' . $_GET['email'] . '%" AND ';
        }
        $where = substr($where, 0, -4); //remove the last 'AND '
        if (trim($where) == '')
            $where = '1 '; //if no search               
        $results = Model_User::get_registered_users($offset, $row_count, $keyword, $direction, $where);
        $total_number = Model_User::get_num_of_registered_users($where);
        $route_params_arr = array('keyword' => $keyword, 'direction' => $direction);
        $page_links = parent::page_links($total_number, $row_count, $route_params_arr);
        $view = View::factory($this->view_path . 'registered_user_list');
        $view->set('items_per_page', $row_count);   //for systemparameter/set_items_per_page_form
        $view->set('results', $results);
        $view->set('page_links', $page_links);
        $this->set_list_params($view, $page_number, $keyword, $direction);
        $this->view($view);
    }

    /**
     * for ajax 0: inactive, 1: active, 2: registered
     * only change registered user status 
     * check admin_index.js to use correct id/status 
     */
    public function action_change_status() {
        //App_Test::objectLog('pp_product',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $changed = false;
        $user_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';  //don't use intval here
        if ($user_id > 0 AND ($status == '0' OR $status == '1' OR $status == '2')) {
            if (App_User::change_registered_user_status($user_id, $status)) {
                $changed = true;
            }
        }
        $view = View::factory($this->view_path . 'change_status');
        $view->set('changed', $changed);
        $this->ajax_view($view);
    }

    public function action_reset_password() {
        $user_id = $this->request->param('id');
        //Model_User::disable_user($user_id);
        App_User::reset_password($user_id);
        App_Http::goto_previous_admin_page();
    }

    /**
     */
    public function action_delete_registered_user() {
        $user_id = $this->request->param('id');
        //Model_User::disable_user($user_id);
        App_User::delete_registered_user($user_id);
        App_Http::goto_previous_admin_page();
    }

    public function action_view_ajax() {
        if (App_User::has_admin_permission('retrieve user')) {
            $user_id = $this->request->param('id');
            $user = Model_Buser::get_user($user_id);

            $view = View::factory($this->view_path . 'user_view_ajax');
            $view->set('user', $user);
            $this->ajax_view($view);
        }
    }

    /**
     * ajax for add company form
     */
    public function action_exist_user_name_ajax() {

        $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
        $message = '';
        if ($user_name != '') {
            if (Model_Buser::exist_user($user_name)) {
                $message = "User exists";
            } else {
                $message = '';
            }
        }

        $view = View::factory($this->view_path . 'user_name_exist_ajax');
        $view->set('message', $message);
        $this->ajax_view($view);
    }

    /**
      create registered user, password will be generated automatically
     */
    public function action_create_registered_user() {//form
        //App_Test::objectLog('$_POST',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        App_Session::set_breadcrumb(2, Request::detect_uri(), 'create');
        App_Session::set_menu('USER');
        App_Session::set_submenu('注册用户');
        $success = false;
        $posted = array();
        $errors = NULL;

        if (isset($_POST['submit'])) {

            $post = Validation::factory($_POST);
            $post->rule('user_name', 'not_empty')
                    ->rule('email', 'email')
                    ->rule('first_name', 'alpha')
                    ->rule('last_name', 'alpha');
            $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $group_id = 3; //registered user group is 3
            $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
            $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $status = isset($_POST['status']) ? trim($_POST['status']) : '';

            $posted = array(
                'user_name' => $user_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'group_id' => $group_id,
                'email' => $email,
                'phone' => $phone,
                'status' => $status,
            );
            if ($post->check()) {
          //      App_Test::objectLog('$posted',$posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
                //$_SESSION['ADMINSESSID'] is defined in auth controller
                if (App_User::create_registered_user($posted)) {
                    $success = true;
                }
            } else {
                $errors = $post->errors('user');
            }
        }
        if ($success) {
            $this->request->redirect($this->list_page);
        } else {
            $view = View::factory($this->view_path . 'user_create');
            $view->set('posted', $posted);
            $view->set('errors', $errors);
            $this->view($view);
        }
    }

    /**
     * password will be generated in different way
     */
    public function action_update_registered_user() {
        //form
        App_Session::set_breadcrumb(2, Request::detect_uri(), 'create');
        App_Session::set_menu('USER');
        App_Session::set_submenu('注册用户');
        $success = false;
        $posted = array();
        $errors = NULL;
        if (isset($_POST['user_id'])) {
            $post = Validation::factory($_POST);
            $post->rule('user_name', 'not_empty')
                    ->rule('email', 'email')
                    ->rule('user_id', 'not_empty');
            $user_id = intval($_POST['user_id']);
            if (isset($_POST['user_name']))
                $posted['user_name'] = trim($_POST['user_name']);
            if (isset($_POST['group_id']))
                $posted['group_id'] = intval($_POST['group_id']);
            if (isset($_POST['email']))
                $posted['email'] = trim($_POST['email']);
            if (isset($_POST['first_name']))
                $posted['first_name'] = trim($_POST['first_name']);
            if (isset($_POST['last_name']))
                $posted['last_name'] = trim($_POST['last_name']);
            if (isset($_POST['phone']))
                $posted['phone'] = trim($_POST['phone']);
            if (isset($_POST['status']))
                $posted['status'] = trim($_POST['status']);
            //App_Test::objectLog('$posted',$posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if ($post->check()) {
                //App_Test::objectLog('111','111', __FILE__, __LINE__, __CLASS__, __METHOD__);
                if (App_User::update_registered_user($user_id, $posted)) {
                    $success = true;
                }
            } else {
                $errors = $post->errors('category');
            }
        } else {
            $user_id = intval($this->request->param('id', 0));
        }
        if ($success) {
            $this->request->redirect($this->list_page);
        } else {
            if (!empty($user_id)) {
                $user = Model_User::get_record($user_id);
                $view = View::factory($this->view_path . 'user_update');
                $view->set('user', $user);
                $view->set('errors', $errors);
                $this->view($view);
            } else {
                $this->request->redirect($this->list_page);
            }
        }
    }

    public function reset_password() {
        $user_id = $this->request->param('id');
        App_User::reset_password($user_id);
    }

    /**
     * setting page for all users
     * view file is in template folder
     */
    public function action_setting() {
        //App_Test::objectLog('settingt',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (App_User::is_admin() || App_User::is_developer()) {
            App_Session::set_menu('SETTING');
            $user_id = App_User::get_user_id();
            $user = Model_User::get_record($user_id);
            if (isset($_POST['submit_password']) OR isset($_POST['submit_profile'])) {
                if (isset($_POST['submit_password'])) {
                    if (!empty($_POST['password1']) AND !empty($_POST['password2']) AND trim($_POST['password1']) == trim($_POST['password2'])) {
                        $arr['password'] = $_POST['password1'];
                        if (Model_User::update_record($user_id, $arr)) {
                            App_Session::set_success_message("Password is modified successfully.");
                        }
                    } else {
                        App_Session::set_error_message("Password cannot be empty, 2 passwords you entered must be same");
                    }
                }
                if (isset($_POST['submit_profile'])) {
                    $arr = array();
                    if (isset($_POST['first_name']))
                        $arr['first_name'] = trim($_POST['first_name']);
                    if (isset($_POST['last_name']))
                        $arr['last_name'] = trim($_POST['last_name']);
                    if (isset($_POST['phone']))
                        $arr['phone'] = trim($_POST['phone']);
                    if (isset($_POST['email']))
                        $arr['email'] = trim($_POST['email']);
                    Model_User::update_record($user_id, $arr);
                }
                App_Http::goto_previous_admin_page();
            } else {
                $user = Model_User::get_record($user_id);
                $view = View::factory($this->view_path . 'user_setting');
                $view->set('user', $user);
                //$view->set('', $page_links);
                //$view->set('pagination', $pagination);
                $this->view($view);
            }
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    public function action_logout() {
        if (App_User::admin_has_loggedin()) {
            $user_id = App_User::get_admin_user_id();
            App_User::admin_logout();
        }
        App_Http::redirect_to_admin_login_page();
    }

    /**
     * this is only a form, not a complete page
     */
    public function action_login_form_hmvc() {
        $suburbs = Model_Suburb::get_all_suburbs();
        $view = View::factory($this->view_path . 'login');
        $this->hmvc_view($view);
    }

    public function action_login() {
        $success = false;
        $errors = array();
        $posted = array();

        //App_Test::objectLog('$_POST',  $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);        
        if (App_Staff::admin_has_loggedin()) {
            App_Http::goto_admin_home_page();
        } else {
            if (isset($_POST['sess']) AND $_POST['sess'] == App_Session::get_new_form_session()) {
                $post = Validation::factory($_POST);
                $post->rule('user_name', 'not_empty')
                        ->rule('password', 'not_empty');
                if ($post->check()) {
                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    if (App_Staff::valid_admin_user($user_name, $password)) {
                        $success = true;
                        App_Http::goto_admin_home_page();
                    } else {
                        App_Session::set_error_message("user name or password is wrong!");
                    }
                } else {
                    $errors = $post->errors('user');
                }
            }//else csrf
            if (!$success) {
                $view = View::factory($this->view_path . 'login');
                $view->set('menu_file', '');   //before login successfully, no menu
                $view->set('sess', App_Session::set_new_form_session());
                App_Session::clear_breadcrumb();
                $this->view($view);
            }
        }
    }

    public function action_no_permission() {
        $view = View::factory($this->view_path . 'no_permission');
        $view->set('homepage', 'home/index');
        $view->set('logout', 'user/logout');
        $this->view($view);
    }

    public function action_404() {
        $view = View::factory('templates/404');

        $view->set('homepage', 'home/index');
        $view->set('logout', 'user/logout');

        $this->view($view);
    }

}

class Controller_XXX_Companyuser extends Controller_Admin_Appcontroller {

    public function before() {
        //App_Test::objectLog('$_POST','1111', __FILE__, __LINE__, __CLASS__, __METHOD__);
        parent::before();
        $this->view_path = ADMIN . "companyuser/";
        $this->list_page = ADMIN . "companyuser/list_user/page/1";
        $this->template->title = "User --  " . TITLE;
    }

    public function action_test() {
        echo 'bbbb';
        $this->template->content = 'ccc';
    }

    /**
     * list users who has at least one company
     */
    public function action_list_company_user() {
        
    }

    /**
     * list users who has no company 
     */
    public function action_list_none_company_user() {
        
    }

    /**
     * list all users
     */
    public function action_list() {
        //table
        if (App_Adminuser::has_permission('manage user')) {
            App_Session::set_breadcrumb(1, Request::detect_uri(), USER);
            //App_Message::set_breadcrumb(1, HTMLLINKROOT . 'venue/list_venue', 'Venue list');
            App_Session::set_menu(USER);
            App_Session::set_submenu(COMPANY_USER);
            App_Http::remember_this_admin_page();
            $row_count = Model_Systemparameter::get_items_per_page();
            $page_number = $this->request->param('page_id', 1);
            $keyword = $this->request->param('keyword', 'user_name');
            $direction = $this->request->param('direction', 'ASC');

            $offset = ($page_number - 1) * $row_count;
            $results = Model_Companyuser::get_users($offset, $row_count, $keyword, $direction);
            $total_number = Model_Companyuser::get_num_of_users();
            $number_of_companies_arr = array();
            foreach ($results as $user) {
                $number_of_companies_arr[$user->id] = Model_Company::get_number_of_companies_by_user_id($user->id);
            }
            $page_links = parent::page_links($total_number, $row_count);

            $view = View::factory($this->view_path . 'user_list');
            $view->set('items_per_page', $row_count);   //for systemparameter/set_items_per_page_form
            $view->set('number_of_companies_arr', $number_of_companies_arr);
            $view->set('results', $results);
            $view->set('page_links', $page_links);
            $this->set_list_params($view, $page_number, $keyword, $direction);
            $this->view($view);
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    /*

     * user: company is 1:m
     * show user and all related companies/ads or other information
     */

    public function action_show_user() {
        $valid = App_Adminuser::has_permission('manage user');
        if ($valid) {
            $user_id = $this->request->param('id');
            $user = Model_Companyuser::get_record($user_id);
            $companies = Model_Companyuser::get_companies_by_user_id($user_id);
            $view = View::factory($this->view_path . 'user_information');
            $view->set('user', $user);
            $view->set('companies', $companies);
            $this->view($view);
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    /**
     * for ajax
     */
    public function action_change_user_status() {
        //App_Test::objectLog('pp_product',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $valid = App_Adminuser::has_permission('manage company');
        $changed = false;
        if ($valid) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
            $status = isset($_POST['status']) ? trim($_POST['status']) : '';
            if ($user_id > 0 AND ($status == 'enable' OR $status == 'disable')) {
                if (App_Companyuser::change_status($user_id, $status)) {
                    $changed = true;
                }
            }
        }
        $view = View::factory($this->view_path . 'change_status');
        $view->set('changed', $changed);
        $this->ajax_view($view);
    }

    /**
     */
    public function action_delete_user() {
        if (App_Adminuser::has_permission('manage user')) {
            $user_id = $this->request->param('id');
            //Model_User::disable_user($user_id);
            App_Companyuser::delete_user($user_id);
            App_Http::goto_previous_admin_page();
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    public function action_view_user_ajax() {
        if (App_Adminuser::has_permission('retrieve user')) {
            $user_id = $this->request->param('id');
            $user = Model_Companyuser::get_user($user_id);

            $view = View::factory($this->view_path . 'user_view_ajax');
            $view->set('user', $user);
            $this->ajax_view($view);
        }
    }

    /**
     * ajax for add company form
     */
    public function action_exist_user_name_ajax() {

        $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
        $message = '';
        if ($user_name != '') {
            if (Model_Companyuser::exist_user($user_name)) {
                $message = "User exists";
            } else {
                $message = '';
            }
        }

        $view = View::factory($this->view_path . 'user_name_exist_ajax');
        $view->set('message', $message);
        $this->ajax_view($view);
    }

    /**
     * user:company 1:1
     */
    public function action_create_user() {//form
        if (App_Adminuser::has_permission('manage company')) {
            App_Session::set_menu('USER');
            App_Session::set_submenu(USER);
            $post = Validation::factory($_POST);
            $post->rule('user_name', 'not_empty')
                    ->rule('email', 'email')
                    ->rule('phone', 'not_empty')
                    ->rule('first_name', 'alpha')
                    ->rule('last_name', 'alpha')
                    ->rule('password', 'not_empty');
            $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
            $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $status = isset($_POST['status']) ? trim($_POST['status']) : '';

            $posted = array(
                'user_name' => $user_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'status' => $status,
            );
            if ($post->check()) {
                //$_SESSION['ADMINSESSID'] is defined in auth controller
                App_Companyuser::create_user($posted);
                App_Http::goto_previous_admin_page();
            } else {
                $errors = $post->errors('user');
                $view = View::factory($this->view_path . 'user_create');
                $view->set('posted', $posted);
                $view->set('companies', $companies);
                $view->set('errors', $errors);
                $this->view($view);
            }
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    /**
     * password will be generated in different way
     */
    public function action_update_user() {
        //form
        //App_Test::objectLog('$_POST',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (App_Adminuser::has_permission('manage user')) {
            $post = Validation::factory($_POST);
            $post->rule('user_name', 'not_empty')
                    ->rule('email', 'email')
                    ->rule('user_id', 'not_empty');
            if (isset($_POST['user_name']))
                $posted['user_name'] = trim($_POST['user_name']);
            if (isset($_POST['email']))
                $posted['email'] = trim($_POST['email']);
            if (isset($_POST['first_name']))
                $posted['first_name'] = trim($_POST['first_name']);
            if (isset($_POST['last_name']))
                $posted['last_name'] = trim($_POST['last_name']);
            if (isset($_POST['phone']))
                $posted['phone'] = trim($_POST['phone']);
            if (isset($_POST['status']))
                $posted['status'] = trim($_POST['status']);
            //App_Test::objectLog('$posted',$posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if ($post->check()) {
                //App_Test::objectLog('111','111', __FILE__, __LINE__, __CLASS__, __METHOD__);
                $user_id = intval($_POST['user_id']);
                App_Companyuser::update_user($user_id, $posted);
                App_Http::goto_previous_admin_page();
            } else {
                $errors = $post->errors('user');
                if (isset($_POST['user_id'])) {
                    $user_id = $_POST['user_id'];
                } else {
                    $user_id = $this->request->param('id');
                }
                if (!empty($user_id)) {
                    $user = App_Companyuser::get_record($user_id);
                    $view = View::factory($this->view_path . 'user_update');
                    $view->set('user', $user);
                    $view->set('errors', $errors);
                    $this->view($view);
                }
            }
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    public function reset_password() {
        if (App_Adminuser::has_permission('manage user')) {
            $user_id = $this->request->param('id');
            App_Companyuser::reset_password($user_id);
        }
    }

    /**
     * search form
     */
    public function action_search_user() {
        //table
        if (App_Adminuser::has_permission('retrieve user')) {
            $page_number = $this->request->param('page_id', 1);
            $row_count = 4;
            $keyword = $this->request->param('keyword', 'user_id');
            $direction = $this->request->param('direction', 'ASC');
            $search_keywords = (isset($_GET['search_keywords'])) ? trim($_GET['search_keywords']) :
                    $this->request->param('search_keywords', '');
            $offset = ($page_number - 1) * $row_count;
            $results = Model_Companyuser::get_users($offset, $row_count, $keyword, $direction, $search_keywords);
            $total_number = Model_Companyuser::get_num_of_users($search_keywords);

            $pagination = Pagination::factory(array(
                        'current_page' => array('source' => 'route', 'key' => 'page_id'),
                        'total_items' => $total_number,
                        'items_per_page' => $row_count,
                        'auto_hide' => false,
                    ));

            $page_links = $pagination->render();

            $view = View::factory($this->view_path . 'user_list');
            $view->set('action', $this->request->action);
            if ($search_keywords != '')
                $view->set('search_keywords', $search_keywords);
            $view->set('results', $results);
            $view->set('page_links', $page_links);
            $this->set_list_params($view, $page_number, $keyword, $direction);
            $this->view($view);
        }
    }

}

class Controller_XXX_Registereduser extends Controller_Admin_Appcontroller {

    public function before() {
        parent::before();
        $this->view_path = ADMIN . "registereduser/";
        $this->list_page = ADMIN . "registereduser/list_user/page/1";
        $this->template->title = "User --  " . TITLE;
    }

    public function action_test() {
        echo 'bbbb';
        $this->template->content = 'ccc';
    }

    /**
     */
    public function action_list_user() {
        //table
        if (App_Adminuser::has_permission('manage user')) {
            App_Session::set_breadcrumb(1, Request::detect_uri(), USER);
            //App_Message::set_breadcrumb(1, HTMLLINKROOT . 'venue/list_venue', 'Venue list');
            App_Session::set_menu(USER);
            App_Session::set_submenu(COMPANY_USER);
            App_Http::remember_this_admin_page();
            $row_count = Model_Systemparameter::get_items_per_page();
            $page_number = $this->request->param('page_id', 1);
            $keyword = $this->request->param('keyword', 'user_name');
            $direction = $this->request->param('direction', 'ASC');

            $offset = ($page_number - 1) * $row_count;
            $results = Model_Registereduser::get_users($offset, $row_count, $keyword, $direction);
            $total_number = Model_Registereduser::get_num_of_users();
            $number_of_requirements_arr = array();
            foreach ($results as $user) {
                $number_of_requirements_arr[$user->id] = 0;
            }
            $page_links = parent::page_links($total_number, $row_count);

            $view = View::factory($this->view_path . 'user_list');
            $view->set('items_per_page', $row_count);   //for systemparameter/set_items_per_page_form
            $view->set('results', $results);
            $view->set('page_links', $page_links);
            $view->set('number_of_requirements_arr', $number_of_requirements_arr);
            $this->set_list_params($view, $page_number, $keyword, $direction);
            $this->view($view);
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    /**
     * user and related information
     *      */
    public function action_show_registered_user() {
        $valid = App_Adminuser::has_permission('manage user');
        if ($valid) {
            $user_id = $this->request->param('id');
            $user = Model_Registereduser::get_record($user_id);
            $view = View::factory($this->view_path . 'user_information');
            $view->set('user', $user);
            $this->view($view);
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    /**
     * for ajax
     */
    public function action_change_user_status() {
        //App_Test::objectLog('pp_product',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $valid = App_Adminuser::has_permission('manage company');
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
     */
    public function action_delete_user() {
        if (App_Adminuser::has_permission('manage user')) {
            $user_id = $this->request->param('id');
            App_Registereduser::delete_user($user_id);
            App_Http::goto_previous_admin_page();
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    public function action_view_user_ajax() {
        if (App_Adminuser::has_permission('retrieve user')) {
            $user_id = $this->request->param('id');
            $user = Model_Registereduser::get_user($user_id);

            $view = View::factory($this->view_path . 'user_view_ajax');
            $view->set('user', $user);
            $this->ajax_view($view);
        }
    }

    /**
     * ajax for add company form
     */
    public function action_exist_user_name_ajax() {

        $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
        $message = '';
        if ($user_name != '') {
            if (Model_Registereduser::exist_user($user_name)) {
                $message = "User exists";
            } else {
                $message = '';
            }
        }

        $view = View::factory($this->view_path . 'user_name_exist_ajax');
        $view->set('message', $message);
        $this->ajax_view($view);
    }

    /**
     * user:company 1:1
     */
    public function action_create_user() {//form
        if (App_Adminuser::has_permission('manage company')) {
            App_Session::set_menu('USER');
            App_Session::set_submenu(USER);
            $post = Validation::factory($_POST);
            $post->rule('user_name', 'not_empty')
                    ->rule('email', 'email')
                    ->rule('phone', 'not_empty')
                    ->rule('first_name', 'alpha')
                    ->rule('last_name', 'alpha')
                    ->rule('password', 'not_empty');
            $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 3; //default is 3, can be 4
            $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
            $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $status = isset($_POST['status']) ? trim($_POST['status']) : '';

            $posted = array(
                'user_name' => $user_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'group_id' => $group_id,
                'email' => $email,
                'phone' => $phone,
                'status' => $status,
            );
            if ($post->check()) {
                //$_SESSION['ADMINSESSID'] is defined in auth controller
                App_Registereduser::create_user($posted);
                App_Http::goto_previous_admin_page();
            } else {
                $errors = $post->errors('user');
                $view = View::factory($this->view_path . 'user_create');
                $view->set('posted', $posted);
                $view->set('companies', $companies);
                $view->set('errors', $errors);
                $this->view($view);
            }
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    /**
     * password will be generated in different way
     */
    public function action_update_user() {
        //form
        //App_Test::objectLog('$_POST',$_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (App_Adminuser::has_permission('manage user')) {
            $post = Validation::factory($_POST);
            $post->rule('user_name', 'not_empty')
                    ->rule('email', 'email')
                    ->rule('user_id', 'not_empty');
            if (isset($_POST['user_name']))
                $posted['user_name'] = trim($_POST['user_name']);
            if (isset($_POST['email']))
                $posted['email'] = trim($_POST['email']);
            if (isset($_POST['first_name']))
                $posted['first_name'] = trim($_POST['first_name']);
            if (isset($_POST['last_name']))
                $posted['last_name'] = trim($_POST['last_name']);
            if (isset($_POST['phone']))
                $posted['phone'] = trim($_POST['phone']);
            if (isset($_POST['group_id']))
                $posted['group_id'] = trim($_POST['group_id']);  //may be 3 or 4
            if (isset($_POST['status']))
                $posted['status'] = trim($_POST['status']);
            //App_Test::objectLog('$posted',$posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if ($post->check()) {
                //App_Test::objectLog('111','111', __FILE__, __LINE__, __CLASS__, __METHOD__);
                $user_id = intval($_POST['user_id']);
                App_Registereduser::update_user($user_id, $posted);
                App_Http::goto_previous_admin_page();
            } else {
                $errors = $post->errors('user');
                if (isset($_POST['user_id'])) {
                    $user_id = $_POST['user_id'];
                } else {
                    $user_id = $this->request->param('id');
                }
                if (!empty($user_id)) {
                    $user = Model_Buser::get_record($user_id);
                    $view = View::factory($this->view_path . 'user_update');
                    $view->set('user', $user);
                    $view->set('errors', $errors);
                    $this->view($view);
                }
            }
        } else {
            $this->request->redirect($this->list_page);
        }
    }

    public function reset_password() {
        if (App_Adminuser::has_permission('manage user')) {
            $user_id = $this->request->param('id');
            App_Registereduser::reset_password($user_id);
        }
    }

    /**
     * search form
     */
    public function action_search_user() {
        //table
        if (App_Adminuser::has_permission('retrieve user')) {
            $page_number = $this->request->param('page_id', 1);
            $row_count = 4;
            $keyword = $this->request->param('keyword', 'user_id');
            $direction = $this->request->param('direction', 'ASC');
            $search_keywords = (isset($_GET['search_keywords'])) ? trim($_GET['search_keywords']) :
                    $this->request->param('search_keywords', '');
            $offset = ($page_number - 1) * $row_count;
            $results = Model_Registereduser::get_users($offset, $row_count, $keyword, $direction, $search_keywords);
            $total_number = Model_Registereduser::get_num_of_users($search_keywords);

            $pagination = Pagination::factory(array(
                        'current_page' => array('source' => 'route', 'key' => 'page_id'),
                        'total_items' => $total_number,
                        'items_per_page' => $row_count,
                        'auto_hide' => false,
                    ));

            $page_links = $pagination->render();

            $view = View::factory($this->view_path . 'user_list');
            $view->set('action', $this->request->action);
            if ($search_keywords != '')
                $view->set('search_keywords', $search_keywords);
            $view->set('results', $results);
            $view->set('page_links', $page_links);
            $this->set_list_params($view, $page_number, $keyword, $direction);
            $this->view($view);
        }
    }

}