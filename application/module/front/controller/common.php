<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \App\Model\Article as Model_Article;
use \App\Model\Question as Model_Question;
use \App\Model\Articlecategory as Model_Articlecategory;
use App\Transaction\Html as Transaction_Html;
use \Zx\View\View as Zx_View;

class Common extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/common/';
    }

    public function sitemap() {
        Transaction_Html::set_title('网站地图');
        Transaction_Html::set_keyword('');
        Transaction_Html::set_description('');
        //$cats = Model_Articlecategory::get_all_active_cats();
        //$articles = Model_Article::get_all_active_articles();
        Zx_View::set_view_file($this->view_path . 'sitemap.php');
        //View::set_action_var('cats', $cats);
        //View::set_action_var('articles', $articles);
    }

    public function contact_us() {
//\Zx\Test\Test::object_log('$arr', '22222', __FILE__, __LINE__, __CLASS__, __METHOD__);

        $submitted = false;
        if (isset($_POST['submit'])) {
            $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
            $name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
            $phone = (isset($_POST['phone'])) ? trim($_POST['phone']) : '';
            if (Transaction_Email::send_contact_us_email($email, $name, $phone)) {
                $submitted = true;
            }
        }
        if ($submitted) {
            Zx_View::set_view_file($this->view_path . 'contact_us_result.php');
        } else {
            Zx_View::set_view_file($this->view_path . 'contact_us.php');
        }
    }

    public function home() {
        //\Zx\Test\Test::object_log('lob', 'aaaa', __FILE__, __LINE__, __CLASS__, __METHOD__);
        Transaction_Html::set_title('首页');
        Transaction_Html::set_keyword('最新问题');
        Transaction_Html::set_description('最新问题');
        $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $where = ' status=1 ';
        $questions = Model_Question::get_all($where, 0, NUM_OF_QUESTIONS_IN_FRONT_PAGE, $order_by, $direction);
        Zx_View::set_view_file($this->view_path . 'home.php');
        Zx_View::set_action_var('questions', $questions);
    }

    public function home1() {
        Transaction_Html::set_title('首页');
        Transaction_Html::set_keyword('');
        Transaction_Html::set_description('');
        $current_page = 1;
        $order_by = 'date_created';
        $direction = 'DESC';
        $articles = Model_Article::get_active_articles_by_page_num($current_page, $order_by, $direction);
        $related_articles = Model_Article::get_10_active_related_articles(1);
        $num_of_articles = Model_Article::get_num_of_active_articles();
        $num_of_pages = ceil($num_of_articles / NUM_OF_ARTICLES_IN_CAT_PAGE);
        Zx_View::set_view_file($this->view_path . 'home.php');
        Zx_View::set_action_var('articles', $articles);
        Zx_View::set_action_var('related_articles', $related_articles);
        Zx_View::set_action_var('order_by', $order_by);
        Zx_View::set_action_var('direction', $direction);
        Zx_View::set_action_var('current_page', $current_page);
        Zx_View::set_action_var('num_of_pages', $num_of_pages);
    }

}
