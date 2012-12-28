<div class='zx-front-left1'>
    <?php
    include FRONT_VIEW_PATH . 'template/ad_list.php';
    /**
     * define $link_prefix and $link_postfix in main view file
      $link_prefix = HTML_ROOT . 'front/question/tag/' . $tag;
      $link_postfix = "/$order_by/$direction";
     */
    $link_prefix = FRONT_HTML_ROOT . 'ad/user/' . $user['id'] . '/';
    $link_postfix = ""; //always order by score desc
    include FRONT_VIEW_PATH . 'templates/pagination.php';
    ?>
</div>
