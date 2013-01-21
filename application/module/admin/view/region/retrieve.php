<?php
//region page no pagination only 8 states
if ($region_list) {
$link_prefix = ADMIN_HTML_ROOT . "region/retrieve/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_state = $link_prefix . 'state' . $link_postfix;
$link_num_of_questions = $link_prefix . 'num_of_questions' . $link_postfix;
$link_num_of_ads = $link_prefix . 'num_of_ads' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_state;?>'>previous</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_num_of_questions;?>'>operation</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_num_of_ads;?>'>difference</a><img src="<?php echo $direction_img;?>" /></th>
</tr>

<?php
    foreach ($region_list as $region) {
	//$sid = $region['id'];
	//$link_delete = ADMIN_HTML_ROOT . 'score/delete/' . $sid;
	//$link_update = ADMIN_HTML_ROOT . 'score/update/' . $sid;
?>
<tr>
	<td><?php echo $region['state'];?></td>
	<td><?php echo $region['num_of_questions'];?></td>
	<td><?php echo $region['num_of_ads'];?></td>
</tr>
<?php
    }
	?>
	</table>
<?php
} else {
	echo 'No record.';
}




