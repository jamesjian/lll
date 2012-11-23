<?php
/**
 * this may be our homepage
 * left ads                     tag cloud
 *  article                        right ads
 * related articles                latest
 *                              hottest
 */
?>

<div class='zx-front-left'>	
    <div class="zx-front-breadcrumb">
        <?php echo \App\Transaction\Session::get_breadcrumb(); ?>
    </div>
    <div class='zx-front-left1'>
        <?php include FRONT_VIEW_PATH . 'templates/left_google_ads.php'; ?>
    </div>	
    <div class='zx-front-left2'>
        <?php
        if ($questions) {
            ?>
            <nav>
                <ul>
                    <?php
                    foreach ($questions as $question) {
                        $id = $question['id'];
                        $link = FRONT_HTML_ROOT . 'question/content/' . $id;
                        ?>		
                        <li><a href='<?php echo $link; ?>'><?php echo $question['title']; ?></a> </li>
                        <?php
                    }//foreach
                    ?>
                </ul>
            </nav>
            <?php
        $link_prefix = HTML_ROOT . 'front/question/all/';
        $link_postfix = "/$order_by/$direction";
        include FRONT_VIEW_PATH . 'templates/pagination.php';        
        }
        ?>
    </div>

</div>
<div class='zx-front-right'>
    <div class='zx-front-right1'>
        <?php //include FRONT_VIEW_PATH . 'templates/tag_cloud.php'; ?>
    </div>	
    <div class="zx-front-right2">

    </div>    
    <div class='zx-front-right3'>
        <?php include FRONT_VIEW_PATH . 'templates/right_google_ads.php'; ?>
    </div>
    <div class='zx-front-right4'>
    </div>
    <div class='zx-front-right5'>
    </div>
</div>
