<span class="zx-front-tag-title">常见类别：</span> 
<?php
$i = 0;
foreach ($tags as $tag) {
    $link = HTML_ROOT . 'front/question/tag/' . $tag;
    $tag_class = ($i%2 ==0) ? 'zx-front-tag1' : 'zx-front-tag2';
    $i++; 
?>
<a href="<?php echo $link;?>" class="<?php echo $tag_class;?>" title="<?php echo $tag;?>"><?php echo $tag;?></a>
<?php
}