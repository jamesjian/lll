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
        foreach ($answers as $answer) {
            $link = FRONT_HTML_ROOT . 'question/content/' . $answer['qid1'];
            ?>
            <tr><td><a href='<?php echo $link; ?>'><?php echo $answer['title']; ?></a></td></tr>
            <?php
        }//foreach
        ?>
    </table>                   
</div>
