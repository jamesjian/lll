<h4>新问题</h4>
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
<form id="login_form" name="login_form" method="post" action="<?php echo FRONT_HTML_ROOT; ?>question/create">
    <fieldset class="fyl_fieldset">
        <table>
            <tr>
                <td class="table_title required">标题:</td>
                <td class="table_input"> 
                    <input type="text" name="title"  class="form_element"   id="title" 
                           value="<?php if (isset($posted['title'])) echo $posted['title']; ?>"/>
                </td>
            </tr>
            <tr>    <td class="table_title required"> State:</td>
                <td class="table_input"> <select name='state'>
                        <?php
                        foreach ($states as $state) {
                            echo "<option value='" . $state . "'";
                            if ($state == $posted['state']) {
                                echo " selected";
                            } elseif ($state == 'NSW') {
                                echo " selected";
                            }
                            echo ">" . $state . '</option>';
                        }
                        ?>
                    </select>
                </td>               
            </tr>
            <tr>
                <td class="table_title required">关键词:</td>
                <td class="table_input">  
                    <input type="text" name="tag_names"  class="form_element" 
                           value="<?php if (isset($posted['tag_names'])) echo $posted['tag_names']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="table_title required">内容:</td>
                <td class="table_input"> 
                    Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php if (isset($posted['content'])) echo $posted['content']; ?></textarea>
                </td>
                </tr>
            <tr>
                <td>
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">发布</button>
                    (如果你已登录， 新问题将会立即被发布， 如果你尚未登录， 新问题审核通过后才会显示。）
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/login">现在登录</a>或
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/register">注册新用户</a>

                </td>
            </tr>
        </table>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');