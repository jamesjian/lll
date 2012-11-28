<?php

namespace App\Module\Front\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use \Zx\Model\Pagecategory as Model_Pagecategory;

class Pagecategory extends Base {

    public $view_path;

    public function init() {
        parent::init();
        $this->view_path = APPLICATION_PATH . 'module/front/view/pagecategory/';
    }

    //one page category
    public function show() {
		$cat_id = $params[0];
        $cat = Model_Pagecategory::get_one($cat_id);
        View::set_view_file($this->view_path . 'show.php');
        View::set_action_var('cat', $cat);
    }
}
