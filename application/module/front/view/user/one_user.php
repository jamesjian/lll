<?php
\Zx\Message\Message::show_message();
include 'search.php';
if ($user) {
    $uid = $user['id'];
    $link_questions = FRONT_HTML_ROOT . 'question/retrive_by_uid/' . $uid;
    $link_answers = FRONT_HTML_ROOT . 'answer/retrive_by_uid/' . $uid;

    echo $user['id'];
    echo $user['uname'];
    echo $user['score'];
    if ($user['num_of_questions'] > 0) {
        ?>
        <a href='<?php echo $link_questions; ?>'><?php echo $user['num_of_questions']; ?>问题</a>
        <?php
    }
    if ($user['num_of_answers'] > 0) {
        ?>
        <a href='<?php echo $link_answers; ?>'><?php echo $user['num_of_answers']; ?>回答</a>
        <?php
    }
    if ($user['num_of_ads'] > 0) {
        ?>    
        <a href='<?php echo $link_answers; ?>'><?php echo $user['num_of_ads']; ?>广告</a>
        <?php
    }
    if ($recent_questions) {
        foreach ($recent_questions as $question) {
            $link = FRONT_HTML_ROOT . 'question/content/' . $question['id'];
            ?>

            <ul>
                <li><a href='<?php echo $link; ?>'><?php echo $question['title']; ?></a></li>
            </ul>
            <?php
        }
    }
    if ($recent_answers) {
        foreach ($recent_ansers as $answer) {
            $link = FRONT_HTML_ROOT . 'question/content/' . $answer['qid'];
            ?>

            <ul>
                <li><a href='<?php echo $link; ?>'><?php echo $answer['title']; ?></a></li>
            </ul>
            <?php
        }
    }
    if ($recent_ads) {
        foreach ($recent_ads as $ad) {
            $link = FRONT_HTML_ROOT . 'ad/content/' . $ad['id'];
            ?>

            <ul>
                <li><a href='<?php echo $link; ?>'><?php echo $ad['title']; ?></a></li>
            </ul>
            <?php
        }
    }
} else {
    echo 'No record.';
}




