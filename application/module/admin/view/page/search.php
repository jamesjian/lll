<?php
$link_search = ADMIN_HTML_ROOT . 'page/search';
?>
<form action="<?php echo $link_search;?>" method="post">
Keyword:<input type="text" name="keyword" />
<input type="submit" name="submit" value="Search" />
</form>
