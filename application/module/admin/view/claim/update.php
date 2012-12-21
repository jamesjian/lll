<form action="<?php echo ADMIN_HTML_ROOT . 'claim/update'; ?>" method="post">
    <fieldset>
        <legend>Update article</legend>
        <dl>
            <dt>Item ID</dt>
            <dd><a href=""><?php echo $claim['item_id'];?></a></dd>
            <dt>Item Type</dt>
            <dd>
                <?php
                $item_types = array('1'=>'question', '2'=>'answer', '3'=>'ad');
                echo $item_types[$claim['item_type']];
                ?>
            </dd>
            <dt>Content</dt>
            <dd>
                <?php
                if ($claim['item_type'] == 2) {
                    //answer has no title
                    echo $item['content'];
                } else {
                    echo $item['title'], BR, $item['content'], BR;
                }
                ?>
            </dd>
            <dt>    Category:</dt><dd><select name='cat_id'>
                    <?php
                    foreach ($cats as $cat_id=>$cat_name) {
                        echo "<option value='" . $cat_id . "'";
                        if ($claim['cat_id'] == $cat_id) {
                            echo " selected";
                        }
                        echo ">" . $cat_name . '</option>';
                    }
                    ?>
                </select>
            </dd>
            <dt>    Status:</dt>
            <dd>
                <?php
                if ($claim['status'] == '1') {
                    $active_checked = ' checked';
                    $inactive_checked = '';
                } else {
                    $inactive_checked = ' checked';
                    $active_checked = '';
                }
                ?>
                <input type="radio" name="status" value="1" <?php echo $active_checked; ?>/>Active    
                <input type="radio" name="status" value="0"  <?php echo $inactive_checked; ?>/>Inactive     
            </dd>
            <dt> <input type="hidden" name="id" value="<?php echo $claim['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>

