<?php
//this is splash page of the account
$question_link = USER_HTML_ROOT . 'question/user/' . $user['id'];
$answer_link = USER_HTML_ROOT . 'answer/user/' . $user['id'];
$ad_link = USER_HTML_ROOT . 'ad/user/' . $user['id'];
$password_link = USER_HTML_ROOT . 'user/password/' . $user['id'];
$image_link = USER_HTML_ROOT . 'user/image/' . $user['id'];
?>
    用户名:<?php echo $user['uname']; ?>, 电子邮箱:<?php echo $user['email']; ?>,
    总分:<?php echo $user['score']; ?>
<ul class="zx-front-user-menu">
    <li><a href="<?php echo $password_link; ?>">更新密码</a></li>
    <li><a href="<?php echo $image_link; ?>">更改头像</a>  </li>  
</ul>
<ul class="zx-front-user-menu">
    <li><a href="<?php echo $question_link; ?>">提问(<?php echo $num_of_questions; ?>)</a></li>
    <li><a href="<?php echo $question_link; ?>">回答(<?php echo $num_of_answers; ?>)</a></li>
    <li><a href="<?php echo $question_link; ?>">广告(<?php echo $num_of_ads;?>)</a></li>
</ul>


