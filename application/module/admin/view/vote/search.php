<?php
$link_search = ADMIN_HTML_ROOT . 'vote/search';
?>
<form action="<?php echo $link_search;?>" method="post">
    Type:<select name="item_type">
        <option value="1">question</option>
        <option value="2">answer</option>
    </select>
Item Id:<input type="text" name="item_id" />
Item Id1:<input type="text" name="id1" />
User Id:<input type="text" name="uid" />
<input type="submit" name="submit" value="Search" />
</form>
