<?php
/**
 * only content, no content1 (for num_of_answers>0)
 */
?>
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
<form class="zx-front-form" name="answer_form" method="post" action="<?php echo USER_HTML_ROOT; ?>answer/update">
    <fieldset>
        <legend>更新回答</legend>
        <table>
            <tr>
                <td class="zx-front-table-title zx-front-required">内容:</td>
                <td class="table_input"> <textarea cols="10" rows="30" name="content"><?php
                if (isset($posted['content'])) echo $posted['content']; else echo $answer['content'];?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="aid" value="<?php echo $answer['id'];?>" />
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">更新</button>
                    <a href="<?php echo \App\Transaction\Html::get_previous_page(); ?>" />返回</a>
                </td>
            </tr>
        </table>
    </fieldset>
</form>

<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
