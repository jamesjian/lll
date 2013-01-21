<?php
\Zx\Message\Message::show_message();
include 'search.php';
?>
<?php
if ($vote_list) {
    $link_prefix = ADMIN_HTML_ROOT . "vote/retrieve/1/";
    $link_postfix = ($direction == 'ASC') ? '/DESC' : '/ASC';
    $link_uid = $link_prefix . 'uid' . $link_postfix;
    $link_item_type = $link_prefix . 'item_type' . $link_postfix;
    $link_item_id = $link_prefix . 'item_id' . $link_postfix;
    $link_id1 = $link_prefix . 'id1' . $link_postfix;
    $link_date_created = $link_prefix . 'date_created' . $link_postfix;
    ?>
    <table>
        <tr>
            <th><a href='<?php echo $link_uid; ?>'>user id</a></th>
            <th><a href='<?php echo $link_item_type; ?>'>item_type</a></th>
            <th><a href='<?php echo $link_item_id; ?>'>item_id</a></th>
            <th><a href='<?php echo $link_id1; ?>'>id1</a></th>
            <th><a href='<?php echo $link_date_created; ?>'>Date</a></th>
        </tr>

        <?php
        foreach ($vote_list as $vote) {
            $link_user = ADMIN_HTML_ROOT . 'user/detail/' . $vote['uid'];
            switch ($vote['item_type']) {
                case '1':
                    $item_link = ADMIN_HTML_ROOT . 'question/detail/' . $vote['item_id'];
                    $item_type = 'question';
                    break;
                case '2':
                    $item_link = ADMIN_HTML_ROOT . 'answer/detail/' . $vote['item_id'];
                    $item_type = 'answer';
                    break;
            }
            ?>
            <tr>
                <td><a href="<?php echo $link_user; ?>"><?php echo $vote['uid']; ?></a></td>
                <td><?php echo $item_type; ?></td>
                <td><a href="<?php echo $link_item; ?>"><?php echo $vote['item_id']; ?></a></td>
                <td><?php echo $vote['id1']; ?></td>
                <td><?php echo $vote['date_created']; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
    $link_prefix = ADMIN_HTML_ROOT . 'vote/retrieve/';
    $link_postfix = "/$order_by/$direction";
    include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
    echo 'No record.';
}




