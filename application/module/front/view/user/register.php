<?php ?>
<div class="zx-front-left1">
    <div id="left-side" class="grid">
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
        <div class="zx-front-clear-both"></div>
        <form class="zx-front-form" name="register_form" method="post" action="<?php echo FRONT_HTML_ROOT; ?>user/register">
            <fieldset>
                <legend>注册新用户</legend>
                <table>
                    <tr>
                        <td></td>
                        <td><a href="<?php echo HTML_ROOT . 'faqs.php#user';?>">注册用户有什么好处？</a></td>
                    </tr>
                    <tr>
                        <td class="zx-front-table-title zx-front-required">帐户名称:</td>
                        <td>  
                            <input type="text" name="uname" id="uname" class="form_element" value="<?php if (isset($posted['uname'])) echo $posted['uname']; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <a href="<?php echo FRONT_HTML_ROOT . "user/check_account"; ?>" id="check_account" title="查查这个账户可以用吗？">我能用这个账户或邮箱吗？</a>
                            <span id="account_message"></span> 
                        </td>
                    </tr>
                    <tr>
                        <td class="zx-front-table-title zx-front-required">邮箱地址:</td>
                        <td>  <input type="text" name="email" id="email" class="form_element"  value="<?php if (isset($posted['email'])) echo $posted['email']; ?>"   />
                        </td>
                    </tr>
                    <tr>
                        <td class="zx-front-table-title zx-front-required">帐户密码:</td>
                        <td> <input type="password" name="password1" class="form_element"  id="password1" /> </td>
                    </tr>
                    <tr>
                        <td class="zx-front-table-title zx-front-required">密码确定:</td>
                        <td> <input type="password" name="password2" class="form_element"  id="password2" /> </td>
                    </tr>
                    <tr>
                        <td class="zx-front-table-title zx-front-required">验证码: </td>
                        <td class="table_input">请正确输入六位验证代码（不分大小写）</td>
                    </tr>

                    <tr>    
                        <td colspan="2">
                            <div id="vcode">
                                <img src="<?php echo FRONT_HTML_ROOT; ?>user/vcode" id="vcode_img"/> 
                                <a href="<?php echo FRONT_HTML_ROOT . "user/vcode"; ?>" id="refresh_vcode" title="换一个验证码">
                                    <img src="<?php echo HTML_IMAGE_ROOT . 'icon/Button-Refresh-icon.png'; ?>" alt="换一个验证码"/><span id="not_clear">看不清楚? 另选一个</span></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="text" name="vcode"  size="20" id="vcode" /></td>
                    </tr>                    
                    <tr>
                        <td></td>
                        <td class="table_input">
                            当您提交注册信息时， 表明您完全同意并接受我们的<a href="<?php echo FRONT_HTML_ROOT . 'article/terms'; ?>" target="_blank">用户使用条款和限制</a>。 
                        </td>
                    </tr>
                    <tr>
                        <td>
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