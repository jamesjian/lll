<?php
$link_search = ADMIN_HTML_ROOT . 'user/search';
?>
<form action="<?php echo $link_search;?>" method="post">
Keyword:<input type="text" name="search" />
<input type="submit" name="submit" value="Search" />
</form>
