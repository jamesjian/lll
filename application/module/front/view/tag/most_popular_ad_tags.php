<?php
/**
 *$popular_tags is an template variable 
 */
?>
<span class="zx-front-tag-title">常见类别：</span> 
<a href="<?php echo FRONT_HTML_ROOT;?>tag/ad" title="所有分类">所有信息分类</a>
<?php
$i = 0;
foreach ($popular_tags as $tag) {
    $link = \App\Transaction\Tag::get_link($tag, 'ad');
    $tag_class = ($i%2 ==0) ? 'zx-front-tag1' : 'zx-front-tag2';
    $i++; 
?>
<a href="<?php echo $link;?>" class="<?php echo $tag_class;?>" title="<?php echo $tag['name'];?>"><?php echo $tag['name'];?></a>
<?php
}