<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
?>
<form action="<?php echo ADMIN_HTML_ROOT . 'user/update'; ?>" method="post">
    <fieldset>
        <legend>Update a user</legend>
        <dl>      
            <dt>Name:</dt><dd><input type="text" name="user_name" size="50" disabled="disabled" value="<?php if (isset($_POST['user_name'])) echo $_POST['user_name']; else echo $user['user_name']; ?>"/></dd>
            <dt>Password:</dt><dd><input type="text" name="password" size="50" value=""/></dd>
            <dt>Email:</dt><dd><input type="text" name="email" size="50" value="<?php if (isset($_POST['email'])) echo $_POST['email']; else echo $user['email']; ?>"/></dd>
            <dt>Number of Questions:</dt><dd><input type="text" name="num_of_questions" size="50" value="<?php if (isset($_POST['num_of_questions'])) echo $_POST['num_of_questions']; else echo $user['num_of_questions']; ?>"/></dd>
            <dt>Number of Answers:</dt><dd><input type="text" name="num_of_answers" size="50" value="<?php if (isset($_POST['num_of_answers'])) echo $_POST['num_of_answers']; else echo $user['num_of_answers']; ?>"/></dd>
            <dt>Number of Ads:</dt><dd><input type="text" name="num_of_ads" size="50" value="<?php if (isset($_POST['num_of_ads'])) echo $_POST['num_of_ads']; else echo $user['num_of_ads']; ?>"/></dd>
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

