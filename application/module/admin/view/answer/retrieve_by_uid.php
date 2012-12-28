<?php
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'answer/create/' . $uid;
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($answer_list) {
$link_prefix = ADMIN_HTML_ROOT . "answer/retrieve_by_uid/$uid/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_title = $link_prefix . 'title' . $link_postfix;
$link_tnames = $link_prefix . 'tnames' . $link_postfix;
$link_rank = $link_prefix . 'rank' . $link_postfix;
$link_uname = $link_prefix . 'uname' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_title;?>'>title</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_tnames;?>'>tags</a><img src="<?php echo $direction_img;?>" /></th>
<th>Content</th>
<th><a href='<?php echo $link_rank;?>'>rank</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_uname;?>'>user</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($answer_list as $answer) {
	$aid = $answer['id'];
	$link_delete = ADMIN_HTML_ROOT . 'answer/delete/' . $aid;
	$link_update = ADMIN_HTML_ROOT . 'answer/update/' . $aid;
?>
<tr>
	<td><?php echo $answer['id'];?></td>
	<td><?php echo $answer['title'];?></td>
	<td><?php echo $answer['tnames'];?></td>
	<td><?php echo mb_substr($answer['content'], 0, 50, 'UTF-8');?></td>
	<td><?php echo $answer['rank'];?></td>
	<td><?php echo $answer['uname'];?></td>
        <td><?php echo $answer['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_answer">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'answer/retrieve_by_uid/' . $uid;	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




