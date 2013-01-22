<?php
namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Article as Model_Article;
use \App\Model\Articlecategory as Model_Articlecategory;
use \App\Transaction\Article as Transaction_Article;
use \Zx\View\View as Zx_View;
use \Zx\Test\Test;

class Article extends Base {

    public $list_page = '';
    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'article/';
        $this->list_page =  ADMIN_HTML_ROOT . 'article/retrieve/1/title/ASC/';
        \App\Transaction\Session::set_ck_upload_path('article');
    }

    public function create() {
        $success = false;
        if (isset($_POST['submit'])) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
            $abstract = isset($_POST['abstract']) ? trim($_POST['abstract']) : '';
            $cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 1;
            $num_of_views = isset($_POST['num_of_views']) ? intval($_POST['num_of_views']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($title <> '') {
                $arr = array('title' => $title, 
                    'content' => $content, 
                    'keyword'=>$keyword,
                    'abstract'=>$abstract, 
                    'num_of_views'=>$num_of_views,
                    'status'=>$status,
                    'cat_id' => $cat_id);
                if (Transaction_Article::create_article($arr)) {
                    $success = true;
                }
            }
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            $cats = Model_Articlecategory::get_all_cats();
            Zx_Veiw::set_view_file($this->view_path . 'create.php');
            Zx_Veiw::set_action_var('cats', $cats);
        }
    }

    /**
     * usually cannot be deleted when has reply
     */
    public function delete() {
        $id = $this->params[0];
        Transaction_Article::delete_article($id);
        Transaction_Html::goto_previous_admin_page();
    }

    public function update() {
        $success = false;
        if (isset($_POST['submit']) && isset($_POST['id'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            //\Zx\Test\Test::object_log('id', $id, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array();
            if ($id <> 0) {
                if (isset($_POST['title']))
                    $arr['title'] = trim($_POST['title']);
                if (isset($_POST['title_en']))
                    $arr['content'] = trim($_POST['content']);
                if (isset($_POST['keyword']))
                    $arr['keyword'] = trim($_POST['keyword']);
                if (isset($_POST['keyword_en']))
                    $arr['abstract'] = trim($_POST['abstract']);                
                if (isset($_POST['url']))
                    $arr['cat_id'] = intval($_POST['cat_id']);
                if (isset($_POST['rank']))
                    $arr['num_of_views'] = intval($_POST['num_of_views']);
                if (isset($_POST['status']))
                    $arr['status'] = intval($_POST['status']);
                if (Transaction_Article::update_article($id, $arr)) {
                    $success = true;
                }
            }
        } else {
             $id = (isset($this->params[0])) ?  intval($this->params[0]) : 0;
        }
        if ($success) {
            Transaction_Html::goto_previous_admin_page();
        } else {
            $article = Model_Article::get_one($id);
            $cats = Model_Articlecategory::get_cats();
            //\Zx\Test\Test::object_log('cats', $cats, __FILE__, __LINE__, __CLASS__, __METHOD__);
            Zx_Veiw::set_view_file($this->view_path . 'update.php');
            Zx_Veiw::set_action_var('article', $article);
            Zx_Veiw::set_action_var('cats', $cats);
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
        \App\Transaction\Session::set_admin_current_l1_menu('Article');
        $current_page = isset($this->params[0]) ? intval($this->params[0]) : 1;
        $order_by = isset($this->params[1]) ? $this->params[1] : 'id';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3]: '';
        if ($search != '') {
            $where = " b.title LIKE '%$search%' OR bc.title LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $article_list = Model_Article::get_articles_by_page_num($where, $current_page, $order_by, $direction);
        $num_of_records = Model_Article::get_num_of_articles($where);
        $num_of_pages = ceil($num_of_records / NUM_OF_RECORDS_IN_ADMIN_PAGE);
        //\Zx\Test\Test::object_log('article_list', $article_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_Veiw::set_view_file($this->view_path . 'retrieve.php');
        Zx_Veiw::set_action_var('article_list', $article_list);
        Zx_Veiw::set_action_var('search', $search);
        Zx_Veiw::set_action_var('order_by', $order_by);
        Zx_Veiw::set_action_var('direction', $direction);
        Zx_Veiw::set_action_var('current_page', $current_page);
        Zx_Veiw::set_action_var('num_of_pages', $num_of_pages);
    }
    /**
     * under one category
      retrieve_by_cat_id/cat_id/page/orderby/direction
     */
    public function retrieve_by_cat_id() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_current_l1_menu('Article');
        $cat_id = isset($this->params[0]) ? intval($this->params[0]) :0;
        $current_page = isset($this->params[1]) ? intval($this->params[1]) : 1;
        $order_by = isset($this->params[2]) ? $this->params[2] : 'id';
        $direction = isset($this->params[3]) ? $this->params[3] : 'ASC';
        $search = isset($this->params[4]) ? $this->params[4]: '';
        if ($search != '') {
            $where = " b.title LIKE '%$search%' OR bc.title LIKE '%$search%'";
        } else {
            $where = '1';
        }
        $article_list = Model_Article::get_articles_by_cat_id_and_page_num($cat_id, $where, $current_page, $order_by, $direction);
        $num_of_records = Model_Article::get_num_of_articles_by_cat_id($cat_id, $where);
        $num_of_pages = ceil($num_of_records / NUM_OF_ARTICLES_IN_CAT_PAGE);
        //\Zx\Test\Test::object_log('article_list', $article_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        Zx_Veiw::set_view_file($this->view_path . 'retrieve_by_cat_id.php');
        Zx_Veiw::set_action_var('cat_id', $cat_id);
        Zx_Veiw::set_action_var('article_list', $article_list);
        Zx_Veiw::set_action_var('search', $search);
        Zx_Veiw::set_action_var('order_by', $order_by);
        Zx_Veiw::set_action_var('direction', $direction);
        Zx_Veiw::set_action_var('current_page', $current_page);
        Zx_Veiw::set_action_var('num_of_pages', $num_of_pages);
    }

}
