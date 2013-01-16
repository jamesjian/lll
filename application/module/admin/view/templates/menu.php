<?php

use \App\Transaction\Staff as Transaction_Staff;
use \App\Transaction\Session as Transaction_Session;

/**
 * menu for administration
 * 
 * rel is for remembering current top menu, it will be used in admin_index.js and Controller_Admin_Session class
 */
if (Transaction_Staff::staff_has_loggedin()) {
    //if has logged in
    //$group_name = Transaction_Staff::get_admin_group_name();

    $menu_arr = array(
        array('menu_name' => 'Home', 'link' => 'staff/home'),
        array('menu_name' => 'Article', 'link' => 'article/retrieve'),
        array('menu_name' => 'Article Category', 'link' => 'articlecategory/retrieve'),
        array('menu_name' => 'Page', 'link' => 'page/retrieve'),
        array('menu_name' => 'Page Category', 'link' => 'pagecategory/retrieve'),
        array('menu_name' => 'Region', 'link' => 'region/'),
        array('menu_name' => 'User', 'link' => 'user/retrieve'),
        array('menu_name' => 'Question', 'link' => 'question/retrieve'),
        array('menu_name' => 'Answer', 'link' => 'answer/retrieve'),
        array('menu_name' => 'Ad', 'link' => 'ad/retrieve'),
        array('menu_name' => 'Tag', 'link' => 'tag/retrieve'),
        array('menu_name' => 'Claim', 'link' => 'claim/retrieve'),
        array('menu_name' => 'Body', 'link' => 'body/retrieve'),
        array('menu_name' => 'Sitemap', 'link' => 'tool/sitemap'),
        array('menu_name' => 'Logout', 'link' => 'staff/logout'),
    );
    ?>
    <nav id="zx-admin-top-menu1">
        <ul>
            <?php
            $current_l1_menu = Transaction_Session::get_admin_current_l1_menu();
            foreach ($menu_arr as $menu) {
                $link = ADMIN_HTML_ROOT . $menu['link'];
                $active_class = ($current_l1_menu == $menu['menu_name']) ? ' class="zx-admin-active-menu"' : '';
                echo "<li><a href='$link' class='$active_class'>", $menu['menu_name'], '</a></li>';
            }
            ?>      
        </ul>		
    </nav>
    <div class="zx-clear-both"></div>	
    <div id="zx-admin-top-menu2">
        <ul>
            <?php
            $menu_arr = array(
                array('menu_name' => 'Claim',
                    'submenu_arr' => array(
                        array('submenu_name' => 'Question', 'link' => 'claim/retrieve_by_item_type/1'),
                        array('submenu_name' => 'Answer', 'link' => 'claim/retrieve_by_item_type/2'),
                        array('submenu_name' => 'Ad', 'link' => 'claim/retrieve_by_item_type/3'),
                    )
                ),
                array('menu_name' => 'Question',
                    'submenu_arr' => array(
                        array('submenu_name' => 'All', 'link' => 'question/retrieve/1'),
                        array('submenu_name' => 'claimed', 'link' => 'question/retrieve_claimed/1'),
                        array('submenu_name' => 'Correct', 'link' => 'question/retrieve_correct/1'),
                        array('submenu_name' => 'Deleted', 'link' => 'question/retrieve_deleted/1'),
                        array('submenu_name' => 'Disabled', 'link' => 'question/retrieve_disabled/1'),
                    )
                ),
                array('menu_name' => 'Answer',
                    'submenu_arr' => array(
                        array('submenu_name' => 'All', 'link' => 'answer/retrieve/1'),
                        array('submenu_name' => 'claimed', 'link' => 'answer/retrieve_claimed/1'),
                        array('submenu_name' => 'Correct', 'link' => 'answer/retrieve_correct/1'),
                        array('submenu_name' => 'Deleted', 'link' => 'answer/retrieve_deleted/1'),
                        array('submenu_name' => 'Disabled', 'link' => 'answer/retrieve_disabled/1'),
                    )
                ),
                array('menu_name' => 'Ad',
                    'submenu_arr' => array(
                        array('submenu_name' => 'All', 'link' => 'ad/retrieve/1'),
                        array('submenu_name' => 'claimed', 'link' => 'ad/retrieve_claimed/1'),
                        array('submenu_name' => 'Correct', 'link' => 'ad/retrieve_correct/1'),
                        array('submenu_name' => 'Deleted', 'link' => 'ad/retrieve_deleted/1'),
                        array('submenu_name' => 'Disabled', 'link' => 'ad/retrieve_disabled/1'),
                        array('submenu_name' => 'Inactive', 'link' => 'ad/retrieve_inactive/1'),
                    )
                ),
            );
            foreach ($menu_arr as $menu) {
                if ($current_l1_menu == $menu['menu_name']) {
                    $current_l2_menu = Transaction_Session::get_admin_current_l2_menu();
                    foreach ($menu['submenu_arr'] as $submenu) {
                        $active_class = ($current_l2_menu == $submenu['submenu_name']) ? 'zx-admin-active-menu' : '';
                        echo "<li><a href='$link' class='$active_class'>", $submenu['submenu_name'], '</a></li>';
                    }
                }
            }
            ?>
        </ul>		
    </div>
    <?php
}  //if has logged in
?>
<div class="zx-clear-both"></div>	