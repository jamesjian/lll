<div class='zx-front-left'>			
    <div class='zx-front-left1'>
        <?php include FRONT_VIEW_PATH . 'templates/left_google_ads.php'; ?>
    </div>	
    <div class='zx-front-left2'>
        网站地图: 
        <?php
        if (isset($cats)) {
            ?>
            <nav>
                <ul>
                    <?php
                    foreach ($cats as $cat) {
                        $link = HTML_ROOT . 'front/article/category/' . $cat['title'];
                        ?>		
                        <li><?php
                echo "<a href='$link' title='" . $cat['title'] . "'>",$cat['title'],"</a>";
                        ?></li>
                        <?php
                    }//foreach
                    ?>
                </ul>
            </nav>
            <?php
        }//if ($articles)
        ?>
        <?php
        if (isset($articles)) {
            ?>
            <nav>
                <ul>
                    <?php
                    foreach ($articles as $article) {
                        $link = HTML_ROOT . 'front/article/content/' . $article['url'];
                        ?>		
                        <li><?php
                echo "<a href='$link' title='" . $article['title'] . "'>",$article['title'],"</a>";
                        ?></li>
                        <?php
                    }//foreach
                    ?>
                </ul>
            </nav>
            <?php
        }//if ($articles)
        ?>
    </div>
</div>
