<?php
include 'search.php';
//$create_link = ADMIN_HTML_ROOT . 'abuse/create';
?>
<?php
if ($abuse_list) {
$link_prefix = ADMIN_HTML_ROOT . "abuse/retrieve_by_item_type/$type_id/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_cat_id = $link_prefix . 'cat_id' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th>Type</th>
<th>Content</th>
<th><a href='<?php echo $link_id;?>'>Category</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
$cat_names = \App\Model\Abusecategory::get_cats();
$item_types = array('1'=>'question', '2'=>'answer', '3'=>'ad');
$item_type = $item_types[$type_id];
    foreach ($abuse_list as $abuse) {
	$abuse_id = $abuse['id'];
	$link_delete = ADMIN_HTML_ROOT . 'abuse/delete/' . $abuse_id;
	$link_update = ADMIN_HTML_ROOT . 'abuse/update/' . $abuse_id;
?>
<tr>
	<td><?php echo $abuse['id'];?></td>
	<td><?php echo $item_type;?></td>
	<td><?php echo  mb_substr($abuse['content'], 0, 100, 'UTF-8');?></td>
	<td><?php echo $cat_names[$abuse['cat_id']]; ?></td>
        <td><?php echo $abuse['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_abuse">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'abuse/retrieve_by_item_type/' . $type_id . '/';	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




