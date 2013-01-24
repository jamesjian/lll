<?php

namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View as Zx_View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Ad as Model_Ad;
use \App\Model\Vote as Model_Vote;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Transaction\Vote as Transaction_Vote;

/**

 */
class Vote extends Base {

     public function init() {
        parent::init();
        $this->view_path = USER_VIEW_PATH . 'vote/';
    }
    

}