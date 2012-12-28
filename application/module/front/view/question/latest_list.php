<?php
/**
 * this may be our homepage
 * left ads                     tag cloud
 *  article                        right ads
 * related articles                latest
 *                              hottest
 */
?>
<div class="zx-front-breadcrumb">
    <?php echo \App\Transaction\Session::get_breadcrumb(); ?>
</div>
<div class='zx-front-left1'>
    <?php include FRONT_VIEW_PATH . 'templates/left_google_ads.php'; ?>
</div>	
<div class='zx-front-left2'>
    <?php
    include 'question_list.php';
    $link_prefix = HTML_ROOT . 'front/question/latest/';
    $link_postfix = "";  //no need to have order by and direction
    include FRONT_VIEW_PATH . 'templates/pagination.php';
    ?>
</div>
