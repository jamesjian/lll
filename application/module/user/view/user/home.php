<?php
//this is splash page of the account
$question_link = USER_HTML_ROOT . 'question/user/' . $user['id'];
$answer_link = USER_HTML_ROOT . 'answer/user/' . $user['id'];
$ad_link = USER_HTML_ROOT . 'ad/user/' . $user['id'];
$password_link = USER_HTML_ROOT . 'user/password/' . $user['id'];
$image_link = USER_HTML_ROOT . 'user/image/' . $user['id'];
echo 'id:', $user['id'],'     ', 'name:', $user['uname'], BR;
?>

我的问题<a href="<?php echo $question_link;?>"><?php echo $num_of_questions, BR; ?></a>
我的回答<a href="<?php echo $question_link;?>"><?php echo $num_of_answers, BR;?></a>
我的广告<a href="<?php echo $question_link;?>"><?php echo $num_of_ads, BR;?></a>
我的总分<?php echo $user['score'];?>
<a href="<?php echo $password_link;?>">更新密码</a>
<a href="<?php echo $image_link;?>">更改头像</a>

