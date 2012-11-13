<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message_Message::show_message();
?>
<form action="<?php echo ADMIN_HTML_ROOT . 'user/update'; ?>" method="post">
    <fieldset>
        <legend>Update a user</legend>
        <dl>      
            <dt>Name:</dt><dd><input type="text" name="user_name" size="50" value="<?php if (isset($_POST['user_name'])) echo $_POST['user_name']; else echo $user['user_name']; ?>"/></dd>
            <dt>Password:</dt><dd><input type="text" name="password" size="50" value="<?php if (isset($_POST['password'])) echo $_POST['password'];  ?>"/></dd>
            <dt>Email:</dt><dd><input type="text" name="email" size="50" value="<?php if (isset($_POST['email'])) echo $_POST['email']; else echo $user['email']; ?>"/></dd>
            <dt>Rank:</dt><dd><input type="text" name="rank" size="50" value="<?php if (isset($_POST['rank'])) echo $_POST['rank']; else echo $user['rank']; ?>"/></dd>
            <dt>Status:</dt>
            <dd>
                <?php
                if ($user['status'] == '1') {
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
            <dt><input type="hidden" name="id" value="<?php echo $user['id']; ?>" /></dt><dd>
                <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>

