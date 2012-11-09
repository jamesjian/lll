<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 2 levels of registered users, A and B, B has more permissions or benefits such as bigger number of threads
 * default one is A, when register a user, its group is A, group id is 3;
 * admin can raise the user to group B, group id is 4.
 */
class App_User {

    /**
     * will optimize it, use group by
     * 
     * will add blog and message info
     * @return boolean 
     */
    public static function refresh_user_info() {
        $users = Model_User::get_all_registered_user_ids();

        foreach ($users as $user) {
            $user_id = $user->id;
            $num_bulletin = Model_Bulletin::get_num_of_records_by_uid($user_id);
            $num_active_bulletin = Model_Bulletin::get_num_of_active_records_by_uid($user_id);
            $num_company = Model_Company::get_num_of_records_by_uid($user_id);
            $num_active_company = Model_Company::get_num_of_active_records_by_uid($user_id);
            $num_discount = Model_Discount::get_num_of_records_by_uid($user_id);
            $num_active_discount = Model_Discount::get_num_of_active_records_by_uid($user_id);
            $num_requirement = Model_Requirement::get_num_of_records_by_uid($user_id);
            $num_active_requirement = Model_Requirement::get_num_of_active_records_by_uid($user_id);
            $num_secondhand = Model_Secondhand::get_num_of_records_by_uid($user_id);
            $num_active_secondhand = Model_Secondhand::get_num_of_active_records_by_uid($user_id);
            $num_goods = Model_Goods::get_num_of_records_by_uid($user_id);
            $num_active_goods = Model_Goods::get_num_of_active_records_by_uid($user_id);


            $arr = array(
                'num_of_bulletin' => $num_bulletin,
                'num_of_active_bulletin' => $num_active_bulletin,
                'num_of_company' => $num_company,
                'num_of_active_company' => $num_active_company,
                'num_of_discount' => $num_discount,
                'num_of_active_discount' => $num_active_discount,
                'num_of_requirement' => $num_requirement,
                'num_of_active_requirement' => $num_active_requirement,
                'num_of_secondhand' => $num_secondhand,
                'num_of_active_secondhand' => $num_active_secondhand,
                'num_of_goods' => $num_goods,
                'num_of_active_goods' => $num_active_goods,
            );
            Model_User::update_record($user_id, $arr);
        }
        App_Session::set_success_message('User info is refeshed');
        return true;
    }

    /**
     * @param type $user_id
     * @return type
     */
    public static function clear_image($user_id) {
        //get ad image and save information
        $company = Model_User::get_record($user_id);
        $image = $user->image;
        //get image directory
        $dir = PHPUPLOADROOT . 'user/';
        //update image record
        $arr = array('image' => '');
        Model_User::update_record($user_id, $arr);
        //delete image
        if ($image != '' && file_exists($dir . $image)) {
            unlink($dir . $image);
        }

        return true;
    }

    public static function change_portrait($user_id) {
        $this_year = date('Y');
        $dir = PHPUPLOADROOT . 'user/' . $this_year . '/'; //  company/2012/
        $file = Validation::factory($_FILES)
                ->rules('image', array(
            array('not_empty'),
            array('Upload::not_empty'),
            array('Upload::valid'),
            array('Upload::type', array(':value', array('jpg', 'png', 'gif'))),
            array('Upload::size', array(':value', '1500K'))
                ));
        if ($file->check()) {
            //if has new logo image
            //Upload::save(array $file, $filename = NULL, $directory = NULL, $chmod = 0644)
            $filename = Upload::save($_FILES['image'], NULL, $dir, 0755);   //image_file is the field name in the form
            $filename = $this_year . '/' . basename($filename);  //use Upload class generated file name, it replaces ' ' with '_'
            // App_Test::objectLog('$filename',$filename, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if ($filename != '') {
                $user = Model_User::get_record($user_id);
                $old_image = $user->image;  //prepare for deletion
                $image_arr = array('image' => $filename);
                App_Test::objectLog('$image_arr', $image_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

                Model_User::update_record($user_id, $image_arr);
                if ($old_image != '' && file_exists($dir . basename($old_image))) {
                    unlink($dir . basename($old_image));
                }
            }
        } else {
            if (!empty($_FILES['image']['name'])) {
                App_Session::set_error_message("Image is not valid (not image or too big), contact administrator.");
            }
        }
        App_Session::set_success_message("信息已成功更新.");
        return true;
    }

    /**
     * group id=3 is registered user
     * @param int $user_id
     * @param int $status
     * @return boolean 
     */
    public static function change_registered_user_status($user_id, $status) {
        $user = Model_User::get_record($user_id);
        $group_id = intval($user->group_id);
        if ($group_id == 3 || $group_id == 4) {
            $arr = array('status' => $status);
            Model_User::update_record($user_id, $arr);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 1. check email,   email might be email or user name
     * 2. if exists,  generate a new password and send email to customer
     * 3. log
     * 
     * @param type $arr ['name'] is user name or email 
     */
    public static function generate_new_password($email) {
        if (strpos($email, '@')) {
            //it's email
            $user_id = Model_User::exist_email($email);
        } else {
            //it's user name
            $user_id = Model_User::exist_user_name($email);
        }
        if ($user_id) {
            $new_password = mt_rand();
            $user_arr = array('password' => $new_password);
            Model_User::update_record($user_id, $user_arr);
            //App_Test::objectLog('$new_password',$new_password, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $user = Model_User::get_record($user_id);
            App_Swiftmailer::send_new_password($user, $new_password);
            App_Session::set_success_message("您的新密码已经发送到您的电子邮箱， 请查看。");
            return true;
        } else {
            App_Session::set_error_message("您的用户名或电子邮箱不正确， 请重新输入或与网站管理员联系。");
            return false;
        }
    }

    /*     * by admin only
     * admin can reset password, system generate new password
     * @param int $user_id  
     * @return boolean
     */

    public static function reset_password($user_id) {
        if (App_Staff::admin_has_loggedin()) {
            $new_password = mt_rand();
            $user_arr = array('password' => $new_password);
            Model_User::update_record($user_id, $user_arr);
            //App_Test::objectLog('$new_password',$new_password, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $user = Model_User::get_record($user_id);
            App_Swiftmailer::send_new_password($user, $new_password);
            App_Session::set_success_message("The password has been reset and sent to user's email box.");
        }
        return true;
    }

    /**
     * if has order or thread or fund, cannot delete it
     * usually a user cannot be deleted
     * @param int $user_id 
     */
    public static function delete_registered_user($user_id) {
        $user = Model_User::get_record($user_id);
        if (!((($user->num_of_bulletin +
                $user->num_of_company +
                $user->num_of_discount +
                $user->num_of_goods +
                $user->num_of_requirement +
                $user->num_of_secondhand +
                $user->num_of_blog +
                $user->num_of_order) > 0) ||
                abs($user->fund) > 0)) {
            Model_User::delete_record($user_id);
            App_Session::set_success_message("user is deleted successfully.");
            return true;
        } else {
            App_Session::set_success_message("this user has records in the system, cannot delete it.");
            return false;
        }
    }

    /**
     * when register, all user group is Group A (id is 3)
     * @param type $user_arr
     * @return type 
     */
    public static function register_user($user_arr) {
        if (!Model_User::exist_user_name($user_arr['user_name']) AND
                !Model_User::exist_email($user_arr['email'])
        ) {
            $user_arr['group_id'] = 3; //registered user
            $user_arr['status'] = 2; //registered status is 2
            if ($user_id = Model_User::create_record($user_arr)) {
                $user_arr['id'] = $user_id;
                App_Swiftmailer::send_activation_link($user_arr);
                //App_Session::set_success_message("感谢您在fengyunlist.com.au注册， 我们已经发送邮件到您的电子邮箱， 请查看邮件并激活您的账户。 您很快就可以在fengyunlist.com.au上建立生意、 发布广告、 上传产品及发布需求。");
                return true;
            } else {
                App_Session::set_error_message("您的账户未注册成功， 请重新注册。");
                return false;
            }
        } else {
            App_Session::set_error_message("用户名或电子邮箱已经被注册， 请用其他用户名或电子邮箱注册。");
            return false;
        }
    }

    /**
     * after activating the user, go to user account home page (no login)
     * @param int $user_id
     * @param hash string $code
     * @return boolean 
     */
    public static function activate_user($user_id, $code) {
        $user = Model_User::get_record($user_id);
        $code1 = substr(md5($user->id), 1, 10) . substr(md5($user->email), 1, 10) . substr(md5($user->user_name), 1, 12);
        if ($code == $code1) {
            $arr = array('status' => 1); //active
            Model_User::update_record($user, $arr);
            App_Session::set_success_message("您的账户已经激活成功， 您现在就可以登录您的账户。 ");
            /** to go to account home page directly, store session data
             * copy from self::valid_user() method
             * start */
            $user = Model_User::get_record($user_id);

            if ($user && (intval($user->group_id) == 3 || intval($user->group_id) == 4)) {
                $group = Model_Group::get_record($user->group_id);
                if ($group->id == 3) {
                    $num_of_threads = NUM_OF_THREADS_PER_USER_IN_GROUP_A;
                } elseif ($group->id == 4) {
                    $num_of_threads = NUM_OF_THREADS_PER_USER_IN_GROUP_A1;
                }
                $session_array = array(
                    'user_id' => $user->id,
                    'user_name' => $user_name,
                    'group_id' => intval($user->group_id),
                    'group_name' => $group->group_name,
                    'num_of_thread' => $num_of_threads,
                    'action_array' => Model_grouptoaction::get_actions_by_group_id($user->group_id),
                );

                Session::instance()->regenerate();
                App_Session::set_success_message("您的账户已激活成功， 您可以开始操作您的账户。");
                App_Session::set_session('user', $session_array);
            }
            /** end  */
            return true;
        } else {
            App_Session::set_error_message("对不起， 您的账户未激活成功， 请重新激活， 或由网站重新发送一个激活邮件。");
            return false;
        }
    }

    /**
     *
     * @param string $name  name might be email or user name
     * @return boolean
     */
    public static function send_another_activation_link($name) {
        if (strpos($name, '@')) {
            //it's email
            $user_id = Model_User::exist_email($name);
        } else {
            //it's user name
            $user_id = Model_User::exist_user_name($name);
        }
        if ($user_id) {
            $user = Model_User::get_record($user_id);
            if ($user->status == '2') {
//App_Test::objectLog('success','2', __FILE__, __LINE__, __CLASS__, __METHOD__);
                //if inactivated, send activation link
                $user_arr = array('id' => $user_id, 'user_name' => $user->user_name, 'email' => $user->email);
                App_Swiftmailer::send_activation_link($user_arr);
                App_Session::set_success_message("我们已经发送了激活邮件到您的邮箱， 请您检查邮箱并激活您的账户.");
                return true;
            } else {
                //              App_Test::objectLog('fail','1', __FILE__, __LINE__, __CLASS__, __METHOD__);
                //otherwise, ignore it
                App_Session::set_error_message("您的账户已经激活， 您现在就可以登录您的账户.");
                return false;
            }
        } else {
            //         App_Test::objectLog('fail','3', __FILE__, __LINE__, __CLASS__, __METHOD__);
            App_Session::set_error_message("对不起， 您的用户名或邮箱不正确， 请重新输入.");
            return false;
        }
    }

    /**
     * when create a user in backend
      1. check user name, email are unique in database
      2. generate a password automatically
      3. insert a record into user table
      4. send email to user to show password
      5. update cache tables
     * @param array  $arr =array('user_name'=>$user_name,
      'password'=>$password,
      'group_id'=>$group_id,
      'email'=>$email,)
     */
    public static function create_registered_user($user_arr) {
        if (!Model_User::exist_user_name_or_email($user_arr['user_name']) &&
                !Model_User::exist_user_name_or_email($user_arr['email'])) {
            $user_arr['password'] = App_Tool::generatePassword();
            if ($user_id = Model_User::create_record($user_arr)) {
                $user = Model_User::get_record($user_id);
                App_Swiftmailer::send_new_password($user, $user_arr['password']);
                //Todo: update cache tables
                App_Session::set_success_message("user is created successfully.");
                return true;
            } else {
                App_Session::set_error_message("fail to create new user.");
                return false;
            }
        } else {
            App_Session::set_error_message("user name or email has been registered , try again or contact administrator.");
            return false;
        }
    }

    /**
     * Todo: for status change, something will happen
     * @param int $user_id
     * @param array $user_arr
     * @return boolean 
     */
    public static function update_registered_user($user_id, $user_arr) {
        if (!Model_User::duplicate_user_name_or_email($user_id, $user_arr['user_name']) AND
                !Model_User::duplicate_user_name_or_email($user_id, $user_arr['email'])) {
            if (Model_User::update_record($user_id, $user_arr)) {
                App_Session::set_success_message("用户信息已更新.");
                return true;
            } else {
                App_Session::set_error_message("用户信息更新失败.");
                return false;
            }
        } else {
            App_Session::set_error_message("user name/email is duplicate, try again or contact administrator.");
            return false;
        }
    }

    /**
     * check the user is valid or not, status must be "enable", 
     * group_id must be 3(A) or 4(B)
     * set up 'user' session value
     * @param <string> $user_name  user name or email
     * @param <string> $password
     * @return <boolean> true is the user is valid
     */
    public static function valid_user($user_name, $password) {
        $user = ORM::factory('base_user')->
                        where_open()->
                        where('user_name', '=', $user_name)->
                        or_where('email', '=', $user_name)->
                        where_close()->
                        and_where('password', '=', md5($password))->
                        and_where('status', '=', 1)->find();
        //App_Test::objectLog('last query',ORM::factory('base_user')->last_query(), __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($user && !empty($user->id) && (intval($user->group_id) == 3 || intval($user->group_id) == 4)) {
            $group = Model_Group::get_record($user->group_id);
            if ($group->id == 3) {
                $num_of_threads = NUM_OF_THREADS_PER_USER_IN_GROUP_A;
            } elseif ($group->id == 4) {
                $num_of_threads = NUM_OF_THREADS_PER_USER_IN_GROUP_B;
            }
            if (!empty($group) AND intval($group->status) == 1) {

                $session_array = array(
                    'user_id' => $user->id,
                    'user_name' => $user_name,
                    'group_id' => intval($user->group_id),
                    'group_name' => $group->group_name,
                    'num_of_thread' => $num_of_threads,
                    'action_array' => Model_grouptoaction::get_actions_by_group_id($user->group_id),
                );
                Session::instance()->regenerate();
                Session::instance()->set('user', $session_array);
                App_Session::set_success_message("您已登录成功， 可以开始操作您的账户。");
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * for front end user
     * @return <integer>  if has user id, return it, otherwise, return 0
     */
    public static function get_user_id() {
        if (!is_null($arr = Session::instance()->get('user'))) {
            if (isset($arr['user_id'])) {
                return intval($arr['user_id']);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     *
     * @return <string>  if has user name, return it, otherwise, return empty string
     */
    public static function get_user_name() {
        if (!is_null($arr = Session::instance()->get('user'))) {
            if (isset($arr['user_name'])) {
                return $arr['user_name'];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    /**
     *
     * @return <integer>  if has group id, return it, otherwise, return 0
     */
    public static function get_group_id() {
        if (!is_null($arr = Session::instance()->get('user'))) {
            if (isset($arr['group_id'])) {
                return intval($arr['group_id']);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     *
     * @return <string>  if has group name, return it, otherwise, return empty string
     */
    public static function get_group_name() {
        if (!is_null($arr = Session::instance()->get('user'))) {
            if (isset($arr['group_name'])) {
                return $arr['group_name'];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    /**
     *
     * @return <string>  if has group name, return it, otherwise, return empty string
     */
    public static function get_num_of_threads() {
        if (!is_null($arr = Session::instance()->get('user'))) {
            if (isset($arr['num_of_threads'])) {
                return $arr['num_of_threads'];
            } else {
                return NUM_OF_THREADS_PER_USER_IN_GROUP_A;  //default value
            }
        } else {
            return NUM_OF_THREADS_PER_USER_IN_GROUP_A;  //default value
        }
    }

    /**
     * @param object $user
     * check num of existing threads of a user with self::get_num_of_threads()
     * @return boolean if still can create new thread, return true
     */
    public static function user_can_create_new_thread($user) {
        if ($user->group_id == 3) {
            $num_of_threads = NUM_OF_THREADS_PER_USER_IN_GROUP_A;
        } elseif ($user->group_id == 4) {
            $num_of_threads = NUM_OF_THREADS_PER_USER_IN_GROUP_A1;
        }
        $num_of_existing_threads =
                $user->num_of_bulletin_all + $user->num_of_discount_all +
                $user->num_of_requirement_all + $user->num_of_secondhand_all;
        if ($num_of_existing_threads < $num_of_threads) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * if the user belongs to this group, return true; else return false;
     * @param string $group_name
     * @return boolean
     */
    public static function valid_group($group_name) {
        if (!is_null($arr = Session::instance()->get('user'))) {
            if (isset($arr['group_name']) AND $arr['group_name'] == $group_name) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function has_permission($action_name) {
        $arr = Session::instance()->get('user');
        if (!is_null($arr) && isset($arr['action_array']) AND in_array('all', $arr['action_array'])) {
            //if has "all" permission  (usually for developer)
            return true;
        } else {
            if (!is_null($arr) && isset($arr['action_array']) AND in_array($action_name, $arr['action_array'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function logout() {
        App_Session::clear_message();
        Session::instance()->destroy();
    }

    /**
     * check front end user log in or not
     * @return boolean
     */
    public static function has_loggedin() {
        if (!is_null(Session::instance()->get('user'))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * update profile by registered user
     * @param int $user_id
     * @param array $arr ( 'first_name' => $first_name,
      'last_name' => $last_name,
      'phone' => $phone,
      'suburb_id' => $suburb_id,
      'city_id' => $city_id,
      'state' => $state,)
     */
    public static function update_profile($user_id, $arr) {
        $user = Model_User::get_record($user_id);
        if (
                $user->first_name != $arr['first_name'] ||
                $user->last_name != $arr['last_name'] ||
                $user->phone != $arr['phone'] ||
                $user->city_id != $arr['city_id'] ||
                $user->suburb_id != $arr['suburb_id'] ||
                $user->state != $arr['state']) {
            Model_User::update_record($user_id, $arr);
        }
        App_Session::set_success_message("您的基本信息已经生效。");
        return true;
    }

    /**
     * @param int user id
     * @param array $arr = array('old_password', 'new_password'); 
     */
    public static function change_password($user_id, $arr) {
        if (Model_User::password_is_correct($user_id, $arr['old_password'])) {
            $user_arr = array('password' => $arr['new_password']);
            Model_User::update_record($user_id, $user_arr);
            //App_Log::create_change_password_log($user_id);
            //it's better to send email to remind customer the password is changed.
            App_Session::set_success_message("您的新密码已经生效。");
            return true;
        } else {
            App_Session::set_error_message("您输入的旧密码不正确， 请输入正确的旧密码。");
            return false;
        }
    }

    /*     * when email is changed, need to activate again
     * @param integer user id
     * @param string $email
     */

    public static function change_email($user_id, $email) {
        $user_arr = array('email' => $email, 'status' => 0);
//$user_arr=array();
        if (Model_User::update_record($user_id, $user_arr)) {
            //App_Log::create_change_password_log($user_id);
            $user = Model_User::get_record($user_id);
            $user_arr['id'] = $user_id;
            $user_arr['user_name'] = $user->user_name;
            //App_Swiftmailer::send_activation_link($user_arr);
            App_Session::set_success_message("我们已经发送邮件到您新的电子邮箱， 请查看邮件并重新激活您的账户。");
            return true;
        } else {
            App_Session::set_error_message("系统有误， 请重试或联系客户服务部门。");
            return false;
        }
    }

}

