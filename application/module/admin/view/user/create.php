<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
?>
<form action="<?php echo ADMIN_HTML_ROOT . 'user/create'; ?>" method="post">
    <fieldset>
        <legend>Create a user</legend>
        <dl>      
            <dt>Name:</dt><dd><input type="text" name="user_name" size="50" value="<?php
            if (isset($_POST['user_name'])) echo $_POST['user_name'];?>"/></dd>
            <dt> Password: </dt><dd><input type="text" name="password" size="50"  value="<?php
            if (isset($_POST['password'])) echo $_POST['password'];?>"/></dd>
            <dt> Email: </dt><dd><input type="text" name="email" size="50"  value="<?php
            if (isset($_POST['email'])) echo $_POST['email'];?>"/></dd>
            <dt> Rank: </dt><dd><input type="text" name="rank" size="50"  value="<?php
            if (isset($_POST['rank'])) echo $_POST['rank'];?>"/></dd>
            <dt></dt><dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>


