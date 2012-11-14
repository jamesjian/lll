<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
?>
<form action="<?php echo ADMIN_HTML_ROOT . 'answer/create'; ?>" method="post">
    <fieldset>
        <legend>Create an Answer </legend>
        <dl>
            <dt> Question id:</dt><dd><input type="text" name="question_id" size="50"  value="<?php
            if (isset($_POST['question_id'])) echo $_POST['question_id'];?>"/></dd>
            <dt> User id:</dt><dd><input type="text" name="user_id" size="50"  value="<?php
            if (isset($_POST['user_id'])) echo $_POST['user_id'];?>"/></dd>
            
            <dt> Rank:</dt><dd><input type="text" name="rank" size="50"  value="<?php
            if (isset($_POST['rank'])) echo $_POST['rank'];?>"/>    </dd>
            <dt> Status:</dt>
            <dd><input type="radio" name="status" value="1" />Active    
                <input type="radio" name="status" value="0" />Inactive    </dd>
            <dt> Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php
            if (isset($_POST['content'])) echo $_POST['content'];?></textarea></dd>
             <dt> <input type="hidden" name="question_id" value="<?php if (isset($_POST['question_id']))
                echo $_POST['question_id']; else echo $question_id;?>" /></dt>
            <dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
