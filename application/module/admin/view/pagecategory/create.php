<form action="<?php echo ADMIN_HTML_ROOT . 'pagecategory/create'; ?>" method="post">
    <fieldset>
        <legend>Create a page category</legend>

        <dl>
            <dt>    
            Title:</dt><dd><input type="text" name="title" size="50" /></dd>
            <dt>Description:</dt><dd> <textarea cols="20" rows="10" name="description"></textarea></dd>
            <dt></dt><dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('description');
