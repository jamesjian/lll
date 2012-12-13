<div class="zx-front-question-list">
    <ul class="zx-front-question-list-heading">
        <li>问题</li>
        <li>区域</li>
        <li>支持数</li>
        <li>浏览量</li>
        <li>答案数量</li>
    </ul>
    <?php
    /**
     * each question has:
     * title tags user date created and region
     * num of views
     * num of answers
     * num of votes
     */
    if ($questions) {
        $regions = \App\Model\Region::get_au_states_abbr();
        ?>
        <?php
        foreach ($questions as $question) {
            $read_more_link = FRONT_HTML_ROOT . 'question/content/' . $question['id'];
            ?>		
            <ul class='zx-front-one-question'>
                <li><a href='<?php echo $read_more_link; ?>' class='zx-front-latest-question'>
                        <?php echo $question['title']; ?></a>
                    <?php
                    $tag_ids = explode(',', $question['tag_ids']);
                    $tag_names = explode(',', $question['tag_names']);
                    foreach ($tag_ids as $index => $tag_id) {
                        $tag_link = FRONT_HTML_ROOT . 'question/tag/' . $tag_id . '/latest/1';
                        ?>
                        <a href='<?php echo $tag_link; ?>' class='zx-front-tag'>
                            <?php echo $tag_names[$index]; ?></a>            
                            <?php
                        }
                        ?>
                    <a href="<?php echo FRONT_HTML_ROOT . 'user/detail' . $question['uid']; ?>">
                        <?php echo $question['uname']; ?></a>
                    <?php echo $question['date_created']; ?>
                </li>
                <li><?php echo $regions[$question['region']]; ?></li>
                <li><?php echo $question['num_of_votes']; ?></li>
                <li><?php echo $question['num_of_answers']; ?></li>
            </ul>  
            <?php
        }//foreach
        ?>
        <?php
    }//if has wuqestions
    ?>
</div>
