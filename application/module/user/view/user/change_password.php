<?php
/*
 */
if (isset($errors)) {
    echo "<div  class='errormessage'>";
    foreach ($errors as $key => $message) {
        echo $message, BR;
    }
    echo "</div>";
}
App_Session::display_message();
?>
<form action="<?php echo MEMHTMLROOT; ?>user/change_password" method="post" id="user-form">
    <fieldset>
        <legend>重设密码</legend>
        <table>
            <tr>
                <td class="zx-front-table-title zx-front-required">请输入您现在的密码:</td>
                <td><input type="text" name="old_password" value=""  /></td>
            </tr>
            <tr>
                <td class="zx-front-table-title zx-front-required">请输入您的新密码:</dt>
            <td><input type="password" name="password1" value=""  /></td>
            </tr>
            <tr>
                <td class="zx-front-table-title zx-front-required">请再次输入您的新密码:</dt>
            <td><input type="password" name="password2" value=""  /></td>        
            </tr>
            <tr>
            <td><input type='hidden' name='sess' value="<?php echo $sess; ?>" />
            </td>
            <td><input type='submit' name='submit' value="修改" />
                <input type="reset" name="reset"  value="清空" />
            </td>
            </tr>
        </table>
    </fieldset>
</form>


