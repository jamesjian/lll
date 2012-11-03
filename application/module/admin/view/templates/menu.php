<?php
/**
 * menu for administration
 * 
 * rel is for remembering current top menu, it will be used in admin_index.js and Controller_Admin_Session class
 */
if (\App\Transaction\Staff::staff_has_loggedin()) {
    //if has logged in
    $group_name = App_Staff::get_admin_group_name();

    $menu_arr = array(
        array('menu_name' => Home, 'link' => 'staff/home'),
        array('menu_name' => 'Article', 'link' => 'article/retrieve'),
        array('menu_name' => 'Article Category', 'link' => 'articlecategory/retrieve'),
        array('menu_name' => 'Page', 'link' => 'page/retrieve'),
        array('menu_name' => 'Page Category', 'link' => 'pagecategory/retrieve'),
        array('menu_name' => 'Region', 'link' => 'region/'),
        array('menu_name' => 'User', 'link' => 'user/retrieve'),
        array('menu_name' => 'Character', 'link' => 'character/retrieve'),
        array('menu_name' => 'Book', 'link' => 'book/retrieve'),
        array('menu_name' => 'Chapter', 'link' => 'chapter/retrieve'),
        array('menu_name' => 'Section', 'link' => 'section/retrieve',),
        array('menu_name' => 'Sitemap', 'link' => 'tool/sitemap'),
        array('menu_name' => 'Logout', 'link' => 'staff/logout'),
    );
    ?>
    <nav id="top-menu1">
        <ul>
            <?php
            $current_l1_menu = \App\Transaction\Session::get_admin_current_l1_menu();
            foreach ($menu_arr as $menu) {
                $link = ADMIN_HTML_ROOT . $menu['link'];
                $active_class = ($current_l1_menu == $menu['menu_name']) ? ' class="zx-admin-active-menu"' : '';
                echo "<li><a href='$link' class='$active_class'>", $menu['menu_name'], '</a></li>';
            }
            ?>      
        </ul>		
    </nav>
    <div class="clear-both"></div>	
    <div id="top-menu2">
        <ul>
            <?php
            $menu_arr = array(
                
                array('menu_name' => 'Region',
                    'submenu_arr' => array(
                        array('submenu_name' => '省', 'link' => 'region/retrieve_province'),
                        array('submenu_name' => '直辖市', 'link' => 'region/retrieve_manipucility'),
                        array('submenu_name' => '市', 'link' => 'region/retrieve_city'),
                        array('submenu_name' => '区/县', 'link' => 'region/retrieve_district'),
                        array('submenu_name' => '全部', 'link' => 'region/retrieve'),
                )),
                
            );
            foreach ($menu_arr as $menu) {
                if ($current_l1_menu == $menu['menu_name']) {
                    $current_l2_menu = \App\Transaction\Session::get_admin_current_l2_menu();
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