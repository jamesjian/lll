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
    <?php
    if ($question) {
        switch ($question['status']) {
            //deleted or invalid
            case '0':
                //deleted by user
                echo "该问题已被提问用户取消。";
                break;
            case '2':
                //disabled by admin
                echo "该问题因违反网站规定被禁止浏览。";
                break;
            case '1':
                //valid question
                ?>
                <div class='zx-front-left2'>
                    <?php
                    $regions = \App\Model\Region::get_au_states_abbr();
                    $vote_link = FRONT_HTML_ROOT . 'vote/question' . $question['id'];
                    ?>
                    <article class="zx-front-question">
                        <header>
                            <h1 class="zx-front-question-title">问题： 
                                <?php echo $question['title'], BR; ?>
                                <span class="zx-front-state-name"><?php echo $regions[$question['region']]; ?></span>
                                <span class="zx-front-question-user">
                                    <?php echo $question['uname']; ?>
                                </span>                                
                                <span class="zx-front-claim">已有<?php echo $question['num_of_votes']; ?>人投票支持</span>
                                                              
                                    <a href="<?php echo $vote_link; ?>" class="zx-front-vote-link" title="如果这个问题值得关注， 请投票支持">投票支持</a>
                                    <?php
                                    if ($question['valid'] == 0) {
                                        $claim_link = FRONT_HTML_ROOT . 'claim/question' . $question['id'];
                                        //not sure it's valid or not, has report claim button
                                        ?>
                                        <a href = "<?php echo $claim_link; ?>" class = "zx-front-claim-link" title = "如果这个问题有违法或违规嫌疑， 请举报。">我要举报</a>
                                        <?php
                                    }
                                if (!\App\Transaction\User::user_has_loggedin()) {
                                    //remind user to login  (ajax)
                                    ?>
                                    (<a href="<?php echo FRONT_HTML_ROOT . 'user/login_form_popup'; ?>" class="zx-front-login-popup">登录</a>后才可以投票支持该问题或举报该问题)
                                    <?php
                                }
                                ?>
                            </h1>
                        </header>
                        <section>
                            <div class="zx-front-question-content">
                                <?php
                                echo $question['content'], BR;
                                ?>
                            </div>


                        </section>
                    </article>

                </div>
                <div class="zx-front-left3">
                    <?php
                    if ($answers) {
                        echo $num_of_answers . "个回答：";
                        $selected_ad_index = 0;
                        foreach ($answers as $answer) {
                            if ($answer['status']) {
                                +
                                        $vote_link = FRONT_HTML_ROOT . 'vote/answer' . $answer['id'];
                                ?>
                                <article class="zx-front-one-answer">
                                    <section>
                                        <div class="zx-front-question-content">
                                            <?php
                                            echo $answer['content'];
                                            ?>
                                        </div>
                                        <div>
                                            <?php echo $answer['uname']; ?>
                                            <a href="<?php echo $vote_link; ?>" class="zx-front-vote-link" title="如果这个回答很有帮助， 请投票">很有帮助</a>
                                            <?php
                                            if ($answer['valid'] == 0) {
                                                $claim_link = FRONT_HTML_ROOT . 'claim/answer' . $answer['id'];
                                                //not sure it's valid or not, has report claim button
                                                ?>
                                                <a href = "<?php echo $claim_link; ?>" class = "zx-front-claim-link" title = "如果这个回答有违法或违规嫌疑， 请举报。">我要举报</a>
                                                <?php
                                            }
                                            ?>                                        
                                        </div>
                                        <div>
                                            <?php
                                            //ad with this answer
                                            if ($answer['ad_id'] != 0 && $answer['ad_status'] == 1) {
                                                //active ad
                                                $ad_link = FRONT_HTML_ROOT . 'ad/content/' . $answer['ad_id'];
                                                ?>
                                                <a href="<?php echo $ad_link; ?>"><?php echo $answer['ad_title']; ?></a>
                                                <?php
                                            } else {
                                                //inacitve ad, use selected ads instead
                                                $ad_link = FRONT_HTML_ROOT . 'ad/content/' . $selected_ads[$selected_ad_index]['ad_id'];
                                                ?>
                                                <a href="<?php echo $ad_link; ?>"><?php echo $selected_ads[$selected_ad_index]['ad_title']; ?></a>
                                                <?php
                                                $selected_ad_index++;
                                            }
                                            ?>
                                        </div>
                                    </section>
                                </article>
                                <?php
                            }
                        }
                        //pagination of answers
                        if ($num_of_answers > NUM_OF_ITEMS_IN_ONE_PAGE) {
                            $link_prefix = HTML_ROOT . 'front/question/content/' . $question['id'] . '/page/';
                            $link_postfix = "/$order_by/$direction";
                            include FRONT_VIEW_PATH . 'templates/pagination.php';
                        }
                    }//if has answer
                    else {
                        echo "尚无回答。";
                    }
                    ?>
                </div>
                <div class="zx-front-left4">
                    <?php
                    //now we don't ask for login to answer a question
                    //if (Transaction_User::get_uid() > 0) {
                    //must have permission to answer a question
                    include FRONT_VIEW_PATH . 'answer/create.php';
                    //} else {
                    //    include FRONT_VIEW_PATH . 'user/popup_login_link.php';
                    //}
                    ?>
                </div>
                <?php
                break;
        }
    }
    ?>    
</div>
<?php
//the following is disabled now, we will check it later.
//the rigth column of the page is defined in template
?>
<!--
<div class='zx-front-right'>
    <div class='zx-front-right1'>
<?php //include FRONT_VIEW_PATH . 'templates/tag_cloud.php';    ?>
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
    $current_qid = $question['id'];
    foreach ($related_questions as $question) {
        if (!($question['id'] == $current_qid)) {
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

-->