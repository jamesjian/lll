<?php
namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Article as Model_Article;
use \App\Model\Articlereply as Model_Articlereply;
use \App\Model\Articlecategory as Model_Articlecategory;
use \App\Transaction\Article as Transaction_Article;
use \App\Transaction\Articlereply as Transaction_Articlereply;
use \Zx\View\View as Zx_View;
/**
 * only created by user
 * can be updated by admin
 */
class Articlereply extends Base {

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'articlereply/';
    }

    public function delete() {
        $id = isset($this->params[0]) ? intval($this->params[0]) : 0;
        if ($id>0) {
            Transaction_Articlereply::delete($id);
        } else {
            Zx_Message::set_error_message('无效记录。');
        }
        Transaction_Html::goto_previous_admin_page();
    }

    /**
     * only status and content can be updated
     * uid and article_id is no necessary to be changed
     */
    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id <> 0) {
                if (isset($_POST['content']))
                    $arr['content'] = trim($_POST['content']);
                if (Transaction_Articlereply::update($id, $arr)) {
                    $success = true;
                }
            }
        }else {
            $id = (isset($this->params[0])) ?  intval($this->params[0]) : 0;
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            $reply = Model_Articlereply::get_one($id);
            $article = Model_Article::get_one($reply['article_id']);
            Zx_View::set_view_file($this->view_path . 'update.php');
            Zx_View::set_action_var('reply', $reply);
            Zx_View::set_action_var('article', $article);
        }
    }

/**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Article Reply');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3]: '';
        if ($search != '') {
            $where = " a.title LIKE '%$search%' OR ar.content LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $article_list = Model_Articlereply::get_replies_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Articlereply::get_num_of_replies($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('article_list', $article_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_View::set_view_file($this->view_path . 'retrieve.php');
        Zx_View::set_action_var('article_list', $article_list);
        Zx_View::set_action_var('search', $search);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }
    /**
     * under one category
      retrieve_by_cat_id/cat_id/page/orderby/direction
     */
    public function retrieve_by_article_id() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_current_l1_menu('Article Reply');
        $article_id = isset($this->params[0]) ? intval($this->params[0]) :0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4]: '';
        $where = 1;
        $replys = Model_Articlereply::get_replies_by_article_id_and_page_num($article_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Articlereply::get_num_of_replies_by_article_id($article_id,$where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ARTICLES_IN_CAT_PAGE);
        //\Zx\Test\Test::object_log('article_list', $article_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_View::set_view_file($this->view_path . 'retrieve_by_article_id.php');
        Zx_View::set_action_var('article_id', $article_id);
        Zx_View::set_action_var('replys', $replys);
        Zx_View::set_action_var('search', $search);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

}
