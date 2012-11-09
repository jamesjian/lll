<?php

defined('SYSPATH') or die('No direct script access.');

namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Tag as Model_Tag;

/**
 * homepage: /=>/front/question/latest/page/1
 * latest: /front/question/latest/page/3
 * most popular:/front/question/most_popular/page/3
 * question under category: /front/questioncategory/retrieve/$category_id_3/category_name.php
 * one: /front/question/content/$id/$question_url
 * keyword: /front/question/keyword/$keyword_3
 */
class Question extends Base {

    public function home() {
        View::set_view_file($this->view_path . 'home.php');
    }

}