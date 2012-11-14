<?php ?>
<div id="main-content">
    <div id="left-side" class="grid" style="border-left:solid 1px #db5800;">
        <h4 style="padding-top:10px; padding-left:15px">免费注册帐户</h4>
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
        <div style="clear:both; height:0;"></div>
        <form id="register_form" name="register_form" method="post" action="<?php echo HTMLROOT; ?>user/register">
            <fieldset class="fyl_fieldset">
                <table>
                    <tr>
                        <td class="table_title required">帐户名称:
                            
                    </td>
                        <td>  
                            <input type="text" name="user_name" id="user_name" class="form_element" value="<?php if (isset($posted['user_name'])) echo $posted['user_name']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                           <a href="<?php echo HTMLROOT . "user/check_account"; ?>" id="check_account" title="点击此链接检查输入的账户是否可用">查查账户是否可用</a>
                    <span id="account_message"></span> 
                        </td>
                    </tr>
                    <tr>
                        <td class="table_title required">邮箱地址:</td>
                        <td>  <input type="text" name="email" id="email" class="form_element"  value="<?php if (isset($posted['email'])) echo $posted['email']; ?>"   />
                        </td>
                    </tr>
                    <tr>
                        <td class="table_title required">帐户密码:</td>
                        <td> <input type="password" name="password1" class="form_element"  id="password1" /> </td>
                    </tr>
                    <tr>
                        <td class="table_title required">密码确定:</td>
                        <td> <input type="password" name="password2" class="form_element"  id="password2" /> </td>
                    </tr>
                    <tr>
                        <td class="table_title required">验证码: </td>
                        <td class="table_input">请正确输入验证代码</td>
                    </tr>
                <tr>    
                    <td colspan="2">
                <div id="vcode">
                    <input type="text" name="vcode"  size="20" id="vcode" /><img src="/user/vcode" id="vcode_img"/> 
                    <a href="<?php echo HTMLROOT . "user/vcode"; ?>" id="refresh_vcode" title="换一个验证码">
                        <img src="<?php echo HTMLIMAGEROOT . 'icon/Button-Refresh-icon.png'; ?>" alt="换一个验证码"/><span id="not_clear">看不清楚? 刷新一下</span></a>
                </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="table_input">
                        当您提交注册信息时， 表明您完全同意并接受我们的<a href="<?php echo HTMLROOT . 'article/terms';?>" target="_blank">用户使用条款和限制</a>。 
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
    </div>
</div>