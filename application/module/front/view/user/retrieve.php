<?php
\Zx\Message\Message::show_message();
include 'search.php';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($user_list) {
    foreach ($user_list as $user) {
	$uid = $user['id'];
	$link_user = FRONT_HTML_ROOT . 'user/detail/' . $uid;
?>
<tr>
	<td><?php echo $user['id'];?></td>
	<td><a href='<?php echo $link_user;?>'><?php echo $user['uname'];?></a></td>
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




