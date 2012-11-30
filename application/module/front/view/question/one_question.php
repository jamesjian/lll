<?php
/**
 * left ads                     tag cloud
 *  question                        right ads
 * answers                       related questions
 *                              latest questions
 */
?>

<div class='zx-front-left'>	
    <div class="zx-front-breadcrumb">
        <?php echo \App\Transaction\Session::get_breadcrumb(); ?>
    </div>
    <div class='zx-front-left1'>
        <?php include FRONT_VIEW_PATH . 'templates/left_google_ads.php'; ?>
    </div>	
    <div class='zx-front-left2'>
        <?php
        if ($question) {
            $regions = \App\Model\Region::get_au_states_abbr();
            $vote_link = FRONT_HTML_ROOT . 'question/vote/' . $question['id'];
            $abuse_link = FRONT_HTML_ROOT . 'question/abuse/' . $question['id'];
            ?>
            <article>
                <header>
                    <h1 class="zx-front-question-title">
                        <a href="<?php echo $vote_link;?>" class="zx-front-vote-link" title="如果这个问题值得关注， 请投票">关注度</a>
                        <a href="<?php echo $abuse_link;?>" class="zx-front-abuse-link" title="如果这个问题有违法或违规嫌疑， 请举报。">举报</a>
                        <?php
                        echo $regions[$question['region']],
                         $question['title'], BR;
                        ?>
                    </h1>
                </header>
                <section>
                    <div class="zx-front-question-content">
                        <?php
                        echo $question['content'], BR;
                        ?>
                    </div>
                    <div class="zx-front-question-user">
                        <?php echo $question['user_name']; ?>
                    </div>

                </section>
            </article>
            <?php
        }
        ?>
    </div>
    <div class="zx-front-left3">
        <?php
        foreach ($answers as $answer) {
            ?>
            <article>
                <section>
                    <div class="zx-front-question-content">
                        <?php
                        echo $answer['content'];
                        ?>
                    </div>
                    <div>
                        <?php echo $answer['user_name']; ?>

                    </div>
                </section>
            </article>
            <?php
        }
        //pagination of answers
        if ($num_of_answers > NUM_OF_ITEMS_IN_ONE_PAGE) {
            $link_prefix = HTML_ROOT . 'front/question/content/' . $question['id'] . '/page/';
            $link_postfix = "/$order_by/$direction";
            include FRONT_VIEW_PATH . 'templates/pagination.php';
        }
        ?>
    </div>
    <div class="zx-front-left4">
        <?php
        //now we don't ask for login to answer a question
        //if (Transaction_User::get_user_id() > 0) {
            //must have permission to answer a question
            include FRONT_VIEW_PATH . 'answer/create.php';
        //} else {
        //    include FRONT_VIEW_PATH . 'user/popup_login_link.php';
        //}
        ?>
    </div>
</div>
<div class='zx-front-right'>
    <div class='zx-front-right1'>
        <?php //include FRONT_VIEW_PATH . 'templates/tag_cloud.php'; ?>
    </div>	
    <div class="zx-front-right2">
        <?php
//related articles
        if ($related_questions) {
            ?>
            <span class="zx-front-related-article">相关问题：</span> 
            <nav>
                <ul>
                    <?php
                    $current_question_id = $question['id'];
                    foreach ($related_questions as $question) {
                        if (!($question['id'] == $current_question_id)) {
                            $read_more_link = HTML_ROOT . 'front/question/content/' . $question['id'];
                            ?>		
                            <li><?php echo "<a href='$read_more_link' class='zx-front-related-question'>" . $question['title'] . "</a>";
                            ?>
                            </li>
                            <?php
                        }
                    }//foreach
                    ?>
                </ul>
            </nav>	
            <?php
        }//if ($related_articles)
        ?>        
    </div>    
    <div class='zx-front-right3'>
        <?php include FRONT_VIEW_PATH . 'templates/right_google_ads.php'; ?>
    </div>
    <div class='zx-front-right4'>
        <?php include FRONT_VIEW_PATH . 'templates/latest_questions.php'; ?>
    </div>

</div>
