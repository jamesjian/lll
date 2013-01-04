<?php

namespace App\Module\Admin\Controller;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answser;
use \App\Model\Vote as Model_Vote;
use \Zx\View\View;
use \Zx\Test\Test;

//must have item type (1: question, 2: answer, 3: ad)
class Vote extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'vote/';
        $this->list_page = ADMIN_HTML_ROOT . 'vote/retrieve/1/date_created/DESC/'; //default list all votes 
        \App\Transaction\Session::set_ck_upload_path('claim');
    }
    public function retrieve()
    {
        if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \Zx\Test\Test::object_log('cats2222', $_SESSION, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        //\App\Transaction\HTML::set_admin_current_l1_menu('User');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        $where = '1';
        if ($search != '') {
            //$where = " uname LIKE '%$search%' OR email LIKE '%$search%'";
        } 
        $vote_list = Model_Vote::get_records_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_User::get_num_of_records($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ITEMS_IN_ONE_PAGE);
        //\Zx\Test\Test::object_log('user_list', $user_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('vote_list', $vote_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }
    /**
     * for user
     */
    public function retrieve_by_uid()
    {
        
    }    
    /**
     * for question
     */
    public function retrieve_by_qid()
    {
        
    }    
    /**
     * for answer
     */
    public function retrieve_by_aid()
    {
        
    }


}    
