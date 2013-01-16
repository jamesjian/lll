<form action="<?php echo ADMIN_HTML_ROOT . 'question/update_status'; ?>" method="post">
    <fieldset>
        <legend>Update answer status</legend>
        <dl>
            <dt>    Content (only for view, cannot be updated): </dt>
            <dd><textarea cols="10" rows="30" name="content"><?php echo $question['content']; ?></textarea></dd>
            <dt>    Status:</dt><dd><select name='status'>
                    <?php
                    foreach ($statuses as $val=>$text) {
                        echo "<option value='" . $val . "'";
                        if ($question['status'] == $val) {
                            echo " selected";
                        }
                        echo ">" . $text . '</option>';
                    }
                    ?>
                </select>
            </dd>
            <dt> <input type="hidden" name="id" value="<?php echo $question['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
