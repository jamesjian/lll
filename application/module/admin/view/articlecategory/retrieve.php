<?php
\Zx\Message\Message::show_message();
$create_link = ADMIN_HTML_ROOT . 'articlecategory/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($reply_list) {
$link_prefix = ADMIN_HTML_ROOT . "articlecategory/retrieve/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_title = $link_prefix . 'title' . $link_postfix;  //article title
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
<th>Content</th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($reply_list as $reply) {
	$reply_id = $reply['id'];
	$link_article = ADMIN_HTML_ROOT . 'article/update/' . $reply['article_id'];
	$link_delete = ADMIN_HTML_ROOT . 'articlecategory/delete/' . $reply_id;
	$link_update = ADMIN_HTML_ROOT . 'articlecategory/update/' . $reply_id;
?>
<tr>
	<td><?php echo $reply['id'];?></td>
	<td><?php echo $reply['title'];?></td>
	<td><?php echo mb_substr($answer['content'], 0, 50);?></td>
	<td><?php echo $reply['uname'];?></td>
	<td><?php echo $reply['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_article_cat">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
} else {
	echo 'No record.';
}




