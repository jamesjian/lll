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
    <body class='zx-front-body'>	
        <div class='zx-front-header'>
            <div class='zx-front-logo'>
                <a href='<?php echo FRONT_HTML_ROOT; ?>common/sitemap.php' title='<?php echo SITENAME;?>'><?php echo SITENAME;?></a>
            </div>
            <div>
                <a href="<?php echo FRONT_HTML_ROOT;?>user/register" title="注册用户">注册用户</a>
                <a href="<?php echo FRONT_HTML_ROOT;?>user/login" title="注册用户">登录</a>
            </div>
            <div class="zx-front-search">
                <form action="<?php echo FRONT_HTML_ROOT;?>question/search" method="post">
                    <input name="question" size="50" /><input type="submit" value="查找问题" />
                </form>
            </div>
            <nav class='zx-front-top-menu'>
                <a href="<?php echo FRONT_HTML_ROOT;?>question/retrieve" title="最新问题">最新问题</a>
                <a href="<?php echo FRONT_HTML_ROOT;?>qyestion/unanswered" title="待解答问题">待解答问题</a>
                <a href="<?php echo FRONT_HTML_ROOT;?>qyestion/answered" title="待解答问题">已解答问题</a>
                <a href="<?php echo FRONT_HTML_ROOT;?>qyestion/answered" title="待解答问题">最受关注问题</a>
                <?php 
                /** only has tags for question in main menu, the link of tags for ads is display in tags section in all ad pages */
                ?>
                <a href="<?php echo FRONT_HTML_ROOT;?>tag/question" title="所有分类">所有问题分类</a>
                <a href="<?php echo FRONT_HTML_ROOT;?>user/retrieve" title="用户列表">用户列表</a>
                <a href="<?php echo FRONT_HTML_ROOT;?>ad/retrieve" title="最新信息">最新信息</a>
            </nav>
        </div>
        <div class="zx-front-clear-both"></div>