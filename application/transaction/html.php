<?php

namespace App\Transaction;

class Html {
    static $title = ' -- 问答';
    static $keyword = '问答';
    static $description = '问答';
    public static function set_title($title)
    {
        self::$title = $title . self::$title;
    }
    public static function get_title()
    {
        return self::$title;
    }
    public static function set_description($description)
    {
        self::$description = $description;
    }
    public static function get_description()
    {
        return self::$description;
    }
    public static function set_keyword($keyword)
    {
        self::$keyword = $keyword;
    }
    public static function get_keyword()
    {
        return self::$keyword;
    }
    /**
     * generate a snag URL such as this-is-a-snag-url
     * remove all invalid characters for an URL, such as all punctuation
     * @param string $title
     * currently generate it manually to avoid duplicate
     */
    public static function generate_url($title)
    {
        
    }
    public static function get_url()
    {
        
    }
    public static function goto_home_page()
    {
        header('Location: '. HTML_ROOT);
    }
    public static function goto_user_home_page()
    {
        header('Location: '. USER_HTML_ROOT . 'user/home');
    }
 public static function remember_current_page() {
        $_SESSION['current_page'] = Route::get_url();
    }

    /**
      if has current page in SESSION, return it, otherwise return false
     */
    public static function get_previous_page() {
        if (isset($_SESSION['current_page'])) {
            return $_SESSION['current_page'];
        } else {
            return false;
        }
    }

    //for admin
    public static function remember_current_admin_page() {
        $_SESSION['current_admin_page'] = Route::get_url();
    }

    /**
      if has current admin page in SESSION, return it, otherwise return false
     */
    public static function get_previous_admin_page() {
        if (isset($_SESSION['current_admin_page'])) {
            return $_SESSION['current_admin_page'];
        } else {
            return false;
        }
    }
}

