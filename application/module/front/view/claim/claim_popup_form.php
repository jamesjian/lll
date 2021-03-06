<?php
/**
 * if loggedin no user fields
 * else has user fields
 */
$link =  FRONT_HTML_ROOT . "claim/create/$item_type/$item_id";
?>
<form class="zx-front-form" name="claim_form" method="post" action="<?php echo $link;?>">
    <fieldset>
        <legend><?php echo CLAIM_TITLE;?></legend>
        <table>
            <?php
            if ($loggedin) {
            ?>
            <tr>
                <td class="zx-front-table-title zx-front-required">您的用户名或电子邮箱:</td>
                <td class="">  <input type="text" name="uname"  class="form_element"   id="uname" /></td>
            </tr>
            <tr>
                <td class="zx-front-table-title zx-front-required">您的用户密码:</td>
                <td class=""> <input type="password" name="password"  class="form_element"   id="password" /><span id="caps_on">您现在输入的是大写字母</span></td>
            </tr>
            <?php 
            }
            ?>
            <tr>
                <td class="zx-front-table-title zx-front-required">请选择理由:</td>
                <td class=""> 
                    <select name="cat_id">
                        <?php 
                        foreach ($cats as $cat_id=>$cat_name) {
                            echo "<option value='$cat_id'>$cat_name</option>";
                        }
                        ?>
                    </select>    
                    </td>
            </tr>            
            <?php
            if ($loggedin) {
            ?>            
            <tr>     
                <td></td>
                <td id="login_link">
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/forgotten_password">忘记密码？</a> | 
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/register">注册新用户</a> |
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/activation_link">重发激活邮件</a>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td></td>
                <td>
                    <button type='submit' name='submit' value="submit"><?php echo CLAIM_TITLE;?></button>
                </td>
            </tr>
        </table>
    </fieldset>
</form>