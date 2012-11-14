<h4>用户登录</h4>
<div class="clear-both"></div>
<?php
if (isset($errors)) {
    echo "<div  class='errormessage'>";
    foreach ($errors as $key => $message) {
        echo $message, BR;
    }
    echo "</div>";
}
App_Session::display_message();
?>
<form id="login_form" name="login_form" method="post" action="<?php echo HTMLROOT; ?>user/login">
    <fieldset class="fyl_fieldset">
        <table>
            <tr>
                <td class="table_title required">您的用户名或电子邮箱:</td>
                <td class="table_input">  <input type="text" name="user_name"  class="form_element"   id="user_name" /></td>
            </tr>
            <tr>
                <td class="table_title required">您的用户密码:</td>
                <td class="table_input"> <input type="password" name="password"  class="form_element"   id="password" /><span id="caps_on">您现在输入的是大写字母</span></td>
            </tr>
            <tr>     
                <td></td>
                <td id="login_link">
                    <a href="<?php echo HTMLROOT; ?>user/forgotten_password">忘记密码？</a> | 
                    <a href="<?php echo HTMLROOT; ?>user/register">注册新用户</a> |
                    <a href="<?php echo HTMLROOT; ?>user/activation_link">重发激活邮件</a>
                </td>
            </tr>
            <tr>
                <td>
                    <input type='hidden' name='sess' value="<?php echo $sess; ?>" />
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">登 录</button>
                </td>
            </tr>
        </table>
    </fieldset>
</form>


