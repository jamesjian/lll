<?php
if ($article) {
?>
    Title: <?php echo $article['title'];?><br />
    Title(en): <?php echo $article['title_en'];?><br />
    URL: <?php echo $cat['url'];?><br />
    Keyword: <?php echo $article['keyword'];?><br />
    Keyword(en): <?php echo $article['keyword_en'];?><br />
    Rank: <?php echo $article['rank'];?><br />
    Status: <?php echo $article['status'];?><br />
    Description: <?php echo $article['description'];?><br />
    
<?php    
}//if

