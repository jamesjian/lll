<?php
/**
 * this is a template of question list, be used by 
 * user_list, 
 * popular_list, 
 * latest_list, 
 * tag_list, 
 * unanswered_list, 
 * anaswered_list 
 */
?>
<div class="zx-front-question-list">
    <ul class="zx-front-question-list-heading">
        <li class="zx-front-question-list-heading-col1">区域</li>
        <li class="zx-front-question-list-heading-col2">问题</li>
        <li class="zx-front-question-list-heading-col3">支持数</li>
        <li class="zx-front-question-list-heading-col4">浏览量</li>
        <li class="zx-front-question-list-heading-col5">回答数</li>
    </ul>
    <div class="zx-front-clear-both"></div>
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
        foreach ($questions as $question) {
            $read_more_link = \App\Transaction\Question::get_link($question);
            $link_user = \App\Transaction\User::get_link($question['uid']);
            ?>
    <div class='zx-front-one-question'>
            <ul>
                <li class="zx-front-one-question-col1"><?php echo $regions[$question['region']]; ?></li>
                <li class="zx-front-one-question-col2"><a href='<?php echo $read_more_link; ?>' class=''>
                        <?php echo $question['title']; ?></a></li>
                <li class="zx-front-one-question-col3"><?php echo $question['num_of_votes']; ?></li>
                <li class="zx-front-one-question-col4"><?php echo $question['num_of_views']; ?></li>
                <li class="zx-front-one-question-col5"><?php echo $question['num_of_answers']; ?></li>
            </ul>
            <div class="zx-front-clear-both"></div>
            <ul>
                <li class="zx-front-one-question-col6">
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
                </li>
                <li class="zx-front-one-question-col7">
                    <a href="<?php echo $link_user; ?>">
                        <?php echo $question['uname']; ?></a>
                </li>
                <li class="zx-front-one-question-col8">
                    <?php echo $question['date_created']; ?>
                </li>
            </ul>  
            <div class="zx-front-clear-both"></div>
    </div>
            <?php
        }//foreach
        ?>
        <?php
    }//if has wuqestions
    ?>
</div>
