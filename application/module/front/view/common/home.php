<div class='zx-front-left1'>
    <?php
    //similar to      FRONT_VIEW_PATH . 'question/question_list.php';
    //latest questions without pagination
    ?>
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
            $read_more_link = \App\Transaction\Question::get_link($question);
            $link_user = \App\Transaction\User::get_link($question['uid']);
                ?>		
                <ul class='zx-front-one-question'>
                    <li><a href='<?php echo $read_more_link; ?>' class='zx-front-latest-question'>
                            <?php echo $question['title']; ?></a>
                        <?php
                        $tids = explode(TNAME_SEPERATOR, $question['tids']);
                        $tnames = explode(TNAME_SEPERATOR, $question['tnames']);
                        foreach ($tids as $index => $tag_id) {
                            $tag_link = FRONT_HTML_ROOT . 'question/tag/' . $tag_id . '/latest/1';
                            ?>
                            <a href='<?php echo $tag_link; ?>' class='zx-front-tag'>
                                <?php echo $tnames[$index]; ?></a>            
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

</div>
<div class="zx-front-left2">
    点击这里可以查看更多最新问题， 点击这里可以发布您的新问题， 
    要获得更多个性化服务， 请点击这里注册用户， 或者如果您已注册， 点击这里登录，
    点击这里可以查看用户提供的最新信息。 
</div>
