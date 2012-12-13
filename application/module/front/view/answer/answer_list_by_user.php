<?php
/**
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
        if ($user) {
            echo $use['id'], BR;
            echo $use['uname'], BR;
            echo $use['id'], BR;
            ?>
            <ul>
                <?php
                foreach ($answers as $answer) {
                    $link = FRONT_HTML_ROOT . 'question/detail/' . $answer['qid'];
                    ?>
                        <li><a href='<?php echo $link;?>'><?php echo $answer['title'];?></a></li>
                        <?php
                }//foreach
                ?>
            </ul>                   
            <?php
        }//if valid user       
        ?>
    </div>

</div>
<div class='zx-front-right'>
    <div class='zx-front-right1'>
        <?php include FRONT_VIEW_PATH . 'templates/tag_cloud.php'; ?>
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
