<?php
/**
 * in right column
 */
?>
<span class="zx-front-latest-article">最新文章：</span> 
<?php
//latest contents
if ($latest10) {
    ?>
    <nav>
        <ul>
            <?php
            foreach ($latest10 as $question) {
                $read_more_link = HTML_ROOT . 'front/question/content/' . $question['url'];
                ?>		
                <li><?php echo "<a href='$read_more_link' class='zx-front-latest-question'>" . $question['title'] . "</a>";
                ?>
                </li>
                <?php
            }//foreach
            ?>
        </ul>
    </nav>	
    <?php
}//if ($related_articles)
?>

