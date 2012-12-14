<div class="zx-front-ad-list">
    <ul class="zx-front-ad-list-heading">
        <li>标题</li>
        <li>发布人</li>
        <li>区域</li>
        <li>分值</li>
        <li>发布时间</li>
    </ul>
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
                <li><a href='<?php echo $read_more_link; ?>' class='zx-front-latest-ad'>
                        <?php echo $ad['title']; ?></a>
                    <?php
                    $tids = explode(',', $ad['tids']);
                    $tnames = explode(',', $ad['tnames']);
                    foreach ($tids as $index => $tag_id) {
                        $tag_link = FRONT_HTML_ROOT . 'ad/tag/' . $tag_id;
                        ?>
                        <a href='<?php echo $tag_link; ?>' class='zx-front-tag'>
                            <?php echo $tnames[$index]; ?></a>            
                            <?php
                        }
                        ?>
                    <a href="<?php echo FRONT_HTML_ROOT . 'user/profile' . $ad['uid']; ?>">
                        <?php echo $ad['uname']; ?></a>
                    <?php echo $ad['date_created']; ?>
                </li>
                 <li><?php echo $regions[$question['region']]; ?></li>
                <li><?php echo $ad['score']; ?></li>
                <li><?php echo $ad['date_created']; ?></li>
            </ul>  
            <?php
        }//foreach
        ?>
        <?php
    }//if has wuqestions
    ?>
</div>
