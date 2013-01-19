<?php
//item_type: question 1, answer 2, ad 3
if (!isset($item_type)) $item_type = ''; 
$link_search = ADMIN_HTML_ROOT . 'claim/search/' . $item_type;
?>
<form action="<?php echo $link_search;?>" method="post">
Keyword:<input type="text" name="search" value="<?php echo $search;?>" />
<input type="submit" name="submit" value="Search" />
</form>
<a href="<?php echo ADMIN_HTML_ROOT . 'article/retrieve/1/title/ASC';?>">All records</a>
