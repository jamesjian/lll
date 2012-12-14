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
        if ($ad) {
            //list all ad tags
            $tids = explode('@', $ad['tids']);
            $tnames = explode('@', $ad['tnames']);
            foreach ($tids as $i => $tid) {
                $link = FRONT_HTML_ROOT . 'ad/tag/' . $tid;
            ?>
            <a href="<?php echo $link; ?>" title="<?php echo $tnames[$i]; ?>"><?php echo $tnames[$i]; ?></a>
            <?php
            }
            ?>
            <article>
                <header>
                    <h1 class="zx-front-article-title">
                        <?php
                        echo $ad['title'], BR;
                        ?>
                    </h1>
                </header>
                <section>
                    <div class="zx-front-article-content">
                        <?php
                        echo $ad['content'], BR;
                        ?>
                    </div>
                </section>
            </article>
            <?php
        }
        ?>
    </div>

</div>
<div class='zx-front-right'>
    <div class='zx-front-right1'>
        <?php include FRONT_VIEW_PATH . 'templates/ad_tag_cloud.php'; ?>
    </div>	
    <div class="zx-front-right2">
        <?php
        //related articles
        if ($related_ads) {
            ?>
            <span class="zx-front-related-article">相关信息：</span> 
            <nav>
                <ul>
                    <?php
                    $current_ad_id = $ad['id'];
                    foreach ($related_ads as $ad) {
                        if (!($ad['id'] == $current_ad_id)) {
                            $read_more_link = HTML_ROOT . 'front/ad/content/' . $ad['id'];
                            ?>		
                            <li><?php echo "<a href='$read_more_link' class='zx-front-related-ad'>" . $ad['title'] . "</a>";
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
    </div>
    <div class='zx-front-right4'>
    </div>
    <div class='zx-front-right5'>
    </div>
</div>
