<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View as Zx_View;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Catgroup as Model_Catgroup;
class Catgroup {
    public $view_path;

    public function init() {
        $this->view_path = APPLICATION_PATH . 'module/front/view/catgroup/';
        parent::init();
    }

    /*     * one article
     * /front/article/content/niba
     * use url rather than id in the query string
     */

    public function content() {
        $catgroup_url = $this->params[0]; //it's url rather than an id

        $group = Model_Catgroup::get_one_by_url($catgroup_url);
        //\Zx\Test\Test::object_log('$article', $article, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if ($group) {
            
            $group_id = $group['id'];
            $home_url = HTML_ROOT;
            $group_url = FRONT_HTML_ROOT . 'catgroup/content/' . $group['title']; 
            Transaction_Session::set_breadcrumb(0, $home_url,  '首页');
            Transaction_Session::set_breadcrumb(1, $group_url,  $group['title']);
            Transaction_Html::set_title($group['title']);
            Transaction_Html::set_keyword($group['keyword']);
            Transaction_Html::set_description($group['title']);
            Model_Catgroup::increase_rank($group_id);
            $cats = Model_Category::get_cats_by_catgroup_id($group_id);
            $infos = Model_Info::get_infos_by_catgroup_id($group_id);
            Zx_View::set_view_file($this->view_path . 'one_catgroup.php');
            Zx_View::set_action_var('cats', $cats);
            Zx_View::set_action_var('group', $group);
            Zx_View::set_action_var('infos', $infos);
        } else {
            //if no article, goto homepage
            Transaction_Html::goto_home_page();
        }
    }

}