<div class='zx-front-footer'>
    <nav class='zx-front-bottom-menu'>
        <ul>
            <?php
            if ($article_cats) {
                foreach ($article_cats as $cat) {
                    $link = HTML_ROOT . 'front/article/category/' . $cat['title'];
                    ?>
                    <li><a href="<?php echo $link; ?>" title="<?php echo $cat['title']; ?>"><?php echo $cat['title']; ?></a></li>
                    <?php
                }
            }
            ?>
            <li><a href="<?php echo HTML_ROOT . 'about-us.php'; ?>">About Us</a></li>
            <li><a href="<?php echo HTML_ROOT . 'contact-us.php'; ?>">Contact Us</a></li>
            <li><a href="<?php echo HTML_ROOT . 'sitemap.php'; ?>">Sitemap</a></li>
        </ul>
    </nav>            
</div>
<script type="text/javascript" src="<?php echo HTML_ROOT . 'js/site.js'; ?>"></script>
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