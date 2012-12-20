<?php
/**
 *  questions tags and latest ads as right column
 *  
 */
 include 'header.php'; ?>


<div class='zx-front-main'>
    <div class='zx-front-left'>			
        <?php echo $content; ?>
    </div>
    <div class='zx-front-right'>
        <div class='zx-front-right1'>
            <?php
            //tag cloud or search
            include FRONT_VIEW_PATH . 'tag/most_popular_question_tags.php';
            ?>
        </div>	
        <div class='zx-front-right2'>
            <?php include 'right_google_ads.php'; ?>
        </div>
        <div class='zx-front-right3'>
            <?php include FRONT_VIEW_PATH . 'ad/latest_ads.php'; ?>
        </div>
    </div>    
</div>
<div class="zx-front-clear-both"></div>
<?php include 'footer.php'; ?>
