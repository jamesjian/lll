<?php
\Zx\Message\Message::show_message();
include 'search.php';
?>
<?php
if ($users) {
    $columns = 5; //5 users in one line
    $i = 0;
    ?>
    <table class="zx-front-user-list">
        <?php
        foreach ($users as $user) {
            
            $link_user = \App\Transaction\User::get_link($user['id']);
            if ($i == 0) {
                echo "<tr>";
            }
            ?>
            <td><?php //echo $user['id']; ?>
            <a href='<?php echo $link_user; ?>'><?php echo $user['uname']; ?></a>
            <?php echo $user['score'];?>
            </td>
            <?php
            $i = $i + 1;
            if ($i == 5) {
                echo "</tr>";
                $i = 0;
            }
            ?>
        <?php
    }//foreach
    ?>
    </table>
    <?php
    $link_prefix = FRONT_HTML_ROOT . 'user/all/';
    $link_postfix = ""; //always order by score desc
    include FRONT_VIEW_PATH . 'templates/pagination.php';
} else {
    echo 'No record.';
}




