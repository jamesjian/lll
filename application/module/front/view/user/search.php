<?php
$link_search = FRONT_HTML_ROOT . 'user/search';
?>
<form action="<?php echo $link_search;?>" method="post">
Keyword:<input type="text" name="keyword" />
<input type="submit" name="submit" value="Search" />
</form>
