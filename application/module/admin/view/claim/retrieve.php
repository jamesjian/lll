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
<th>update</th>
</tr>

<?php
    foreach ($claim_list as $claim) {
	$uid = $claim['id'];
	$link_delete = ADMIN_HTML_ROOT . 'claim/delete/' . $uid;
	$link_questions = ADMIN_HTML_ROOT . 'question/retrieve_by_uid/' . $uid; 
	$link_answers = ADMIN_HTML_ROOT . 'answer/retrieve_by_uid/' . $uid;  
	$link_ads = ADMIN_HTML_ROOT . 'ad/retrieve_by_uid/' . $uid;  
	//$link_new_question = ADMIN_HTML_ROOT . 'question/createb_by_uid/' . $uid;  //create a create by claim id
	$link_update = ADMIN_HTML_ROOT . 'claim/update/' . $uid;
?>
<tr>
	<td><?php echo $claim['id'];?></td>
	<td><?php echo $claim['uname'];?></td>
	<td><?php echo $claim['email'];?></td>
  <td>
       
	<td><a href='<?php echo $link_delete;?>' class="delete_claim">delete</a></td>
	<td><a href='<?php echo $link_questions;?>'><?php echo $claim['num_of_questions'];?></a></td>
	<td><a href='<?php echo $link_answers;?>'><?php echo $claim['num_of_answers'];?></a></td>
	<td><a href='<?php echo $link_ads;?>'><?php echo $claim['num_of_ads'];?></a></td>
	<td><?php echo $claim['status'];?></td>
	<td><?php echo $claim['date_created'];?></td>
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




