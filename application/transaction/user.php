<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\User as Model_User;
use \App\Model\Ad as Model_Ad;
use \App\Transaction\Swiftmail as Transaction_Swiftmail;
use \App\Transaction\Tool as Transaction_Tool;
use \Zx\Message\Message as Zx_Message;
use \Zx\Tool\Upload as Zx_Upload;

class User {

    /**
     * @param type $uid
     * @return type
     */
    public static function clear_image($uid) {
        //get ad image and save information
        $company = Model_User::get_one($uid);
        $image = $user['image'];
        //get image directory
        $dir = PHPUPLOADROOT . 'user/';
        //update image record
        $arr = array('image' => '');
        Model_User::update($uid, $arr);
        //delete image
        if ($image != '' && file_exists($dir . $image)) {
            unlink($dir . $image);
        }

        return true;
    }

    public static function change_image($uid) {
        $this_year = date('Y');
        $dir = PHPUPLOADROOT . 'user/' . $this_year . '/'; //  company/2012/
        /*
          $file = Validation::factory($_FILES)
          ->rules('image', array(
          array('not_empty'),
          array('Upload::not_empty'),
          array('Upload::valid'),
          array('Upload::type', array(':value', array('jpg', 'png', 'gif'))),
          array('Upload::size', array(':value', '1500K'))
          ));
          if ($file->check()) {
         * 
         */
        $file_is_ok = false;
        if (isset($_FILES['image']))
            $image = $_FILES['image'];
        if (Zx_Upload::valid($image) && Zx_Upload::not_empty($image)
                && Zx_Upload::image($image)) {
            $file_is_ok = true;
        }
        if ($file_is_ok) {
            //if has new logo image
            //Upload::save(array $file, $filename = NULL, $directory = NULL, $chmod = 0644)
            $filename = Zx_Upload::save($image, NULL, $dir, 0755);   //image_file is the field name in the form
            $filename = $this_year . '/' . basename($filename);  //use Upload class generated file name, it replaces ' ' with '_'
            // App_Test::objectLog('$filename',$filename, __FILE__, __LINE__, __CLASS__, __METHOD__);
            if ($filename != '') {
                $user = Model_User::get_one($uid);
                $old_image = $user['image'];  //prepare for deletion
                $image_arr = array('image' => $filename);
                //App_Test::objectLog('$image_arr', $image_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

                Model_User::update($uid, $image_arr);
                if ($old_image != '' && file_exists($dir . basename($old_image))) {
                    unlink($dir . basename($old_image));
                }
            }
        } else {
            if (!empty($image['name'])) {
                Zx_Message::set_error_message("图片文件过大或不是有效图片，请重新上传。");
            }
        }
        Zx_Message::set_success_message("信息已成功更新.");
        return true;
    }

    /**
     * when a user is disabled, the ads will be disabled too, 
     * but question and answer are not affected
     * @param int $uid
     * @param int $status
     * @return boolean 
     */
    public static function change_status($uid, $status) {
        $user = Model_User::get_one($uid);
        if ($user) {
            $arr = array('status' => $status);
            Model_User::update($uid, $arr);
            if ($status == 0) {
                //disabled
                Model_Ad::disable_by_uid($uid);
            }
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
            $uid = Model_User::exist_email($email);
        } else {
            //it's user name
            $uid = Model_User::exist_uname($email);
        }
        if ($uid) {
            $new_password = mt_rand();
            $user_arr = array('password' => $new_password);
            Model_User::update($uid, $user_arr);
            //App_Test::objectLog('$new_password',$new_password, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $user = Model_User::get_one($uid);
            Transaction_Swiftmail::send_new_password($user, $new_password);
            Zx_Message::set_success_message("您的新密码已经发送到您的电子邮箱， 请查看。");
            return true;
        } else {
            Zx_Message::set_error_message("您的用户名或电子邮箱不正确， 请重新输入或与网站管理员联系。");
            return false;
        }
    }

    /*     * by admin only
     * admin can reset password, system generate new password
     * @param int $uid  
     * @return boolean
     */

    public static function reset_password($uid) {
        if (App_Staff::admin_has_loggedin()) {
            $new_password = mt_rand();
            $user_arr = array('password' => $new_password);
            Model_User::update($uid, $user_arr);
            //App_Test::objectLog('$new_password',$new_password, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $user = Model_User::get_one($uid);
            Transaction_Swiftmail::send_new_password($user, $new_password);
            Zx_Message::set_success_message("The password has been reset and sent to user's email box.");
        }
        return true;
    }

    /**
     * if has question or answer or company, cannot delete it
     * usually a user cannot be deleted
     * @param int $uid 
     */
    public static function delete_user($uid) {

        if (Model_User::can_be_deleted($uid)) {
            Model_User::delete($uid);
            Zx_Message::set_success_message("删除用户成功.");
            return true;
        } else {
            Zx_Message::set_success_message("用户有相关记录， 不能删除.");
            return false;
        }
    }

    /**
     * when register
     * @param type $user_arr
     * @return type 
     */
    public static function register_user($user_arr) {
        if (Model_User::exist_uname($user_arr['uname']) ||
                Model_User::exist_email($user_arr['email'])
        ) {
            Zx_Message::set_error_message("用户名或电子邮箱已经被注册， 请用其他用户名或电子邮箱注册。");
            return false;
        } else {
            $user_arr['status'] = 1; //activated status is 1, registered status is 2, currently set it to 1 to avoid activate (no email now)
            if ($uid = Model_User::create($user_arr)) {
                $user_arr['id'] = $uid;
                //App_Swiftmailer::send_activation_link($user_arr);
                //App_Session::set_success_message("感谢您在" . SITENAME . "注册， 我们已经发送邮件到您的电子邮箱， 请查看邮件并激活您的账户。 ");
                return true;
            } else {
                Zx_Message::set_error_message("您的账户未注册成功， 请重新注册。");
                return false;
            }
        }
    }

    /**
     * after activating the user, go to user account home page (no login)
     * @param int $uid
     * @param hash string $code
     * @return boolean 
     */
    public static function activate_user($uid, $code) {
        $user = Model_User::get_one($uid);
        $code1 = substr(md5($user->id), 1, 10) . substr(md5($user->email), 1, 10) . substr(md5($user->uname), 1, 12);
        if ($code == $code1) {
            $arr = array('status' => 1); //active
            Model_User::update($user, $arr);
            Zx_Message::set_success_message("您的账户已经激活成功， 您现在就可以登录您的账户。 ");
            /** to go to account home page directly, store session data
             * copy from self::valid_user() method
             * start */
            $user = Model_User::get_one($uid);

            if ($user) {
                $session_array = array(
                    'uid' => $user->id,
                    'uname' => $uname,
                );

                session_regenerate_id();
                Zx_Message::set_success_message("您的账户已激活成功， 您可以开始操作您的账户。");
                $_SESSION['user'] = $session_array;
            }
            /** end  */
            return true;
        } else {
            Zx_Message::set_error_message("对不起， 您的账户未激活成功， 请重新激活， 或由网站重新发送一个激活邮件。");
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
            $uid = Model_User::exist_email($name);
        } else {
            //it's user name
            $uid = Model_User::exist_uname($name);
        }
        if ($uid) {
            $user = Model_User::get_one($uid);
            if ($user->status == '2') {
//App_Test::objectLog('success','2', __FILE__, __LINE__, __CLASS__, __METHOD__);
                //if inactivated, send activation link
                $user_arr = array('id' => $uid, 'uname' => $user->uname, 'email' => $user->email);
                Transaction_Swiftmail::send_activation_link($user_arr);
                Zx_Message::set_success_message("我们已经发送了激活邮件到您的邮箱， 请您检查邮箱并激活您的账户.");
                return true;
            } else {
                //              App_Test::objectLog('fail','1', __FILE__, __LINE__, __CLASS__, __METHOD__);
                //otherwise, ignore it
                Zx_Message::set_error_message("您的账户已经激活， 您现在就可以登录您的账户.");
                return false;
            }
        } else {
            //         App_Test::objectLog('fail','3', __FILE__, __LINE__, __CLASS__, __METHOD__);
            Zx_Message::set_error_message("对不起， 您的用户名或邮箱不正确， 请重新输入.");
            return false;
        }
    }

    /**
     * for admin to create a user
     * when create a user in backend
      1. check user name, email are unique in database
      2. generate a password automatically
      3. insert a record into user table
      4. send email to user to show password
     * @param array  $arr =array('uname'=>$uname,
      'password'=>$password,
      'email'=>$email,....)
     */
    public static function create_user($user_arr) {
        if (!Model_User::exist_uname_or_email($user_arr['uname']) &&
                !Model_User::exist_uname_or_email($user_arr['email'])) {
            //$user_arr['password'] = Transaction_Tool::generatePassword();
            if ($uid = Model_User::create($user_arr)) {
                //$user = Model_User::get_one($uid);
                self::change_image($uid);
                //App_Swiftmailer::send_new_password($user, $user_arr['password']);
                Zx_Message::set_success_message("创建用户成功.");
                return true;
            } else {
                Zx_Message::set_error_message("创建用户不成功.");
                return false;
            }
        } else {
            Zx_Message::set_error_message("用户名或邮箱已在本网站注册。");
            return false;
        }
    }

    /**
     * user name cannot be changed now, otherwise, will update all user name fields in other tables
     * Todo: for status change, something will happen
     * @param int $uid
     * @param array $user_arr
     * @return boolean 
     */
    public static function update_user($uid, $user_arr) {
        if (
                //!Model_User::duplicate_uname_or_email($uid, $user_arr['uname']) AND
                !Model_User::duplicate_uname_or_email($uid, $user_arr['email'])) {
            if (Model_User::update($uid, $user_arr)) {
                \Zx\Message\Message::set_success_message("用户信息已更新.");
                return true;
            } else {
                \Zx\Message\Message::set_error_message("用户信息更新失败.");
                return false;
            }
        } else {
           \Zx\Message\Message::set_error_message("用户名或邮箱已在本网站注册。");
            return false;
        }
    }

    /**
     * check the user is valid or not, status must be "enable", 
     * group_id must be 3(A) or 4(B)
     * set up 'user' session value
     * @param <string> $uname  user name or email
     * @param <string> $password
     * @return <boolean> true is the user is valid
     */
    public static function verify_user($uname, $password) {
        $user = Model_User::verify_user($uname, $password);
        if ($user) {
            $session_array = array(
                'uid' => $user['id'],
                'uname' => $uname,
            );
            session_regenerate_id();
            $_SESSION['user'] = $session_array;
            //\Zx\Test\Test::object_log('$_SESSION',$_SESSION, __FILE__, __LINE__, __CLASS__, __METHOD__);
            Zx_Message::set_success_message("您已登录成功， 可以开始操作您的账户。");
            return true;
        } else {
            return false;
        }
    }

    /**
     * for front end user
     * @return <integer or boolean>  if has user id, return it, otherwise, return false
     */
    public static function get_user() {
        if (isset($_SESSION['user']['uid'])) {
            return Model_User::get_one($_SESSION['user']['uid']);
        } else {
            return false;
        }
    }
    /**
     * for front end user
     * @return <integer>  if has user id, return it, otherwise, return 0
     */
    public static function get_uid() {
        if (isset($_SESSION['user'])) {
            return intval($_SESSION['user']['uid']);
        } else {
            return 0;
        }
    }

    /**
     *
     * @return <string>  if has user name, return it, otherwise, return empty string
     */
    public static function get_uname() {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user']['uname'];
        } else {
            return '';
        }
    }

    public static function user_logout() {
        Zx_Message::init_message();
        if (isset($_SESSION['user']))
            unset($_SESSION['user']);
        return true;
    }

    /**
     * check front end user log in or not
     * @return boolean
     */
    public static function user_has_loggedin() {
        if (isset($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * update profile by registered user
     * @param int $uid
     * @param array $arr ( 'first_name' => $first_name,
      'last_name' => $last_name,
      'phone' => $phone,
      'suburb_id' => $suburb_id,
      'city_id' => $city_id,
      'state' => $state,)
     */
    public static function update_profile($uid, $arr) {
        $user = Model_User::get_record($uid);
        if (
                $user->first_name != $arr['first_name'] ||
                $user->last_name != $arr['last_name'] ||
                $user->phone != $arr['phone'] ||
                $user->city_id != $arr['city_id'] ||
                $user->suburb_id != $arr['suburb_id'] ||
                $user->state != $arr['state']) {
            Model_User::update_record($uid, $arr);
        }
        App_Session::set_success_message("您的基本信息已经生效。");
        return true;
    }

    /**
     * @param int user id
     * @param array $arr = array('old_password', 'new_password'); 
     */
    public static function change_password($uid, $arr) {
        if (Model_User::password_is_correct($uid, $arr['old_password'])) {
            $user_arr = array('password' => $arr['new_password']);
            Model_User::update_record($uid, $user_arr);
            //App_Log::create_change_password_log($uid);
            //it's better to send email to remind customer the password is changed.
            App_Session::set_success_message("您的新密码已经生效。");
            return true;
        } else {
            App_Session::set_error_message("您输入的旧密码不正确， 请输入正确的旧密码。");
            return false;
        }
    }
/**
      To avoid generating passwords containing offensive words,
      vowels are excluded from the list of possible characters.
      To avoid confusing users, pairs of characters which look similar
      (letter O and number 0, letter S and number 5, lower-case letter L and number 1)
      have also been left out.
     */
    public static function generatePassword($length = 8) {

        // start with a blank password
        $password = "";

        // define possible characters - any character in this string can be
        // picked for use in the password, so if you want to put vowels back in
        // or add special characters such as exclamation marks, this is where
        // you should do it
        $possible = "!*#+$%&34789cdfghjkmnpqrtwxyBCDFGHJKLMNPQRTWXY";

        // we refer to the length of $possible a few times, so let's grab it now
        $maxlength = strlen($possible);

        // check for length overflow and truncate if necessary
        if ($length > $maxlength) {
            $length = $maxlength;
        }

        // set up a counter for how many characters are in the password so far
        $i = 0;

        // add random characters to $password until $length is reached
        while ($i < $length) {

            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, $maxlength - 1), 1);

            // have we already used this character in $password?
            if (!strstr($password, $char)) {
                // no, so it's OK to add it onto the end of whatever we've already got...
                $password .= $char;
                // ... and increase the counter by one
                $i++;
            }
        }

        // done!
        return $password;
    }
}

