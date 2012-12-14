<?php
\Zx\Message\Message::show_message();
include 'search.php';
?>
<?php
if ($user_list) {
    $columns = 5; //5 users in one line
    $i = 0;
    ?>
    <table>
        <?php
        foreach ($user_list as $user) {
            $uid = $user['id'];
            $link_user = FRONT_HTML_ROOT . 'user/detail/' . $uid;
            if ($i == 0) {
                echo "<tr>";
            }
            ?>
            <td><?php echo $user['id']; ?>
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
    $link_prefix = ADMIN_HTML_ROOT . 'user/retrieve/';
    $link_postfix = ""; //always order by score desc
    include ADMIN_VIEW_PATH . 'templates/pagination.php';
} else {
    echo 'No record.';
}




