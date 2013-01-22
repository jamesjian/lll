<?php
include 'search.php';
?>

<?php
if ($article_list) {

$link_title = HTML_ROOT . 'admin/article/list/orderby_direction_page_number';
?>
<table>
<tr>
<th><a href='<?php echo $link_title;?>'>title</a></th>
<th>action</th>
</tr>
<?php
    foreach ($article_list as $article) {
	$article_id = $article['id'];
	$link_delete = HTML_ROOT . 'admin/article/delete/' . $article_id;
	$link_update = HTML_ROOT . 'admin/article/update/' . $article_id;
?>
<tr>
<td><?php echo $article['title'];?></tr>
<td><a href='<?php echo link_delete;?>'>delete</a></td>
<td><a href='<?php echo link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
}





