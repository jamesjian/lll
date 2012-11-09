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
        <?php //find tag ?>
        <form>
            查找问题类别<input name="tag_name" size="50" />
            <input type="submit" value="查找" />
        </form>
        <?php
        if ($tags) {
            $n = count($tags);
            for ($i = 0; $i < $n; $i++) {
                $tag = $tags[$i];
                $link = HTMLROOT . 'front/question/tag/' . $tag['id'] . '/page/1/' . $tag['name'];
                ?>
                <div class="zx-front-tag">
                    <a href="<?php echo $link; ?>"><?php echo $tag['name']; ?></a>
                </div>
                <?php
                if (($i + 1) % 4 == 0) {
                    echo BR;
                }//if
            }//for tag
        }//if tags
        ?>
    </div>
    <?php
    //might have pagination if too many tags
    ?>
</div>
<div class='zx-front-right'>
    <div class='zx-front-right1'>
        <?php include FRONT_VIEW_PATH . 'templates/tag_cloud.php'; ?>
    </div>	
    <div class="zx-front-right2">
        <?php
        //related articles
        if ($related_articles) {
            ?>
            <span class="zx-front-related-article">相关文章：</span> 
            <nav>
                <ul>
                    <?php
                    $current_article_id = $article['id'];
                    foreach ($related_articles as $article) {
                        if (!($article['id'] == $current_article_id)) {
                            $read_more_link = HTML_ROOT . 'front/article/show/' . $article['id'];
                            ?>		
                            <li><?php echo "<a href='$read_more_link' class='zx-front-related-article'>" . $article['title'] . "</a>";
                            ?>
                            </li>
                            <?php
                        }
                    }//foreach
                    ?>
                </ul>
            </nav>	
            <?php
        }//if ($related_articles)
        ?>        
    </div>    
    <div class='zx-front-right3'>
        <?php include FRONT_VIEW_PATH . 'templates/right_google_ads.php'; ?>
    </div>
    <div class='zx-front-right4'>
        <?php include FRONT_VIEW_PATH . 'templates/latest_articles.php'; ?>
    </div>
    <div class='zx-front-right5'>
        <?php include FRONT_VIEW_PATH . 'templates/hottest_articles.php'; ?>
    </div>
</div>
