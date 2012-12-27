<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">        
        <title><?php echo \App\Transaction\Html::get_title(); ?></title>
        <meta name="Keywords" content="<?php echo \App\Transaction\Html::get_keyword(); ?>" />
        <meta name="Description" content="<?php echo \App\Transaction\Html::get_description(); ?>">

        <link rel="stylesheet" type="text/css" href="<?php echo HTML_ROOT . 'css/site.css'; ?>" />            
        <link rel="stylesheet" type="text/css" href="<?php echo HTML_ROOT . 'css/front.css'; ?>" />            
        <!--[if IE]>
            <link  rel="stylesheet" type="text/css" href="/css/site_ie.css" />    
        <![endif]-->            
        <link rel="shortcut icon" href="<?php echo HTML_ROOT . 'image/icon/favicon.ico?v3'; ?>" />
        <script type="text/javascript" src="<?php echo HTML_ROOT . 'js/jquery/jquery-1.8.1.min.js'; ?>"></script>
    </head>
    <body>
        <div class='zx-front-body'>	
            <div class='zx-front-header'>
                <div class='zx-front-logo'>
                    <a href='<?php echo HTML_ROOT; ?>' title='<?php echo SITENAME; ?>'><img src="<?php echo HTML_IMAGE_ROOT . 'icon/logo.png'; ?>" title="this is logo"/></a>
                </div>
                <div class="zx-front-login">
                    <?php
                    if (\App\Transaction\User::user_has_loggedin()) {
                        ?>    
                        <a href="<?php echo FRONT_HTML_ROOT; ?>user/logout" title="退出" class="zx-front-top-menu-last">退出</a>
                        <?php
                    } else {
                        ?>
                        <a href="<?php echo FRONT_HTML_ROOT; ?>user/register" title="注册用户" class="zx-front-top-menu-first">注册用户</a>
                        <a href="<?php echo FRONT_HTML_ROOT; ?>user/login" title="登录" class="zx-front-top-menu-last">登录</a>
                        <?php
                    }
                    ?>
                </div>
                <?php
                //for active menu class
                $active_menu_item0 =
                        $active_menu_item1 = $active_menu_item2 = $active_menu_item3 = $active_menu_item4 =
                        $active_menu_item5 = $active_menu_item6 = $active_menu_item7 = '';
                $current_module = \Zx\Controller\Route::get_module();
                $current_action = \Zx\Controller\Route::get_action();
                if ($current_module == 'front') {
                    switch ($current_action) {
                        case 'home': $active_menu_item0 = 'zx-front-top-active-menu';
                            break;
                        case 'qpopular': $active_menu_item1 = 'zx-front-top-active-menu';
                            break;
                        case 'latest': $active_menu_item2 = 'zx-front-top-active-menu';
                            break;
                        case 'answered': $active_menu_item3 = 'zx-front-top-active-menu';
                            break;
                        case 'unanswered': $active_menu_item4 = 'zx-front-top-active-menu';
                            break;
                        case 'popular': $active_menu_item5 = 'zx-front-top-active-menu';
                            break;
                        case 'all': $active_menu_item6 = 'zx-front-top-active-menu';
                            break;
                        case 'apopular': $active_menu_item7 = 'zx-front-top-active-menu';
                            break;
                    }
                }
                ?>
                <nav class='zx-front-top-menu'>
                    <ul>
                        <li><a href="<?php echo HTML_ROOT; ?>" title="首页" class="zx-front-top-menu-first <?php echo $active_menu_item0; ?>">首页</a></li>
                        <li><a href="<?php echo FRONT_HTML_ROOT; ?>tag/qpopular"    title="问题分类" class="<?php echo $active_menu_item1; ?>">分类</a></li>
                        <li><a href="<?php echo FRONT_HTML_ROOT; ?>question/latest" title="最新问题" class="<?php echo $active_menu_item2; ?>">最新问题</a></li>
                        <li><a href="<?php echo FRONT_HTML_ROOT; ?>question/answered" title="待解答问题" class="<?php echo $active_menu_item3; ?>">已解答问题</a></li>
                        <li><a href="<?php echo FRONT_HTML_ROOT; ?>question/unanswered" title="待解答问题" class="<?php echo $active_menu_item4; ?>">待解答问题</a></li>
                        <li><a href="<?php echo FRONT_HTML_ROOT; ?>question/popular" title="待解答问题" class="<?php echo $active_menu_item5; ?>">最受关注问题</a></li>
                        <?php
                        /** only has tags for question in main menu, the link of tags for ads is display in tags section in all ad pages */
                        ?>
                        <li> <a href="<?php echo FRONT_HTML_ROOT; ?>user/all" title="全体用户列表" class="<?php echo $active_menu_item6; ?>">用户列表</a></li>
                        <li> <a href="<?php echo FRONT_HTML_ROOT; ?>tag/apopular" title="赞助信息" class="zx-front-top-menu-last <?php echo $active_menu_item7; ?>">赞助信息</a></li>
                    </ul>
                </nav>
                <!--
                <div class="zx-front-search">
                    <form action="<?php echo FRONT_HTML_ROOT; ?>question/search" method="post">
                        <input name="question" size="50" /><input type="submit" value="查找问题" />
                    </form>
                </div>-->
            </div>
            <div class="zx-front-clear-both"></div>