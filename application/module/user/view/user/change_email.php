<?php
/*
 */
if (isset($errors)){
    echo "<div  class='errormessage'>";
    foreach ($errors as $key=>$message){
            echo $message, BR;
    }
    echo "</div>";
}
App_Session::display_message();
?>
<form action="<?php echo MEMHTMLROOT;?>user/change_email" method="post" id="user-form">
    <fieldset>
        <legend>重设电子邮箱</legend>
        <dt>请输入您的电子邮箱(请注意： 您的电子邮箱必须真实有效并且需要重新激活您的账户):</dt>
        <dd><input type="text" name="email" value=""  /></dd>
        <dt>
            <input type='hidden' name='sess' value="<?php echo $sess;?>" />
        </dt>
        <dd><input type='submit' name='submit' value="修改" />
            当您重设电子邮箱后， 我们会发送激活邮件到您的新邮箱， 请检查您的新邮箱并激活您的账户
            <input type="reset" name="reset"  value="清空" />
        </dd>
    </fieldset>
</form>


