<?php

namespace App\Module\Front\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Question as Transaction_Question;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Vote as Model_Vote;
use App\Transaction\User as Transaction_User;

/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class Vote extends Base {

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/vote/';
        $this->list_page = FRONT_HTML_ROOT . 'vote/all/';
    }

    

}
