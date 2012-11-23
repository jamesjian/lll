<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
//must provide title, content, tags and user id
?>
<form action="<?php echo FRONT_HTML_ROOT . 'answer/reply/'; ?>" method="post">
    <fieldset>
        <legend>Answer <?php echo $question['title'];?></legend>
        <dl>
            <dt> Content: </dt><dd><textarea cols="10" rows="30" name="content"></textarea></dd>
            <dt> <input type="hidden" name="question_id" value="<?php echo $question['id'];?>" /></dt>
            <dd><input type="submit" name="submit" value="Answer" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
