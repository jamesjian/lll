<div class='zx-front-left'>			
    <div class='zx-front-left1'>
        <?php include FRONT_VIEW_PATH . 'templates/left_google_ads.php'; ?>
    </div>	
    <div class='zx-front-left2'>
        <?php
        if ($articles) {
            ?>
            <nav>
                <ul>
                    <?php
                    foreach ($articles as $article) {
                        $read_more_link = HTML_ROOT . 'front/article/content/' . $article['url'];
                        ?>		
                        <li><?php
                echo $article['title'], BR;
                //echo mb_substr($article['content'], 0, 100, 'UTF-8');
                echo $article['abstract'];
                echo "<a href='$read_more_link' title='$read_more_link'>阅读全文...</a>";
                        ?></li>
                        <?php
                    }//foreach
                    ?>
                </ul>
            </nav>
            <?php
        }//if ($articles)

        $link_prefix = HTML_ROOT . 'article/retrieve/';
        $link_postfix = "/$order_by/$direction";
        include ADMIN_VIEW_PATH . 'templates/pagination.php';
        ?>
    </div>
</div>
<div class='zx-front-right'>
    <div class='zx-front-right1'>
        <?php
        //tag cloud or search
        include FRONT_VIEW_PATH . 'templates/tag_cloud.php';
        ?>
    </div>	
    <div class='zx-front-right2'>
        <?php include FRONT_VIEW_PATH . 'templates/right_google_ads.php'; ?>
    </div>
    <div class='zx-front-right3'>
        <?php include FRONT_VIEW_PATH . 'templates/hottest_articles.php'; ?>
        <?php 
        $all_hottest = HTML_ROOT . 'article/hottest/';
        ?>
        <a href="<?php echo $all_hottest;?>">All</a>
    </div>
</div>