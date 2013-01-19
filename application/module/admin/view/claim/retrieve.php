<?php
\Zx\Message\Message::show_message();
include 'search.php';
?>
<?php
if ($claim_list) {
$link_prefix = ADMIN_HTML_ROOT . "claim/retrieve/1/";
$link_postfix = ($direction == 'ASC')? '/DESC' : '/ASC';
$link_id = $link_prefix . 'id' . $link_postfix;
$link_item_type = $link_prefix . 'item_type' . $link_postfix;
$link_item_id = $link_prefix . 'item_id' . $link_postfix;
$link_claimant_id = $link_prefix . 'claimant_id' . $link_postfix;
$link_cat_id = $link_prefix . 'cat_id' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$link_date_created = $link_prefix . 'date_created' . $link_postfix;
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a></th>
<th><a href='<?php echo $link_item_type;?>'>Item type</a></th>
<th><a href='<?php echo $link_item_id;?>'>Item id</a></th>
<th><a href='<?php echo $link_claimant_id;?>'>Claimant</a></th>
<th><a href='<?php echo $link_cat_id;?>'>Category</a></th>
<th><a href='<?php echo $link_status;?>'>Status</a></th>
<th><a href='<?php echo $link_date_created;?>'>Date</a></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
        $item_types = \App\Model\Claim::get_item_types();
        $cats = \App\Model\Claimcategory::get_cats();
    foreach ($claim_list as $claim) {
	$claim_id = $claim['id'];
	$link_delete = ADMIN_HTML_ROOT . 'claim/delete/' . $claim_id;
	$link_item = ADMIN_HTML_ROOT .$item_types[$claim['item_type']] . '/update/' . $claim['item_id']; 
	$link_claimant = ADMIN_HTML_ROOT . 'user/update/' . $claim['claimant_id'];  
	//$link_new_question = ADMIN_HTML_ROOT . 'question/createb_by_uid/' . $uid;  //create a create by claim id
	$link_update = ADMIN_HTML_ROOT . 'claim/update/' . $claim_id;
?>
<tr>
	<td><?php echo $claim['id'];?></td>
	<td><?php echo $item_types[$claim['item_type']];?></td>
	<td><a href='<?php echo $link_item;?>'><?php echo $claim['item_id'];?></a></td>
	<td><a href='<?php echo $link_claimant;?>'><?php echo $claim['claimant_id'];?></a></td>
	<td><?php echo $cats[$claim['cat_id']];?></td>
	<td><?php echo $claim['status'];?></td>
	<td><?php echo $claim['date_created'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_claim">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'claim/retrieve/';	
$link_postfix = "/$order_by/$direction";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




