<div class='zx-front-left1'>
    <?php
    //similar to      FRONT_VIEW_PATH . 'question/question_list.php';
    //latest questions without pagination
    include FRONT_VIEW_PATH . 'question/question_list.php';
    ?>
</div>
<div class="zx-front-left2">
    点击<a href="<?php echo FRONT_HTML_ROOT . 'question/latest'; ?>">这里</a>可以查看更多最新问题， 
    点击<a href="<?php echo FRONT_HTML_ROOT . 'question/create'; ?>">这里</a>可以发布您的新问题， 
    要获得更多个性化服务， 请点击<a href="<?php echo FRONT_HTML_ROOT . 'user/register'; ?>">这里</a>注册用户， 
    或者如果您已注册， 点击<a href="<?php echo FRONT_HTML_ROOT . 'user/login'; ?>">这里</a>登录，
    点击<a href="<?php echo FRONT_HTML_ROOT . 'tag/ad'; ?>">这里</a>可以查看用户提供的最新信息。 
</div>
