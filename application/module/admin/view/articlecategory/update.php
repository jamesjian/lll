<form action="<?php echo ADMIN_HTML_ROOT . 'articlereply/update'; ?>" method="post">
    <fieldset>
        <legend>Create an article category</legend>
        <dl>
            <dt>Article:</dt><dd><?php echo $article['title']; ?>"</dd>
            <dt>Status:
            <?php
            if ($reply['status'] == \App\Model\Base\Articlereply::S_ACTIVE) {
                $active_checked = ' checked';
                $inactive_checked = '';
            } else {
                $inactive_checked = ' checked';
                $active_checked = '';
            }
            ?></dt><dd>
                <input type="radio" name="status" value="<?php echo \App\Model\Base\Articlereply::S_ACTIVE;?>" <?php echo $active_checked; ?>/>Active    
                <input type="radio" name="status" value="<?php echo \App\Model\Base\Articlereply::S_INACTIVE;?>"  <?php echo $inactive_checked; ?>/>Inactive      </dd>  
            <dt>Content: </dt><dd><textarea cols="20" rows="10" name="content"><?php echo $reply['content']; ?></textarea></dd>

            <dt><input type="hidden" name="id" value="<?php echo $cat['id']; ?>" /></dt><dd>
                <input type="submit" name="submit" value="update" /></dd>
        </dl></fieldset>
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('contents');
