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

}

