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
            $link = FRONT_HTML_ROOT . 'question/content/' . $question['id'];
            ?>
            <tr>
                <td><a href='<?php echo $link; ?>'><?php echo $question['title']; ?></a></td>
            </tr>
            <?php
        }//foreach
        ?>
    </table>                   
</div>
