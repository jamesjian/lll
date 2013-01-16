<?php
/**
 * left ads                     tag cloud
 *  article                        right ads
 * related articles                latest
 *                              hottest
 */
?>

<div class="zx-front-breadcrumb">
    <?php 
    //echo \App\Transaction\Session::get_breadcrumb(); 
    ?>
</div>
<div class='zx-front-left1'>
    <?php include FRONT_VIEW_PATH . 'templates/left_google_ads.php'; ?>
</div>	
<div class='zx-front-left2'>
    <table>
        <?php
        foreach ($questions as $question) {
            $content_link = FRONT_HTML_ROOT . 'question/content/' . $question['id'];
            $delete_link = FRONT_HTML_ROOT . 'question/delete/' . $question['id'];
            ?>
            <tr>
                <td><a href='<?php echo $content_link; ?>'><?php echo $question['title']; ?></a></td>
                <td><a href='<?php echo $delete_link; ?>' title="如果你的提问已经有回答， 将不可删除">删除</a></td>
            </tr>
            <?php
        }//foreach
        ?>
    </table>                   
</div>
