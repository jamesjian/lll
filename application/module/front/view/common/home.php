<div class='zx-front-left1'>
    <?php
    include FRONT_VIEW_PATH . 'template/question_list.php';
    /**
     * define $link_prefix and $link_postfix in main view file
      $link_prefix = HTML_ROOT . 'front/question/tag/' . $tag;
      $link_postfix = "/$order_by/$direction";
     */
    $link_prefix = HTML_ROOT . 'front/question/retrieve/';
    $link_postfix = "/$order_by/$direction";
    include FRONT_VIEW_PATH . 'template/pagination.php';
    ?>
</div>
