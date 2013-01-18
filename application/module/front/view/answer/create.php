<?php

use \Zx\Message\Message as Zx_Message;

Zx_Message::show_message();
//must provide title, content, tags and user id
?>
<form class="zx-front-form" action="<?php echo FRONT_HTML_ROOT . 'answer/reply/'; ?>" method="post">
    <fieldset>
        <legend><?php echo $question['title']; ?></legend>
        <table>
            <tr>
                <td>回答: </td>
                <td><textarea cols="10" rows="30" name="content"></textarea></td>
            </tr>
            <tr>
                <td> <input type="hidden" name="qid" value="<?php echo $question['id']; ?>" /></td>
                <td>
                    <button type='submit' name='submit' value="submit">发布</button>
                    <a href="<?php echo \App\Transaction\Html::get_previous_page(); ?>" />返回</a>
                    <?php
                    if (!\App\Transaction\User::user_has_loggedin()) {
                        ?>
                        ( 注册用户登录后发布新问题及回答问题可以获得积分。
                        <a href="<?php echo FRONT_HTML_ROOT; ?>user/register">注册新用户</a> 或 <a href="<?php echo FRONT_HTML_ROOT; ?>user/login">现在登录</a> )
                        <?php
                    }
                    ?>
                </td>
            </tr>    
        </table>
    </fieldset>    
</form>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
