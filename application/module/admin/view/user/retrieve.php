<?php
\Zx\Message\Message::show_message();
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'user/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($user_list) {
$link_prefix = ADMIN_HTML_ROOT . "user/retrieve/$current_user/";
$link_postfix = ($direction == 'ASC')? '/DESC' : '/ASC';
$link_id = $link_prefix . 'id' . $link_postfix;
$link_user_name = $link_prefix . 'user_name' . $link_postfix;
$link_email = $link_prefix . 'email' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$link_num_of_questions = $link_prefix . 'num_of_questions' . $link_postfix;
$link_num_of_answers = $link_prefix . 'num_of_answers' . $link_postfix;
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a></th>
<th><a href='<?php echo $link_user_name;?>'>Name</a></th>
<th><a href='<?php echo $link_email;?>'>Email</a></th>
<th><a href='<?php echo $link_status;?>'>Status</a></th>
<th>delete</th>
<th><a href='<?php echo $link_num_of_questions;?>'>Questions</a></th>
<th><a href='<?php echo $link_num_of_answers;?>'>Answers</a></th>
<th>update</th>
</tr>

<?php
    foreach ($user_list as $user) {
	$user_id = $user['id'];
	$link_delete = ADMIN_HTML_ROOT . 'user/delete/' . $user_id;
	$link_questions = ADMIN_HTML_ROOT . 'question/retrieve_by_user_id/' . $user_id; 
	$link_answers = ADMIN_HTML_ROOT . 'question/retrieve_by_user_id/' . $user_id;  
	$link_new_question = ADMIN_HTML_ROOT . 'question/create/' . $user_id;  //create a create by user id
	$link_update = ADMIN_HTML_ROOT . 'user/update/' . $user_id;
?>
<tr>
	<td><?php echo $user['id'];?></td>
	<td><?php echo $user['user_name'];?></td>
	<td><?php echo $user['email'];?></td>
  <td>
    <?php
    $active_check = '';
    $inactive_check = '';
    $registered_check = '';
    $status = intval($user->status);
    if ($status == 1)
        $active_check = 'selected';
    if ($status == 0)
        $inactive_check = 'selected';
    if ($status == 2)
        $registered_check = 'selected';
    ?>
                <select name="status_<?php echo $id; ?>" id="status_<?php echo $id; ?>" class='user_status'>
                    <option value="1" <?php echo $active_check; ?>/>正常</option>
                    <option value="0" <?php echo $inactive_check; ?>/>禁用</option>
                    <option value="2" <?php echo $registered_check; ?>/>未激活</option>
                </select>
            </td>        
	<td><a href='<?php echo $link_delete;?>' class="delete_user">delete</a></td>
	<td><a href='<?php echo $link_questions;?>'><?php echo $user['num_of_questions'];?></a></td>
	<td><a href='<?php echo $link_answers;?>'><?php echo $user['num_of_answers'];?></a></td>
	<td><a href='<?php echo $link_new_question;?>'>new question</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'user/retrieve/';	
$link_postfix = "/$order_by/$direction";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




