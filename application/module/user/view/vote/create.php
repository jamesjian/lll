<?php
/**
 * ajax form
 */
?>
<form action="<?php echo USER_HTML_ROOT;?>vote/create" method="POST">
    <input type="hidden" name="item_id" value="<?php echo $item_id;?>" />
    <input type="hidden" name="item_type" value="<?php echo $item_type;?>" />
</form>