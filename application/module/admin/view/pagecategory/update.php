<form action="<?php echo ADMIN_HTML_ROOT . 'pagecategory/update'; ?>" method="post">
    <fieldset>
        <legend>Create a page category</legend>
        <dl>    
            <dt>Title:</dt><dd><input type="text" name="title" size="50" value="<?php echo $cat['title']; ?>"/></dd>
            <dt> Description: </dt><dd><textarea cols="20" rows="10" name="description"><?php echo $cat['description']; ?></textarea></dd>

            <dt><input type="hidden" name="id" value="<?php echo $cat['id']; ?>" /></dt><dd>
                <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('description');
