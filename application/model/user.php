<?php

/*
 */

class Model_User extends Model_Base_User {

    public static function get_num_of_previous_records($date) {
        $where = " date_created<='$date'";
        return parent::get_num_of_records($where);
    }

    public static function increase_num_of_blog($user_id) {
        $user = Model_User::get_record($user_id);
        $user->num_of_blog = $user->num_of_blog + 1;
        $user->save();
    }

    public static function decrease_num_of_blog($user_id) {
        $user = Model_User::get_record($user_id);
        if ($user->num_of_blog > 0) {
            $user->num_of_blog = $user->num_of_blog - 1;
            $user->save();
        }
    }

    public static function increase_num_of_message($user_id) {
        $user = Model_User::get_record($user_id);
        $user->num_of_message = $user->num_of_message + 1;
        $user->save();
    }

    public static function decrease_num_of_message($user_id) {
        $user = Model_User::get_record($user_id);
        if ($user->num_of_message > 0) {
            $user->num_of_message = $user->num_of_message - 1;
            $user->save();
        }
    }

    public static function disable_user($user_id) {
        $arr = array('status' => 'disable');
        return parent::update_record($user_id, $arr);
    }

    /**
     *
     * @param integer $user_id
     * @param string $password 
     * @return boolean
     */
    public static function password_is_correct($user_id, $password) {
        $user = parent::get_record($user_id);
        if ($user AND $user->password == md5($password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $user_name
     * @return $user object or false
     */
    public static function get_user_by_user_name($user_name) {
        $user = ORM::factory(parent::$model_class, array('user_name' => $user_name));
        if ($user->loaded()) {
            return $user;
        } else {
            return false;
        }
    }

    /**
     * @param $user_name 
     * @return boolean if user_name exists in users table, return true, else false
     */
    public static function exist_user_id($user_id) {
        $user = parent::get_record($user_id);
        if ($user) {
            //App_Test::objectLog('exist_user_name','exist_user_name', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return true;
        } else {
            //App_Test::objectLog('exist_user_name','not exist_user_name', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return false;
        }
    }

    /**
     * @param $user_name 
     * @return boolean if user_name exists in users table, return true, else false
     */
    public static function exist_user_name($user_name) {
        $user = ORM::factory(parent::$model_class, array('user_name' => $user_name));
        if ($user->loaded()) {
            //App_Test::objectLog('exist_user_name','exist_user_name', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return $user->id;
        } else {
            //App_Test::objectLog('exist_user_name','not exist_user_name', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return false;
        }
    }

    /**
     * @param $email
     * @return boolean if email exists in users table, return true, else false
     */
    public static function exist_email($email) {
        $user = ORM::factory(parent::$model_class, array('email' => $email));
        if ($user->loaded()) {
            //App_Test::objectLog('exist_email','exist_email', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return $user->id;
        } else {
            //App_Test::objectLog('exist_email','not exist_email', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return false;
        }
    }

    /**
     * @param $name 
     * @return boolean if name exists in email or user_name fields in user table, return true, else false
     */
    public static function exist_user_name_or_email($name) {
        $user = ORM::factory(parent::$model_class)->where('user_name', '=', $name)->or_where('email', '=', $name)->find();
        if ($user->loaded()) {
            //App_Test::objectLog('exist_','exist_', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return $user->id;
        } else {
            //App_Test::objectLog('last query',ORM::factory(parent::$model_class)->last_query(), __FILE__, __LINE__, __CLASS__, __METHOD__);
            //App_Test::objectLog('exist_','not exist_', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return false;
        }
    }

    /**
     * @param $group id 
     * @return array(user_id=>user_name)
     */
    public static function get_one_group_user_ids_array($group_id) {
        $user_ids = array();
        $q = "SELECT u.id, u.user_name FROM user u WHERE group_id=$group_id";
        $query = DB::query(Database::SELECT, $q);
        $users = $query->as_object()->execute();
        foreach ($users as $user) {
            $user_ids[intval($user->id)] = $user->user_name;
        }
        return $user_ids;
    }

    /**
     *
     * @param string $user_name
     * @return integer or boolean
     */
    public static function get_user_id($user_name) {
        $user = ORM::factory(parent::$model_class)->where('user_name', '=', $user_name)->find();
        if ($user->loaded())
            return intval($user->id);
        else
            return false;
    }

    /**
     * get all actions that can be executed by this user
     * @param <integer> $user_id
     * @return array of actions 
     */
    public static function get_actions($user_id) {
        $user = self::get_user($user_id);
        $actions = Model_Group::get_actions($user->group_id);
        return $actions;
    }

    /**
     * check if a user exists in user table
     * @param <string> $user_name
     * @return <boolean> if user_name exists in user table, return $user; otherwise return false;
     */
    public static function exist_user($user_name) {
        $user = ORM::factory(parent::$model_class, array('user_name' => $user_name));
        if ($user->loaded())
            return $user;
        else
            return false;
    }

    /**
     * check if user name matches password and enabled in user table
     * @param <string> $user_name
     * 
     * @param <string> $password
     * @return <boolean> if valid in user table, return true; otherwise return false;
     */
    public static function valid_user($user_name, $password) {
        $user = ORM::factory(parent::$model_class, array('user_name' => $user_name, 'password' => md5($password), 'status' => 'enable'));
        if ($user->loaded())
            return $user;
        else
            return false;
    }

    /**
     *
     * @param <integer> $user_id
     * @return <integer or boolean> if exist user, return group id, else return false
     */
    public static function get_group_id($user_id) {
        $user = self::get_record($user_id);
        if ($user)
            return intval($user->group_id);
        else
            return false;
    }

    /**
     * return group name of a user
     * @param integer $user_id
     * @return string or boolean
     */
    public static function get_group_name($user_id) {
        $group_id = self::get_group_id($user_id);
        if ($group_id) {
            $group = Model_Group::get_group($group_id);
            if ($group) {
                return $group->group_name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function get_registered_users($offset = 0, $row_count = MAXIMUM_ROWS, $keyword = 'u.user_name', $direction = 'ASC', $where = '1') {
        $where = ' (group_id=3 OR group_id=4) AND (' . $where . ')';
        return parent::get_records($offset, $row_count, $keyword, $direction, $where);
    }

    public static function get_num_of_registered_users($where = '1') {
        $where = ' (group_id=3 OR group_id=4) AND (' . $where . ')';
        return parent::get_num_of_records($where);
    }

    /**
     * (group_id=3 OR group_id=4)
     * @return array of user_id=>user_name pair
     *  
     */
    public static function get_registered_users_array() {
        $q = "SELECT u.id, u.user_name FROM user u WHERE (group_id=3 OR group_id=4) ORDER BY user_name";
        $query = DB::query(Database::SELECT, $q);
        $users = $query->as_object()->execute();
        $users_array = array();
        foreach ($users as $user) {
            $users_array[$user->id] = $user->user_name;
        }
        return $users_array;
    }

    /**
     * (group_id=3 OR group_id=4)
     * @return array of user_id=>user_name pair
     *  
     */
    public static function get_all_registered_user_ids() {
        $q = "SELECT u.id FROM user u WHERE (u.group_id=3 OR u.group_id=4)";
        $query = DB::query(Database::SELECT, $q);
        $users = $query->as_object()->execute();
        return $users;
    }

    public static function get_all_users() {
        $users = ORM::factory(parent::$model_class)->find_all();
        return $users;
    }

    /**
     * check if duplicate users exist in user table, for update user
     * @param <integer> $user_id
     * @param <string> $user_name
     * @return <type> if user_name exists and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_user_name($user_id, $user_name) {
        $user = ORM::factory(parent::$model_class)
                ->where('user_name', '=', $user_name)
                ->where('id', '!=', $user_id)
                ->where('status', '=', 'enable')
                ->find();
        if ($user->loaded())
            return true;
        else
            return false;
    }

    /**
     * check if duplicate users exist in user table, for update user
     * @param <integer> $user_id
     * @param <string> $email
     * @return <type> if email exists and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_email($user_id, $email) {
        $user = ORM::factory(parent::$model_class)
                ->where('email', '=', $email)
                ->where('id', '!=', $user_id)
                ->where('status', '=', 1)
                ->find();
        if ($user->loaded())
            return true;
        else
            return false;
    }

    /**
     * check if duplicate name exist in user table, for update user
     * @param <integer> $user_id
     * @param <string> $name
     * @return <type> if name exists in user_name or email fields and user id is not the same as the particular user in user table, return true; otherwise return false;
     */
    public static function duplicate_user_name_or_email($user_id, $name) {
        $user = ORM::factory(parent::$model_class)
                ->where('id', '!=', $user_id)
                ->where('status', '=', 1)
                ->and_where_open()
                ->where('email', '=', $name)
                ->or_where('user_name', '=', $name)
                ->and_where_close()
                ->find();
        if ($user->loaded()) {
            //App_Test::objectLog('exist_','exist_', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return true;
        } else {
            //App_Test::objectLog('last query',ORM::factory(parent::$model_class)->last_query(), __FILE__, __LINE__, __CLASS__, __METHOD__);
            //App_Test::objectLog('exist_','not exist_', __FILE__, __LINE__, __CLASS__, __METHOD__);
            return false;
        }
    }

    /**
     * set num_of_company_all field for all user 
     * user id info is in thread table 
     */
    public static function set_num_of_company_all() {
        $q = "SELECT user_id, COUNT(*) AS num FROM thread 
            WHERE service_cat_id=2 AND status=1 
            GROUP BY user_id";
        $query = DB::query(Database::SELECT, $q);
        $users = $query->as_object()->execute();
        foreach ($users as $user) {
            $arr = array('num_of_company_all' => $user->num);
            parent::update_record($user->user_id, $arr);
        }
    }

    /**
     * set num_of_discount_all field for all user 
     * user id info is in thread table instead of discount_all table
     */
    public static function set_num_of_discount_all() {
        $q = "SELECT user_id, COUNT(*) AS num FROM thread 
            WHERE service_cat_id=3 AND status=1 
            GROUP BY user_id";
        $query = DB::query(Database::SELECT, $q);
        $users = $query->as_object()->execute();
        foreach ($users as $user) {
            $arr = array('num_of_discount_all' => $user->num);
            parent::update_record($user->user_id, $arr);
        }
    }

    /**
     * set num_of_bulletin_all field for all user 
     * user id info is in thread table 
     */
    public static function set_num_of_bulletin_all() {
        $q = "SELECT user_id, COUNT(*) AS num FROM thread 
            WHERE service_cat_id=1 AND status=1 
            GROUP BY user_id";
        $query = DB::query(Database::SELECT, $q);
        $users = $query->as_object()->execute();
        foreach ($users as $user) {
            $arr = array('num_of_bulletin_all' => $user->num);
            parent::update_record($user->user_id, $arr);
        }
    }

    /**
     * when a thread is created/updated/deleted, 
     * num_of_XXXXXXX or num_of_XXXXXXX_all will be updated
     * @param int $thread_id 
     * @param array similar to array('num_of_bulletin_all'=>1,....); 
     */
    public static function update_nums_by_thread_id($thread_id, $arr) {
        $thread = Model_Thread::get_record($thread_id);
        $user_id = $thread->user_id;
        $user = Model_User::get_record($user_id);
        foreach ($arr as $col => $num) {
            //$num may be <0(positive when created), =0(sometimes unchanged when updated), >0(negative when deleted)
            $user->$col = intval($user->$col) + $num;  //
        }
        $user->save();
    }

    /**
     *
     * @param int $user_id 
     * @param int $channel_id 
     */
    public static function decrease_num_by_user_id_and_channel_id($user_id, $channel_id) {
        $user = Model_User::get_record($user_id);
        switch (intval($channel_id)) {
            case 1:
                $num = $user->num_of_bulletin - 1;
                $num = ($num > 0) ? $num : 0;
                $user->num_of_bulletin = $num;
                break;
            case 2:
                $num = $user->num_of_company - 1;
                $num = ($num > 0) ? $num : 0;
                $user->num_of_company = $num;
                break;
            case 3:
                $num = $user->num_of_discount - 1;
                $num = ($num > 0) ? $num : 0;
                $user->num_of_discount = $num;
                break;
            case 4:
                $num = $user->num_of_requirement - 1;
                $num = ($num > 0) ? $num : 0;
                $user->num_of_requirement = $num;
                break;
            case 5:
                $num = $user->num_of_secondhand - 1;
                $num = ($num > 0) ? $num : 0;
                $user->num_of_secondhand = $num;
                break;
            case 6:
                $num = $user->num_of_goods - 1;
                $num = ($num > 0) ? $num : 0;
                $user->num_of_goods = $num;
                break;
        }
        $user->save();
    }

    /**
     *
     * @param int $user_id 
     * @param int $channel_id 
     */
    public static function increase_num_by_user_id_and_channel_id($user_id, $channel_id) {
        $user = Model_User::get_record($user_id);
        switch (intval($channel_id)) {
            case 1:
                $user->num_of_bulletin = $user->num_of_bulletin + 1;
                break;
            case 2:
                $user->num_of_company = $user->num_of_company + 1;
                break;
            case 3:
                $user->num_of_discount = $user->num_of_discount + 1;
                break;
            case 4:
                $user->num_of_requirement = $user->num_of_requirement + 1;
                break;
            case 5:
                $user->num_of_secondhand = $user->num_of_secondhand + 1;
                break;
            case 6:
                $user->num_of_goods = $user->num_of_goods + 1;
                break;
        }
        $user->save();
    }

    public static function get_user($user_id) {
        $q = "SELECT u.*, g.group_name, s.region_name_en as suburb, 
        s.postcode as postcode, c.region_name_en as city_name_en, email
            FROM user u
            LEFT JOIN `group` g ON u.group_id=g.id
            LEFT JOIN `region` s ON s.id=u.suburb_id
            LEFT JOIN `region` c ON c.id=u.city_id
            WHERE u.id=$user_id
            LIMIT 0, 1";
        //echo $q;
        $query = DB::query(Database::SELECT, $q);
        $users = $query->as_object()->execute();
        return $users[0];
    }

    /**
     * check if a user has company
     * @param type $user_id
     * @return boolean 
     */
    public static function user_has_company($user_id) {
        $user = Model_User::get_record($user_id);
        if ($user && $user->num_of_company > 0)
            return true;
        else
            return false;
    }

}

