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
<form action="<?php echo MEMHTMLROOT;?>user/change_password" method="post" id="user-form">
    <fieldset>
        <legend>重设密码</legend>
        <dt>请输入您现在的密码:</dt>
        <dd><input type="text" name="old_password" value=""  /></dd>
        <dt>请输入您的新密码:</dt>
        <dd><input type="password" name="password1" value=""  /></dd>
        <dt>请再次输入您的新密码:</dt>
        <dd><input type="password" name="password2" value=""  /></dd>        
        
        <dt>
            <input type='hidden' name='sess' value="<?php echo $sess;?>" />
        </dt>
        <dd><input type='submit' name='submit' value="修改" />
            <input type="reset" name="reset"  value="清空" />
        </dd>
    </fieldset>
</form>


