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
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a></th>
<th><a href='<?php echo $link_user_name;?>'>Name</a></th>
<th><a href='<?php echo $link_email;?>'>Email</a></th>
<th>delete</th>
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
	<td><a href='<?php echo $link_delete;?>' class="delete_user">delete</a></td>
	<td><a href='<?php echo $link_questions;?>'>questions</a></td>
	<td><a href='<?php echo $link_answers;?>'>answers</a></td>
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




