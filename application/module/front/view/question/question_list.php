<?php
/**
 *this is a template of question list, be used by 
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
        <li>问题</li>
        <li>区域</li>
        <li>支持票数</li>
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
                    <a href="<?php echo $link_user; ?>">
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
