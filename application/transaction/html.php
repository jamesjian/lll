<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;

class Html {

    static $title = ' -- 问答';
    static $keyword = '问答';
    static $description = '问答';

    public static function set_title($title) {
        self::$title = $title . self::$title;
    }

    public static function get_title() {
        return self::$title;
    }

    public static function set_description($description) {
        self::$description = $description;
    }

    public static function get_description() {
        return self::$description;
    }

    public static function set_keyword($keyword) {
        self::$keyword = $keyword;
    }

    public static function get_keyword() {
        return self::$keyword;
    }

    /**
     * generate a snag URL such as this-is-a-slug-url
     * remove all invalid characters for an URL, such as all punctuation
     * @param string $title
     * currently generate it manually to avoid duplicate
     */
    public static function generate_url($title) {
        
    }

    public static function get_url() {
        
    }

    public static function goto_question_home_page() {
        header('Location: ' . FRONT_HTML_ROOT . 'question/all');
    }
    public static function goto_ad_home_page() {
        header('Location: ' . FRONT_HTML_ROOT . 'ad/all');
    }
    public static function goto_home_page() {
        header('Location: ' . HTML_ROOT);
    }

    public static function goto_user_home_page() {
        header('Location: ' . USER_HTML_ROOT . 'user/home');
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

    /**
      if has current page in SESSION, return it, otherwise return false
     */
    public static function goto_previous_page() {
        if (isset($_SESSION['current_page'])) {
            header('Location: ' . $_SESSION['current_page']);
        } else {
            self::goto_home_page();
        }
    }
    /**
      if has current page in SESSION, return it, otherwise return false
     */
    public static function goto_previous_admin_page() {
        if (isset($_SESSION['current_admin_page'])) {
            header('Location: ' . $_SESSION['current_admin_page']);
        } else {
            self::goto_home_page();
        }
    }
    /**
      if has current page in SESSION, return it, otherwise return false
     */
    public static function goto_previous_user_page() {
        if (isset($_SESSION['current_user_page'])) {
            header('Location: ' . $_SESSION['current_user_page']);
        } else {
            self::goto_home_page();
        }
    }

    //for user
    public static function remember_current_user_page() {
        $_SESSION['current_user_page'] = Route::get_url();
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
    public static function get_previous_user_page() {
        if (isset($_SESSION['current_user_page'])) {
            return $_SESSION['current_user_page'];
        } else {
            return false;
        }
    }
    public static function remember_current_admin_search_keyword($keyword) {
        $_SESSION['current_admin_search_keyword'] = $keyword;
    }

    /**
      if has current admin SEARCH KEYWORD in SESSION, return it, otherwise return false
     */
    public static function get_previous_admin_search_keyword() {
        if (isset($_SESSION['current_admin_search_keyword']) && $_SESSION['current_admin_search_keyword'] != '') {
            return $_SESSION['current_admin_search_keyword'];
        } else {
            return false;
        }
    }
    public static function remember_current_user_search_keyword($keyword) {
        $_SESSION['current_user_search_keyword'] = $keyword;
    }

    /**
     * for front end
      if has current user SEARCH KEYWORD in SESSION, return it, otherwise return false
     */
    public static function get_previous_user_search_keyword() {
        if (isset($_SESSION['current_search_keyword']) && $_SESSION['current_search_keyword'] != '') {
            return $_SESSION['current_search_keyword'];
        } else {
            return false;
        }
    }
    /**
     * for front end
     * @param string $keyword
     */
    public static function remember_current_search_keyword($keyword) {
        $_SESSION['current_search_keyword'] = $keyword;
    }

    /**
     * for front end
      if has current user SEARCH KEYWORD in SESSION, return it, otherwise return false
     */
    public static function get_previous_search_keyword() {
        if (isset($_SESSION['current_search_keyword']) && $_SESSION['current_search_keyword'] != '') {
            return $_SESSION['current_search_keyword'];
        } else {
            return false;
        }
    }

    /**
     * remember search result page rather than use new retrieve page
     * @return boolean
     */
    public static function previous_admin_page_is_search_page() {
        if (isset($_SESSION['current_admin_page']) && strpos($_SESSION['current_admin_page'], 'search') !==false) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * remember search result page rather than use new retrieve page
     * @return boolean
     */
    public static function previous_page_is_search_page() {
        if (isset($_SESSION['current_page']) && strpos($_SESSION['current_page'], 'search') !==false) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * remember search result page rather than use new retrieve page
     * @return boolean
     */
    public static function previous_user_page_is_search_page() {
        if (isset($_SESSION['current_user_page']) && strpos($_SESSION['current_user_page'], 'search') !==false) {
            return true;
        } else {
            return false;
        }
    }

}

