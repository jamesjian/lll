<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
//must provide title, content, tags and user id
?>
<form action="<?php echo USER_HTML_ROOT . 'question/create'; ?>" method="post">
    <fieldset>
        <legend>Create question</legend>
        <dl>
            <dt>Title:</dt><dd><input type="text" name="title" size="50" value="<?php
            if (isset($_POST['title'])) echo $_POST['title'];?>"/></dd>
            <dt> User id:</dt><dd><input type="text" name="uid" size="50"  value="<?php
            if (isset($_POST['uid'])) echo $_POST['uid'];?>"/></dd>
            <dt> Tags:至少一个关键词， 最多五个关键词， 多个关键词之间以@符号分割， 例如留学@移民@中介@培训</dt><dd><input type="text" name="tnames" size="50"  value="<?php
            if (isset($_POST['tnames'])) echo $_POST['tnames'];?>"/></dd>
            <dt> Status:</dt>
            <dd><input type="radio" name="status" value="1" />Active    
                <input type="radio" name="status" value="0" />Inactive    </dd>
            <dt> Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php
            if (isset($_POST['content'])) echo $_POST['content'];?></textarea></dd>
            <dt> <input type="hidden" name="uid" value="<?php if (isset($_POST['uid']))
                echo $_POST['uid']; else echo $uid;?>" /></dt>
            <dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
