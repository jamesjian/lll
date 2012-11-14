<h4 style="padding-top:10px; padding-left:15px">找回登录密码</h4>
<div style="clear:both; height:0;"></div>
<form id="forgotten_password_form" name="forgotten_password_form" method="post" 
      action="<?php echo HTMLROOT . 'user/forgotten_password'; ?>">
    <fieldset class="fyl_fieldset">
        <table>
            <tr>
                <td class="required">请输入您的用户名或电子邮箱:</td>
                <td>
                    <input type="text" name="email" id="email" class="form_element"  value="<?php if (isset($posted['email'])) echo $posted['email']; ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <input type='hidden' name='sess' value="<?php echo $sess; ?>" />
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">提 交</button>
                </td>
            </tr>
        </table>
    </fieldset>
</form>
