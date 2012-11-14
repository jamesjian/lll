<?php

defined('SYSPATH') or die('No direct script access.');

namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Answer as Model_Answer;

/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class Answer extends Base {
    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/user/view/answer/';
        parent::init();
    }
    /**
     * only my answers
     * pagination
     */
    public function my_answers()
    {
        
    }
}