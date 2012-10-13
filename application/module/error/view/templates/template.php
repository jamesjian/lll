<!DOCTYPE html>
<html>
    <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">        
        <title><?php echo $title; ?></title>
        <meta name="keywords" content="<?php echo $keyword; ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo HTML_ROOT . '/css/site.css'; ?>" />            
        <!--[if IE]>
            <link  rel="stylesheet" type="text/css" href="/css/site_ie.css" />    
        <![endif]-->            
<link rel="shortcut icon" href="<?php echo HTML_ROOT .'/image/icon/favicon.ico';?>" />
    </head>
    <body>
       this is an error page
        <?php
        echo $content;
        ?>

        this is footer of template
        <script type="text/javascript" src="<?php echo HTML_ROOT . 'js/jquery/jquery-1.8.1.min.js';?>"></script>
        <script type="text/javascript" src="<?php echo HTML_ROOT . 'js/site.js';?>"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35557322-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>        
    </body>
</html>