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
<form id="login_form" name="login_form" method="post" action="<?php echo FRONT_HTML_ROOT; ?>ad/create">
    <fieldset class="fyl_fieldset">
        <table>
            <tr>
                <td class="table_title required">标题:</td>
                <td class="table_input"> 
                    <input type="text" name="title"  class="form_element"   id="title" 
                           value="<?php if (isset($posted['title'])) echo $posted['title']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="table_title required">关键词:</td>
                <td class="table_input">  
                    <input type="text" name="tnames"  class="form_element" 
                           value="<?php if (isset($posted['tnames'])) echo $posted['tnames']; ?>"/>
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
