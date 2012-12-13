<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
?>
<form action="<?php echo ADMIN_HTML_ROOT . 'answer/create'; ?>" method="post">
    <fieldset>
        <legend>Create an Answer </legend>
        <dl>
            <dt> Question id:</dt><dd><input type="text" name="qid" size="50"  value="<?php
            if (isset($_POST['qid'])) echo $_POST['qid'];?>"/></dd>
            <dt> User id:</dt><dd><input type="text" name="uid" size="50"  value="<?php
            if (isset($_POST['uid'])) echo $_POST['uid'];?>"/></dd>
            
            <dt> Rank:</dt><dd><input type="text" name="rank" size="50"  value="<?php
            if (isset($_POST['rank'])) echo $_POST['rank'];?>"/>    </dd>
            <dt> Status:</dt>
            <dd><input type="radio" name="status" value="1" />Active    
                <input type="radio" name="status" value="0" />Inactive    </dd>
            <dt> Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php
            if (isset($_POST['content'])) echo $_POST['content'];?></textarea></dd>
             <dt> <input type="hidden" name="qid" value="<?php if (isset($_POST['qid']))
                echo $_POST['qid']; else echo $qid;?>" /></dt>
            <dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
