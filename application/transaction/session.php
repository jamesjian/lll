<?php

namespace App\Transaction;

use \Zx\Controller\Route;

class Session {

   

    /**
      @param $path_name such as 'article'
     */
    public static function set_ck_upload_path($path_name) {

        $_SESSION['CK_UPLOAD_PATH'] = $path_name;
    }

    /**
     */
    public static function get_ck_upload_path() {

        return $_SESSION['CK_UPLOAD_PATH'];
    }
    /*
     * sometimes we don't know how many existing levels are, we just append new breadcrum 
     * 
     */
    public static function set_new_level_breadcrumb($link, $title)
    {
        $breadcrumb_arr = (isset($_SESSION['breadcrumb'])) ? $_SESSION['breadcrumb'] : array();
        $level = count($breadcrumb_arr);
        self::set_breadcrumb($level, $link, $title);
    }
    /**
      everytime set a breadcrumb, remove all lower level breadcrumbs,
     * for example: previous breadcrumbs are   category->subcategory
     *              new breadcrumb is  news->article
     * when you replace 'category' with 'news', the 'subcategory' must be removed, 
     * otherwise will show news->subcategory before 'article' is added into the array.
     * @param <type> $level  0, 1, 2,...  0 is the first/highest level breadcrumb
     * @param <type> $link   html link
     * @param <type> $title
     * Todo: decide $level automatically, sometimes the action in the controller cannot decide the level of the breadcrumb,
     * especially for some ajax action
     */
    public static function set_breadcrumb($level, $link, $title) {
        $breadcrumb_arr = (isset($_SESSION['breadcrumb'])) ? $_SESSION['breadcrumb'] : array();
        //App_Test::objectLog('$breadcrumb_arr',$breadcrumb_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (count($breadcrumb_arr) > 0) {
            //App_Test::objectLog('$length',$length, __FILE__, __LINE__, __CLASS__, __METHOD__);
            //App_Test::objectLog('$level',$level, __FILE__, __LINE__, __CLASS__, __METHOD__);
            //remove all lower level breadcrumbs
            foreach ($breadcrumb_arr as $key => $value) {
                if ($key >= $level) {
                    unset($breadcrumb_arr[$key]);
                }
            }
        }
        //App_Test::objectLog('$breadcrumb_arr',$breadcrumb_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $breadcrumb_arr[$level] = array('link' => $link, 'title' => $title);
        //App_Test::objectLog('$breadcrumb_arr',$breadcrumb_arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $_SESSION['breadcrumb'] = $breadcrumb_arr;
    }

    /*
     * get breadcrumb
     * 
     * <a href='#'>aaaaa</a>><a href='#'>bbbbb</a>><a href='#'>ccccc</a>><a href='#'>ddddd</a>
     */

    public static function get_breadcrumb() {
        $breadcrumb_array = (isset($_SESSION['breadcrumb'])) ? $_SESSION['breadcrumb'] : array();
        $str = '';
        if (count($breadcrumb_array) > 0) {
            //$breadcrumb_array = $_SESSION['breadcrumb'];
            ksort($breadcrumb_array);
            foreach ($breadcrumb_array as $level=>$breadcrumb) {
        $str .= "<a href='{$breadcrumb['link']}' title='{$breadcrumb['title']}'" . 
                " class='zx-front-breadcrumb-item-$level'>{$breadcrumb['title']}</a>->";
            }
        }
        $str = substr($str, 0, -2);  //remove the trailing '->';
        return $str;
    }

    /**
     * level one menu
     * @param string $menu such as "Blog Category"
     */
    public static function set_front_current_l1_menu($menu) {
        $_SESSION['front_l1_menu'] = $menu;
    }

    /**
     * 
     * @return string level one menu
     */
    public static function get_front_current_l1_menu() {
        if (isset($_SESSION['front_l1_menu']))
            return $_SESSION['front_l1_menu'];
        else
            return '';
    }

    /**
     * category group 
     * @param string $group such as "服务"
     */
    public static function set_front_current_cat_group($group) {
        $_SESSION['front_cat_group'] = $group;
    }

    /**
     * 
     * @return string category group 
     */
    public static function get_front_current_cat_group() {
        if (isset($_SESSION['front_cat_group']))
            return $_SESSION['front_cat_group'];
        else
            return '';
    }

    /**
     * level one menu
     * @param string $menu such as "Blog Category"
     */
    public static function set_admin_current_l1_menu($menu) {
        $_SESSION['admin_l1_menu'] = $menu;
    }

    /**
     * 
     * @return string level one menu
     */
    public static function get_admin_current_l1_menu() {
        if (isset($_SESSION['admin_l1_menu']))
            return $_SESSION['admin_l1_menu'];
        else
            return '';
    }

    
//generateVerificationCode

    
}