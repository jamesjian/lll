<form action="<?php echo ADMIN_HTML_ROOT . 'page/update'; ?>" method="post">
    <fieldset>
        <legend>Create a page </legend>
        <dl>      
            <dt>Title:</dt><dd><input type="text" name="title" size="50" value="<?php echo $page['title']; ?>"/></dd>
            <dt>Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php echo $page['content']; ?></textarea></dd>
            <dt> Category:</dt><dd><select name='cat_id'>
                    <?php
                    foreach ($cats as $cat) {
                        echo "<option value='" . $cat['id'] . "'";
                        if ($page['cat_id'] == $cat['id']) {
                            echo " selected";
                        }
                        echo ">" . $cat['title'] . '</option>';
                    }
                    ?>
                </select></dd>
            <dt><input type="hidden" name="id" value="<?php echo $page['id']; ?>" /></dt><dd>
                <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
