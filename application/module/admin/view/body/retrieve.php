<?php
\Zx\Message\Message::show_message();
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'body/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($body_list) {
$link_prefix = ADMIN_HTML_ROOT . "body/retrieve/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_en = $link_prefix . 'en' . $link_postfix;
$link_cn = $link_prefix . 'cn' . $link_postfix;
$link_cid = $link_prefix . 'cid' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_en;?>'>En</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_cn;?>'>Cn</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_cid;?>'>Cid</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($body_list as $body) {
	$body_id = $body['id'];
	$link_delete = ADMIN_HTML_ROOT . 'body/delete/' . $body_id;
	$link_update = ADMIN_HTML_ROOT . 'body/update/' . $body_id;
?>
<tr>
	<td><?php echo $body['id'];?></td>
	<td><?php echo $body['en'];?></td>
	<td><?php echo $body['cn'];?></td>
	<td><?php echo $body['cid'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_body">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'body/retrieve/';	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




