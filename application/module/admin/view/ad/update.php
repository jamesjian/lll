<form action="<?php echo ADMIN_HTML_ROOT . 'question/update'; ?>" method="post">
    <fieldset>
        <legend>Update question</legend>
        <dl>
            <dt> Title:</dt>
            <dd><input type="text" name="title" size="50" value="<?php if (isset($_POST['title'])) echo $_POST['title']; else echo $question['title']; ?>"/></dd>
            <dt>    State:</dt><dd><select name='state'>
                    <?php
                    foreach ($states as $state) {
                        echo "<option value='" . $state . "'";
                        if ($question['state'] == $state) {
                            echo " selected";
                        }
                        echo ">" . $state . '</option>';
                    }
                    ?>
                </select>
            </dd>            
            <dt>Tags:</dt><dd><input type="text" name="tnames" size="50" value="<?php if (isset($_POST['title'])) echo $_POST['title']; else echo $question['tnames']; ?>"/></dd>
            <dt>Rank:</dt><dd><input type="text" name="rank" size="50"  value="<?php if (isset($_POST['rank'])) echo $_POST['rank']; else echo $question['rank']; ?>"/>        </dd>
            <dt>    Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php if (isset($_POST['content'])) echo $_POST['content']; else echo $question['content']; ?></textarea></dd>
            <dt>    Status:</dt>
            <dd>
                <?php
                if ($question['status'] == '1') {
                    $active_checked = ' checked';
                    $inactive_checked = '';
                } else {
                    $inactive_checked = ' checked';
                    $active_checked = '';
                }
                ?>
                <input type="radio" name="status" value="1" <?php echo $active_checked; ?>/>Active    
                <input type="radio" name="status" value="0"  <?php echo $inactive_checked; ?>/>Inactive     
            </dd>
            <dt> <input type="hidden" name="id" value="<?php echo $question['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
