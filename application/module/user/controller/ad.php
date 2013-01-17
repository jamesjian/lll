<?php

namespace App\Module\User\Controller;

use \Zx\Controller\Route;
use \Zx\View\View;
use \Zx\Message\Message as Zx_Message;
use App\Transaction\Tool as Transaction_Tool;
use App\Transaction\Session as Transaction_Session;
use App\Transaction\Html as Transaction_Html;
use \App\Model\Ad as Model_Ad;
use \App\Model\User as Model_User;
use \App\Transaction\Ad as Transaction_Ad;

/**

 * homepage: /=>/front/ad/latest/page/1
 * latest: /front/ad/latest/page/3
 * most popular:/front/ad/most_popular/page/3
 * ad under category: /front/adcategory/retrieve/$category_id_3/category_name.php
 * one: /front/ad/content/$id/$ad_url
 * keyword: /front/ad/keyword/$keyword_3
 */
class Ad extends Base {

    public $view_path;
    public $list_page;

    public function init() {
        parent::init();
        $this->view_path = USER_VIEW_PATH . 'ad/';
        $this->list_page = USER_HTML_ROOT . 'ad/user/' . $this->user['id'];
    }

    /**
     * extend validation date
     * ajax
     */
    public function extend() {
        $ad_id = (isset($this->params[0])) ? intval($this->params[0]) : 0;
        $result = false;
        if ($ad_id > 0) {
            if (Transaction_Ad::extend_ad($ad_id)) {
                $ad = Model_Ad::get_one($ad_id);
                $result = true;
                View::set_view_file($this->view_path . 'extend_result.php');
                View::set_action_var('ad', $ad);
                View::do_not_use_template();
            }
        } else {
            //todo
        }
    }

    /**
     * only change score
     * others are changed in update() method
     */
    public function adjust_score() {
        $success = false;
        $posted = array();
        if (isset($_POST['submit']) &&
                isset($_POST['ad_id']) && !empty($_POST['ad_id']) &&
                isset($_POST['score']) && !empty($_POST['score'])) {
            $ad_id = intval($_POST['ad_id']);
            if (Model_Ad::ad_belong_to_user($ad_id, $this->uid)) {
                $score = intval($_POST['score']);

                $arr = array('ad_id' => $ad_id,
                    'score' => $score,
                );
                if (Transaction_Ad::adjust_score($arr)) {
                    $success = true;
                }
            } else {
                Zx_Message::set_error_message('您没有权限更改该广告。');
                Transaction_Html::goto_previous_user_page();
            }
        } else {
            Zx_Message::set_error_message('无效的操作。');
            Transaction_Html::goto_previous_user_page();
        }
        if ($success) {
            Transaction_Html::goto_previous_user_page();
        } else {
            $ad = Model_Ad::get_one($ad);
            if ($ad['uid'] == $this->uid) {
                View::set_view_file($this->view_path . 'adjust_score.php');
                View::set_action_var('posted', $posted);
                View::set_action_var('ad', $ad);
                View::set_action_var('user', $user);
            } else {
                Zx_Message::set_error_message('您没有权限更改该广告。');
                Transaction_Html::goto_previous_user_page();
            }
        }
    }

    /**
     * list ads by user id, the user must be current loggin user
     * it's not a public page
     * pagination
     * ad/myad/userid/page
     */
    public function myad() {
        if (!\App\Transaction\Html::previous_user_page_is_search_page()) {
            \App\Transaction\Html::remember_current_user_page();
        }
        $uid = $this->uid;
        //\Zx\Test\Test::object_log('$cat_title', $cat_title, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $current_page = (isset($this->params[2])) ? intval($this->params[2]) : 1;  //default page 1
        //$tag_url = FRONT_HTML_ROOT . 'ad/tag/' . $tag['id']; 
        $order_by = 'date_created';
        $direction = 'DESC';
        $where = "1";
        $ads = Model_Ad::get_undeleted_ads_by_uid_and_page_num($uid, $where, $current_page, $order_by, $direction);
        //\Zx\Test\Test::object_log('$ads', $ads, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $num_of_ads = Model_Ad::get_num_of_undeleted_ads_by_uid($uid);
        $num_of_pages = ceil($num_of_ads / NUM_OF_ITEMS_IN_ONE_PAGE);
        View::set_view_file($this->view_path . 'my_ads.php');
        View::set_action_var('user', $this->user);
        View::set_action_var('ads', $ads);
        View::set_action_var('order_by', $order_by);
        View::set_action_var('direction', $direction);
        View::set_action_var('current_page', $current_page);
        View::set_action_var('num_of_pages', $num_of_pages);
    }

    /**
     * user can set status to S_INACTIVE, not display in public pages, but not delete it
     * the answers related it will set ad_id to 0
     */
    public function deactivate() {
        $ad_id = intval($this->params['0']);
        if (Model_Ad::ad_belong_to_user($ad_id, $this->uid)) {
            Transaction_Ad::deactivate($ad_id);
        } else {
            Zx_Message::set_error_message('您没有权限修改该广告');
        }
        header('Location:' . $this->list_page);
    }

    public function create() {
        \Zx\Test\Test::object_log('$_POST', $_POST, __FILE__, __LINE__, __CLASS__, __METHOD__);

        $success = false;
        $posted = array();
        $errors = array();
        if (Model_User::has_score($this->uid)) {
            //must has score (>1)
            if (isset($_POST['submit'])) {
                if (isset($_POST['title']) && !empty($_POST['title']) &&
                        isset($_POST['content']) && !empty($_POST['content']) &&
                        !empty($_POST['score']) && intval($_POST['score']) <= $this->user['score'] &&
                        (!empty($_POST['tname1']) || !empty($_POST['tname2']) ||
                        !empty($_POST['tname3']) || !empty($_POST['tname4']) ||
                        !empty($_POST['tname5']))) {
                    $title = trim($_POST['title']);
                    $region = isset($_POST['region']) ? trim($_POST['region']) : 'AU';
                    $score = isset($_POST['score']) ? intval($_POST['score']) : 1;
                    $tnames = array();
                    for ($i = 1; $i <= NUM_OF_TNAMES_PER_ITEM; $i++) {
                        $index = 'tname' . $i;
                        if (isset($_POST[$index])) {
                            $tag = Transaction_Tool::get_clear_string($_POST[$index]);
                            if ($tag <> '') {
                                //only contain valid tag
                                $tnames[] = $tag;
                            }
                        }
                    }
                    $content = trim($_POST['content']);
                    $arr = array('title' => $title,
                        'tnames' => $tnames,
                        'score' => $score,
                        'content' => $content,
                        'region' => $region,
                    );
                    //\Zx\Test\Test::object_log('$arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
                    if (Transaction_Ad::create_by_user($arr)) {
                        $success = true;
                    }
                } else {
                    Zx_Message::set_error_message('标题， 内容, 分值和关键词请填写完整, 分值必须在您的积分范围内。');
                }
            }
            if ($success) {
                header('Location: ' . $this->list_page);
            } else {
                View::set_view_file($this->view_path . 'create.php');
                View::set_action_var('posted', $posted);
                View::set_action_var('errors', $errors);
            }
        } else {
            //error message is from transaction
            header('Location: ' . $this->list_page);
        }
    }

    /**
     * only set status to S_DELETED
     */
    public function delete() {
        $id = $this->params[0];
        Transaction_Ad::delete_by_user($id);
        header('Location: ' . $this->list_page);
    }

    /**
     * cannot change score
     * score is changed in adjust_weight() method
     */
    public function update() {
        $success = false;
        $posted = array();
        $errors = array();
        if (isset($_POST['submit'])) {
            if (isset($_POST['id']) && !empty($_POST['id']) &&
                    isset($_POST['title']) && !empty($_POST['title']) &&
                    isset($_POST['content']) && !empty($_POST['content']) &&
                    isset($_POST['tnames']) && !empty($_POST['tnames'])
            ) {
                $id = intval($_POST['id']);
                $title = trim($_POST['title']);
                $tnames = trim($_POST['tnames']);
                $content = trim($_POST['content']);

                $arr = array('title' => $title,
                    'tnames' => $tnames,
                    'content' => $content,
                );
                if (Transaction_Ad::update_by_user($id, $arr)) {
                    //if success
                    header('Location: ' . $this->list_page);
                }
            } else {
                Zx_Message::set_error_message('title, content, tag can not be empty。');
            }
        } else {
            $id = $this->params[0];
        }

        $ad = Model_Ad::get_one($id);
        //must have a valid ad
        if ($ad) {
            View::set_view_file($this->view_path . 'update.php');
            View::set_action_var('id', $id);
            View::set_action_var('ad', $ad);
            View::set_action_var('posted', $posted);
            View::set_action_var('errors', $errors);
        } else {
            header('Location: ' . $this->list_page);
        }
    }

}