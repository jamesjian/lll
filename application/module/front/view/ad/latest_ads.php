<?php
/**
 * in right column
 */
?>
<span class="zx-front-latest-ad">最新信息：</span> 
： 
<?php
//$latest_ads
if ($latest_ads) {
    ?>
    <nav>
        <ul>
            <?php
            foreach ($latest_ads as $ad) {
                $read_more_link = HTML_ROOT . 'front/ad/content/' . $ad['id'];
                ?>		
                <li><?php echo "<a href='$read_more_link' class='zx-front-latest-ad'>" . $ad['title'] . "</a>";
                ?>
                </li>
                <?php
            }//foreach
            ?>
        </ul>
    </nav>	
    <?php
}//if ($latest_ads)
$all_ads = HTML_ROOT . 'ad/retrieve/';
?>
<a href="<?php echo $all_ads; ?>"  class='zx-front-read-more'>全部信息</a>
