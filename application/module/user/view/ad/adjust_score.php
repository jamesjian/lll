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
<form id="login_form" name="login_form" method="post" action="<?php echo FRONT_HTML_ROOT; ?>ad/adjust_score">
    <fieldset class="fyl_fieldset">
        <table>
            <tr>
                <td class="table_title required">标题:</td>
                <td class="table_input"> 
                    <?php echo $ad['title'];?>
                </td>
            </tr>
            <tr>
                <td class="table_title required">分值:</td>
                <td class="table_input"> 
                    <input type="text" name="score"  class="form_element"   id="score" 
                           value="<?php if (isset($posted['score'])) echo $posted['score']; else echo $ad['score'];?>"/>
                您的可用分值为<?php echo $user['score']-$user['invalid_score']+$ad['score'];?></td>
            </tr>            
            <tr>
                <td>
                    <input type="hidden" name="id" value="<?php echo $id;?>" />
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">更新分值</button>
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
