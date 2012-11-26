<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
//must provide title, content, tags and user id
?>
<form action="<?php echo USER_HTML_ROOT . 'answer/link_ad'; ?>" method="post">
    <fieldset>
        <legend>Create question</legend>
        <dl>
            <dt>Answer Ids:
            1. <1000
            2. >1000
            3. 1,3,5,8,9
            
            </dt><dd><input type="text" name="answer_ids" size="50" value="<?php
            if (isset($_POST['answer_ids'])) echo $_POST['answer_ids'];?>"/></dd>
            <dt> <input type="hidden" name="ad_id" value="<?php if (isset($_POST['ad_id']))
                echo $_POST['ad_id']; else echo $ad_id;?>" /></dt>
            <dd><input type="submit" name="submit" value="Link" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
