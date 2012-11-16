<?php
\Zx\Message\Message::show_message();
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'tag/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($tag_list) {
$link_prefix = ADMIN_HTML_ROOT . "tag/retrieve/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_name = $link_prefix . 'name' . $link_postfix;
$link_num_of_questions = $link_prefix . 'num_of_questions' . $link_postfix;
$link_num_of_ads = $link_prefix . 'num_of_ads' . $link_postfix;
$link_rank = $link_prefix . 'rank' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_name;?>'>name</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_num_of_questions;?>'>Questions</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_num_of_ads;?>'>Ads</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_rank;?>'>rank</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($tag_list as $tag) {
	$tag_id = $tag['id'];
	$link_questions = ADMIN_HTML_ROOT . 'question/retrieve_by_tag_id/' . $tag_id;
	$link_ads = ADMIN_HTML_ROOT . 'ad/retrieve_by_tag_id/' . $tag_id;
	$link_delete = ADMIN_HTML_ROOT . 'tag/delete/' . $tag_id;
	$link_update = ADMIN_HTML_ROOT . 'tag/update/' . $tag_id;
?>
<tr>
	<td><?php echo $tag['id'];?></td>
	<td><?php echo $tag['name'];?></td>
	<td><a href='<?php echo $link_questions;?>'><?php echo $tag['num_of_questions'];?></a></td>
	<td><a href='<?php echo $link_ads;?>'><?php echo $tag['num_of_ads'];?></a></td>
	<td><?php echo $tag['num_of_ads'];?></td>
	<td><?php echo $tag['rank'];?></td>
        <td><?php echo $tag['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_tag">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'tag/retrieve/';	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




