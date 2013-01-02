</div>
<div class='zx-front-remind'>
    点击<a href="<?php echo FRONT_HTML_ROOT . 'question/latest'; ?>">这里</a>可以查看更多最新问题， 
    点击<a href="<?php echo FRONT_HTML_ROOT . 'question/create'; ?>">这里</a>可以发布您的新问题， 
    <?php
    if (!\App\Transaction\User::user_has_loggedin()) {
        ?>        
        要获得更多个性化服务， 请点击<a href="<?php echo FRONT_HTML_ROOT . 'user/register'; ?>">这里</a>注册用户， 
        或者如果您已注册， 点击<a href="<?php echo FRONT_HTML_ROOT . 'user/login'; ?>">这里</a>登录，
        <?php
    }
    ?>
    点击<a href="<?php echo FRONT_HTML_ROOT . 'tag/ad'; ?>">这里</a>可以查看用户提供的最新信息。 
</div>
<div class='zx-front-footer'>
    <nav class='zx-front-bottom-menu'>
        <ul>
            <?php
            /*
              if ($article_cats) {
              foreach ($article_cats as $cat) {
              $link = HTML_ROOT . 'front/article/category/' . $cat['title'];
              ?>
              <li><a href="<?php echo $link; ?>" title="<?php echo $cat['title']; ?>"><?php echo $cat['title']; ?></a></li>
              <?php
              }
              }
             * 
             */
            ?>
            <li><a href="<?php echo HTML_ROOT . 'terms-and-conditions.php'; ?>">使用条款</a></li>
            <li><a href="<?php echo HTML_ROOT . 'privacy-protection.php'; ?>">隐私保护</a></li>
            <li><a href="<?php echo HTML_ROOT . 'faqs.php'; ?>">常见问题</a></li>
            <li><a href="<?php echo HTML_ROOT . 'score.php'; ?>">积分规则</a></li>
            <li><a href="<?php echo HTML_ROOT . 'about-us.php'; ?>">关于我们</a></li>
            <li><a href="<?php echo HTML_ROOT . 'contact-us.php'; ?>">联系我们</a></li>
            <li><a href="<?php echo HTML_ROOT . 'sitemap.php'; ?>">网站地图</a></li>
        </ul>
    </nav>            
    <div class='zx-front-disclaimer'>
        声明： lll.com.au(以下简称本网站）的所有问题， 所有回答以及所有广告信息均由本网站的注册用户或匿名用户提供， 而不是由本网站提供（特别声明的除外)。 本网站不保证任何信息的准确和有效， 
        所有信息仅供参考， 任何用户根据本网站的信息作出行动之前务必自行判断或请相关专业人士（特别是法律， 医疗， 财务等方面）协助判断， 任何用户
        根据本网站的信息而受到的任何损失或伤害都由自己承担， 本网站不承担任何直接或连带责任。     
    </div>
</div>
<div id="zx-front-dialog"></div>
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