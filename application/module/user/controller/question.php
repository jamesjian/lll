<?php
namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Question as Model_Question;


/**
 * homepage: /=>/front/answer/latest/page/1
 * latest: /front/answer/latest/page/3
 * most popular:/front/answer/most_popular/page/3
 * answer under category: /front/answercategory/retrieve/$category_id_3/category_name.php
 * one: /front/answer/content/$id/$answer_url
 * keyword: /front/answer/keyword/$keyword_3
 */
class Question extends User {

    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/user/view/question/';
        parent::init();
    }
    /**
     * only my questions
     * pagination
     */
    public function my_questions()
    {
        
    }    
}