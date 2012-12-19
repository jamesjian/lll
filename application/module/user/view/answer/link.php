<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
//must provide title, content, tags and user id
?>
<form action="<?php echo USER_HTML_ROOT . 'answer/link_ad'; ?>" method="post">
    <fieldset>
        <legend>连接广告与回答</legend>
        <dl>
            <dt>请输入回答ID, 可以有以下几种格式
            例1. <1000， 我的所有ID小于1000的回答都显示本广告
            例2. >1000， 我的所有ID大于1000的回答都显示本广告
            例3. between 1000, 2000， 我的所有ID大于1000且小于2000的回答都显示本广告
            例4. 1,3,5,8,9，  我的ID是1,3,5,8,9的回答都显示本广告
            
            </dt><dd><input type="text" name="aids" size="50" value="<?php
            if (isset($_POST['aids'])) echo $_POST['aids'];?>"/></dd>
            <dt> <input type="hidden" name="ad_id" value="<?php if (isset($_POST['ad_id']))
                echo $_POST['ad_id']; else echo $ad_id;?>" /></dt>
            <dd><input type="submit" name="submit" value="Link" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
