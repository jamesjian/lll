<?php
namespace App\Module\Admin\Controller;
defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Message\Message as Zx_Message;
use \App\Model\Region as Model_Region;
use \App\Transaction\Region as Transaction_Region;
use \App\Transaction\Html as Transaction_Html;
use \Zx\View\View as Zx_View;
use \Zx\Test\Test;

/**
 */
class Body extends Base {

    public $list_page = '';

    public function init() {
        parent::init();
        $this->view_path = ADMIN_VIEW_PATH . 'region/';
        $this->list_page = ADMIN_HTML_ROOT . 'region/retrieve/state/ASC/';
        //\App\Transaction\Session::set_ck_upload_path('body');
    }
    
    /**
      /page/orderby/direction/search
     * page, orderby, direction, search can be empty
     */
    public function retrieve() {
       if (!\App\Transaction\Html::previous_admin_page_is_search_page()) {
            \App\Transaction\Html::remember_current_admin_page();
        }
        \App\Transaction\Session::set_admin_current_l1_menu('Region');
        $order_by = isset($this->params[1]) ? $this->params[1] : 'state';
        $direction = isset($this->params[2]) ? $this->params[2] : 'ASC';
        $search = isset($this->params[3]) ? $this->params[3] : '';
        $where = '1';
        $region_list = Model_Body::get_regions($where, $order_by, $direction);
        //\Zx\Test\Test::object_log('body_list', $body_list, __FILE__, __LINE__, __CLASS__, __METHOD__);

        View::set_view_file($this->view_path . 'retrieve.php');
        View::set_action_var('region_list', $region_list);
        View::set_action_var('search', $search);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
    }
   }
