<?php
include 'search.php';
$create_link = ADMIN_HTML_ROOT . 'article/create';
?>
<a href="<?php echo $create_link;?>">Create</a>
<?php
if ($article_list) {
$link_prefix = ADMIN_HTML_ROOT . "article/retrieve_by_cat_id/$cat_id/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_title = $link_prefix . 'title' . $link_postfix;
$link_num_of_views = $link_prefix . 'num_of_views' . $link_postfix;
$link_cat_name = $link_prefix . 'cat_name' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_title;?>'>title</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_num_of_views;?>'>Views</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_cat_name;?>'>category</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($article_list as $article) {
	$article_id = $article['id'];
	$link_delete = ADMIN_HTML_ROOT . 'article/delete/' . $article_id;
	$link_update = ADMIN_HTML_ROOT . 'article/update/' . $article_id;
?>
<tr>
	<td><?php echo $article['id'];?></td>
	<td><?php echo $article['title'];?></td>
	<td><?php echo $article['num_of_views'];?></td>
	<td><?php echo $article['cat_name'];?></td>
        <td><?php echo $article['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_article">delete</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'article/retrieve_by_cat_id/' . $cat_id . '/';	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




