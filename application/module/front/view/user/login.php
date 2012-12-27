
<div class="clear-both"></div>
<?php
if (isset($errors)) {
    echo "<div  class='errormessage'>";
    foreach ($errors as $key => $message) {
        echo $message, BR;
    }
    echo "</div>";
}
\Zx\Message\Message::show_message();
?>
<form class="zx-front-login-form" name="login_form" method="post" action="<?php echo FRONT_HTML_ROOT; ?>user/login">
    <fieldset>
        <legend>用户登录</legend>
        <table>
            <tr>
                <td class="zx-front-table-title zx-front-required">您的用户名或电子邮箱:</td>
                <td class="">  <input type="text" name="uname"  class="form_element"   id="uname" /></td>
            </tr>
            <tr>
                <td class="zx-front-table-title zx-front-required">您的用户密码:</td>
                <td class=""> <input type="password" name="password"  class="form_element"   id="password" /><span id="caps_on">您现在输入的是大写字母</span></td>
            </tr>
            <tr>     
                <td></td>
                <td id="login_link">
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/forgotten_password">忘记密码？</a> | 
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/register">注册新用户</a> |
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/activation_link">重发激活邮件</a>
                </td>
            </tr>
            <tr>
                <td>
                    <input type='hidden' name='sess' value="<?php //echo $sess; ?>" />
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">登 录</button>
                </td>
            </tr>
        </table>
    </fieldset>
</form>


