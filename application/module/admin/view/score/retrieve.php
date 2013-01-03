<?php
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'score/create/' . $uid;
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($score_list) {
$link_prefix = ADMIN_HTML_ROOT . "score/retrieve/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_previous_score = $link_prefix . 'previous_score' . $link_postfix;
$link_operation = $link_prefix . 'operation' . $link_postfix;
$link_difference = $link_prefix . 'difference' . $link_postfix;
$link_current_score = $link_prefix . 'current_score' . $link_postfix;
$link_date_created = $link_prefix . 'date_created' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_previous_score;?>'>previous</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_operation;?>'>operation</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_difference;?>'>difference</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_current_score;?>'>current</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_date_created;?>'>Date</a><img src="<?php echo $direction_img;?>" /></th>
</tr>

<?php
    foreach ($score_list as $score) {
	//$sid = $score['id'];
	//$link_delete = ADMIN_HTML_ROOT . 'score/delete/' . $sid;
	//$link_update = ADMIN_HTML_ROOT . 'score/update/' . $sid;
?>
<tr>
	<td><?php echo $score['id'];?></td>
	<td><?php echo $score['previous_score'];?></td>
	<td><?php echo $score['operation'];?></td>
	<td><?php echo $score['difference'];?></td>
	<td><?php echo $score['current_score'];?></td>
        <td><?php echo $score['date_created'];?></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'score/retrieve/';	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




