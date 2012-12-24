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
                <a href='<?php echo HTML_ROOT;?>' title='<?php echo SITENAME;?>'><img src="<?php echo HTML_IMAGE_ROOT . 'icon/logo.png';?>" title="this is logo"/></a>
            </div>
            <a href="<?php echo FRONT_HTML_ROOT; ?>user/logout" title="退出">退出</a>
            <div class="zx-front-search">
                <form>
                    <input name="question" size="50" /><input type="submit" value="查找问题" />
                </form>
            </div>
            <nav class='zx-front-top-menu'>
                <?php
                include 'menu.php';
                ?>
            </nav>
        </div>
        <div class="zx-front-clear-both"></div>