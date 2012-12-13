<?php
\Zx\Message\Message::show_message();
include 'search.php';
//no create link in this page, create link is in retrieve_by_uid page, must have valid user id
?>
<?php
if ($question_list) {
$link_prefix = ADMIN_HTML_ROOT . "question/retrieve/$current_page/";
$next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
$link_postfix =  "/$next_direction/$search";
$link_id = $link_prefix . 'id' . $link_postfix;
$link_title = $link_prefix . 'title' . $link_postfix;
$link_tag_names = $link_prefix . 'tag_names' . $link_postfix;
$link_uname = $link_prefix . 'uname' . $link_postfix;
$link_num_of_views = $link_prefix . 'num_of_views' . $link_postfix;
$link_status = $link_prefix . 'status' . $link_postfix;
$direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' : 
                                         HTML_ROOT . 'image/icon/down.png'; 
\Zx\Message\Message::show_message();
?>
<table>
<tr>
<th><a href='<?php echo $link_id;?>'>id</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_title;?>'>title</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_tag_names;?>'>tags</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_rank;?>'>rank</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_uname;?>'>category</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_num_of_views;?>'>Views</a><img src="<?php echo $direction_img;?>" /></th>
<th><a href='<?php echo $link_status;?>'>status</a><img src="<?php echo $direction_img;?>" /></th>
<th>delete</th>
<th>update</th>
</tr>

<?php
    foreach ($question_list as $question) {
	$qid = $question['id'];
	$link_delete = ADMIN_HTML_ROOT . 'question/delete/' . $qid;
	$link_answers = ADMIN_HTML_ROOT . 'answer/retrive_by_qid/' . $qid;
	$link_new_answer = ADMIN_HTML_ROOT . 'answer/create/' . $qid;
	$link_update = ADMIN_HTML_ROOT . 'question/update/' . $qid;
?>
<tr>
	<td><?php echo $question['id'];?></td>
	<td><?php echo $question['title'];?></td>
	<td><?php echo $question['tag_names'];?></td>
	<td><?php echo $question['uname'];?></td>
	<td><?php echo $question['num_of_views'];?></td>
        <td><?php echo $question['status'];?></td>
	<td><a href='<?php echo $link_delete;?>' class="delete_question">delete</a></td>
	<td><a href='<?php echo $link_answers;?>'>Answers</a></td>
	<td><a href='<?php echo $link_new_answer;?>'>New Answer</a></td>
	<td><a href='<?php echo $link_update;?>'>update</a></td>
</tr>
<?php
    }
	?>
	</table>
<?php
$link_prefix = ADMIN_HTML_ROOT . 'question/retrieve/';	
$link_postfix = "/$order_by/$direction/$search";
include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
	echo 'No record.';
}




