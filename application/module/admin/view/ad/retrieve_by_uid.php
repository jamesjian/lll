<?php
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'ad/create/' . $uid;
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($ad_list) {
$link_prefix = ADMIN_HTML_ROOT . "ad/retrieve_by_uid/$uid/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_title = $link_prefix . 'title' . $link_postfix;
$link_tnames = $link_prefix . 'tnames' . $link_postfix;
$link_score = $link_prefix . 'score' . $link_postfix;
$link_num_of_views = $link_prefix . 'num_of_views' . $link_postfix;
$link_score = $link_prefix . 'score' . $link_postfix;
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
<th><a href='<?php echo $link_num_of_views;?>'>view</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_score;?>'>Score</a></th>
<th><a href='<?php echo $link_uname;?>'>user</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($ad_list as $ad) {
	$qid = $ad['id'];
	$link_delete = ADMIN_HTML_ROOT . 'ad/delete/' . $qid;
	$link_update = ADMIN_HTML_ROOT . 'ad/update/' . $qid;
?>
<tr>
	<td><?php echo $ad['id'];?></td>
	<td><?php echo $ad['title'];?></td>
	<td><?php echo $ad['tnames'];?></td>
	<td><?php echo $ad['num_of_views'];?></td>
        <td><?php echo $ad['score'];?></a></td>
	<td><?php echo $ad['uname'];?></td>
        <td><?php echo $ad['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_ad">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'ad/retrieve_by_uid/' . $uid;	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




