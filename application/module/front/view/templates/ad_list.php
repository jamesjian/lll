<div class="zx-front-ad-list">
    <ul class="zx-front-ad-list-heading">
        <li>标题</li>
        <li>浏览量</li>
        <li>发布时间</li>
    </ul>
    <?php
    /**
     * each ad has:
     * title tags user date created
     * num of views
     */
    if ($ads) {
        ?>
        <?php
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
                <li><?php echo $ad['num_of_views']; ?></li>
                <li><?php echo $ad['date_created']; ?></li>
            </ul>  
            <?php
        }//foreach
        ?>
        <?php
    }//if has wuqestions
    ?>
</div>
