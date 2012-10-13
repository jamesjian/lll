<?php
\Zx\Message\Message::show_message();
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'pagecategory/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($cat_list) {
$link_prefix = ADMIN_HTML_ROOT . "pagecategory/retrieve/$current_page/";
$link_postfix = ($direction == 'ASC')? '/DESC' : '/ASC';
$link_id = $link_prefix . 'id' . $link_postfix;
$link_title = $link_prefix . 'title' . $link_postfix;
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a></th>
<th><a href='<?php echo $link_title;?>'>title</a></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($cat_list as $cat) {
	$cat_id = $cat['id'];
	$link_delete = ADMIN_HTML_ROOT . 'pagecategory/delete/' . $cat_id;
	$link_update = ADMIN_HTML_ROOT . 'pagecategory/update/' . $cat_id;
?>
<tr>
	<td><?php echo $cat['id'];?></td>
	<td><?php echo $cat['title'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_page_cat">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'pagecategory/retrieve/';	
$link_postfix = "/$order_by/$direction";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




