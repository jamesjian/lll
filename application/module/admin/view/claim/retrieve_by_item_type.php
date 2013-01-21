<?php
include 'search.php';
//$create_link = ADMIN_HTML_ROOT . 'claim/create';
echo $item_type;  //1: question, 2: answer, 3: ad
?>
<?php
if ($claim_list) {
    $link_prefix = ADMIN_HTML_ROOT . "claim/retrieve_by_item_type/$item_type/$current_page/";
    $next_direction = ($direction == 'ASC') ? 'DESC' : 'ASC';  //change direction
    $link_postfix = "/$next_direction/$search";
    $link_id = $link_prefix . 'id' . $link_postfix;
    if ($item_type == 1 || $item_type == 2) {
        //question and answer has id1
        $link_id1 = $link_prefix . 'id1' . $link_postfix;
    }
    $link_item_id = $link_prefix . 'item_id' . $link_postfix;
    if ($item_type == 1 || $item_type == 3) {
        //question and answer has id1
        $link_title = $link_prefix . 'title' . $link_postfix;
    }
    $link_status = $link_prefix . 'status' . $link_postfix;
    $direction_img = ($direction == 'ASC') ? HTML_ROOT . 'image/icon/up.png' :
            HTML_ROOT . 'image/icon/down.png';
    \Zx\Message\Message::show_message();
    ?>
    <table>
        <tr>
            <th><a href='<?php echo $link_id; ?>'>id</a><img src="<?php echo $direction_img; ?>" /></th>
            <th>ID</th>
            <?php
            if ($item_type == 1 || $item_type == 2) {
                //question and answer has id1
                ?>
                <th>ID1</th>
                <?php
            }
            ?>
            <?php
            if ($item_type == 1 || $item_type == 3) {
                //question and answer has title
                ?>
                <th>Title</th>
                <?php
            }
            ?>
            <th>Content</th>
            <th><a href='<?php echo $link_id; ?>'>Category</a><img src="<?php echo $direction_img; ?>" /></th>
            <th><a href='<?php echo $link_status; ?>'>status</a><img src="<?php echo $direction_img; ?>" /></th>
            <th>delete</th>
            <th>update</th>
        </tr>

        <?php
        $cat_names = \App\Model\Claimcategory::get_cats();
        $item_types = array('1' => 'question', '2' => 'answer', '3' => 'ad');
        foreach ($claim_list as $claim) {
            $claim_id = $claim['id'];
            $link_item = ADMIN_HTML_ROOT . $item_types[$claim['item_type']] . '/update/' . $claim['item_id'];
            $link_delete = ADMIN_HTML_ROOT . 'claim/delete/' . $claim_id;
            $link_update = ADMIN_HTML_ROOT . 'claim/update/' . $claim_id;
            ?>
            <tr>
                <td><?php echo $claim['id']; ?></td>
                <?php
                if ($item_type == 1 || $item_type == 2) {
                    //question and answer has id1
                    ?>
                    <td><?php echo $claim['id1']; ?></td>
                    <?php
                }
                ?>
                <?php
                if ($item_type == 1 || $item_type == 3) {
                    //question and ad has title
                    ?>
                    <td><?php echo $claim['title']; ?></td>
                    <?php
                }
                ?>        
                <td><?php echo mb_substr($claim['item_content'], 0, 100, 'UTF-8'); ?></td>
                <td><?php echo $cat_names[$claim['cat_id']]; ?></td>
                <td><?php echo $claim['status']; ?></td>
                <td><a href='<?php echo $link_delete; ?>' class="delete_claim">delete</a></td>
                <td><a href='<?php echo $link_update; ?>'>update</a></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
    $link_prefix = ADMIN_HTML_ROOT . 'claim/retrieve_by_item_type/' . $item_type . '/';
    $link_postfix = "/$order_by/$direction/$search";
    include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
    echo 'No record.';
}




