<form action="<?php echo ADMIN_HTML_ROOT . 'article/create'; ?>" method="post">
    <fieldset>
        <legend>Create article</legend>
        <dl>
            <dt>Title:</dt><dd><input type="text" name="title" size="50" /></dd>
            <dt> Abstract:</dt><dd><input type="text" name="abstract" size="50" /></dd>
            <dt> Keyword:</dt><dd><input type="text" name="keyword" size="50" /></dd>
            <dt> Views:</dt><dd><input type="text" name="num_of_views" size="50" />    </dd>
            <dt> Status:</dt><dd><input type="radio" name="status" value="1" />Active    
                <input type="radio" name="status" value="0" />Inactive    </dd>
            <dt> Content: </dt><dd><textarea cols="10" rows="30" name="content"></textarea></dd>
            <dt> Category:</dt><dd><select name='cat_id'>
                    <?php
                    foreach ($cats as $cat) {
                        echo "<option value='" . $cat['id'] . "'>" . $cat['title'] . '</option>';
                    }
                    ?>
                </select></dd>
            <dt> </dt><dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
