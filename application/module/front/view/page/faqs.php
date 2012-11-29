<?php
if ($faqs) {
?>
<dl>
<?php
    foreach ($faqs as $faq) {

?>
<dt><?php	echo $faq['title']; ?></dt>
<dd><?php   echo $faq['content'];?></dd>
<?php
    }
?>
</dl>	
<?php	
}


