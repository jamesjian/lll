<form action="<?php echo ADMIN_HTML_ROOT . 'answer/update'; ?>" method="post">
    <fieldset>
        <legend>Update answer</legend>
        <dl>
            <dt>    Content: </dt>
            <dd><textarea cols="10" rows="30" name="content"><?php echo $article['content']; ?></textarea></dd>
            <dt> <input type="hidden" name="id" value="<?php echo $answer['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
