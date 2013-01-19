<?php
include 'search.php';
//$create_link = ADMIN_HTML_ROOT . 'claim/create';
?>
<?php
if ($claim_list) {
$link_prefix = ADMIN_HTML_ROOT . "claim/retrieve_by_item_type/$item_type/$current_page/";
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
$cat_names = \App\Model\Claimcategory::get_cats();
$item_types = array('1'=>'question', '2'=>'answer', '3'=>'ad');
    foreach ($claim_list as $claim) {
	$claim_id = $claim['id'];
        $link_item = ADMIN_HTML_ROOT .$item_types[$claim['item_type']] . '/update/' . $claim['item_id']; 
	$link_delete = ADMIN_HTML_ROOT . 'claim/delete/' . $claim_id;
	$link_update = ADMIN_HTML_ROOT . 'claim/update/' . $claim_id;
?>
<tr>
	<td><?php echo $claim['id'];?></td>
	<td><?php echo $item_types[$item_type];?></td>
	<td><?php echo  mb_substr($claim['item_content'], 0, 100, 'UTF-8');?></td>
	<td><?php echo $cat_names[$claim['cat_id']]; ?></td>
        <td><?php echo $claim['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_claim">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'claim/retrieve_by_item_type/' . $item_type . '/';	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




