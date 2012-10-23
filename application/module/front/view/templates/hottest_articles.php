<?php
/**
 * in right column
 */
?>
<span class="zx-front-hottest-article">最受关注文章：</span> 
： 
<?php
//hottest contents
if ($top10) {
    ?>
    <nav>
        <ul>
            <?php
            foreach ($top10 as $article) {
                $read_more_link = HTML_ROOT . 'front/article/content/' . $article['url'];
                ?>		
                <li><?php echo "<a href='$read_more_link' class='zx-front-hottest-article'>" . $article['title'] . "</a>";
                ?>
                </li>
                <?php
            }//foreach
            ?>
        </ul>
    </nav>	
    <?php
}//if ($related_articles)
$all_hottest = HTML_ROOT . 'article/hottest/';
?>
<a href="<?php echo $all_hottest; ?>"  class='zx-front-read-more'>全部文章</a>
