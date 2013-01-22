<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use \Zx\Model\Articlereply as Model_Articlereply;

class Articlereply extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = FRONT_VIEW_PATH . 'articlereply/';
    }

/**
     * must have content 
     * this create() is in front module, in the transaction, it will check if a user has logged in, 
     */
    public function reply() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit']) && isset($_POST['qid']) &&
                isset($_POST['content']) && !empty($_POST['content']) 
        ) {
            $qid = intval($_POST['qid']);
            if (Model_Question::exist_question($qid)) {
                $content = trim($_POST['content']);

                $arr = array(
                    'content' => $content,
                    'qid'=>$qid,
                );
                if (Transaction_Answer::reply_question($arr)) {
                    $success = true;
                }
            } else {
                 Zx_Message::set_error_message('无效问题');
                 //goto previous valid page
                 Transaction_Html::goto_previous_page();
            }
        } else {
            Zx_Message::set_error_message('您未回答问题。');
        }
        header('Location: ' . FRONT_HTML_ROOT . 'question/content/' . $qid);
        //always go to question or question list page
        
            /**
        if ($success) {
            header('Location: ' . $this->list_page);
        } else {
            View::set_view_file($this->view_path . 'create.php');
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
             * 
        }
             */
    }
    
    }
