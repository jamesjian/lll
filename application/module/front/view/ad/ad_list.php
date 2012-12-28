<?php
/**
 * this is a template file, be used by
 * tag_list
 * user_list
 */
?>
<div class="zx-front-ad-list">
    <ul class="zx-front-ad-list-heading">
        <li class="zx-front-question-list-heading-col1">区域</li>
        <li class="zx-front-question-list-heading-col2">标题</li>
        <li class="zx-front-question-list-heading-col3">分值/查看</li>        
    </ul>
    <div class="zx-front-clear-both"></div>    
    <?php
    /**
     * each ad has:
     * title tags user date created
     * num of views
     */
    if ($ads) {
        $regions = \App\Model\Region::get_au_states_abbr();
        foreach ($ads as $ad) {
            $read_more_link = FRONT_HTML_ROOT . 'ad/content/' . $ad['id'];
            ?>		
            <ul class='zx-front-one-ad'>
                <li class="zx-front-one-ad-col1"><?php echo $regions[$ad['region']]; ?></li>
                <li class="zx-front-one-ad-col2">   <a href='<?php echo $read_more_link; ?>' class=''>
                        <?php echo $ad['title']; ?></a></li>
                <li class="zx-front-one-ad-col3"><?php echo $ad['score']; ?></li>
            </ul>
            <div class="zx-front-clear-both"></div>
            <ul>
                <li class="zx-front-one-question-col4">    
                    <?php
                    $tids = explode(TNAME_SEPERATOR, $ad['tids']);
                    $tnames = explode(TNAME_SEPERATOR, $ad['tnames']);
                    foreach ($tids as $index => $tag_id) {
                        $tag_link = FRONT_HTML_ROOT . 'ad/tag/' . $tag_id;
                        ?>
                        <a href='<?php echo $tag_link; ?>' class='zx-front-tag'>
                            <?php echo $tnames[$index]; ?></a>            
                        <?php
                    }
                    ?>
                </li>
                <li class="zx-front-one-question-col5">
                    <a href="<?php echo FRONT_HTML_ROOT . 'user/profile' . $ad['uid']; ?>">
                        <?php echo $ad['uname']; ?></a>
                    <?php echo $ad['date_created']; ?>
                </li>
            </ul>  
            <div class="zx-front-clear-both"></div>            
            <?php
        }//foreach
        ?>
        <?php
    }//if has wuqestions
    ?>
</div>

