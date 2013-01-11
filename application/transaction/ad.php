<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Ad as Model_Ad;
use \App\Model\User as Model_User;
use \App\Model\Tag as Model_Tag;
use \App\Model\Answer as Model_Answer;
use \Zx\Message\Message;
use App\Transaction\User as Transaction_User;
use \Zx\Model\Mysql;

class Ad {

    /**
     * mainly for front end
     * @param array $ad  record
     * @return string 
     */
    public static function get_link($ad) {
        $link = FRONT_HTML_ROOT . 'ad/content/' . $ad['id1'];
        return $link;
    }

    public static function extend($ad_id) {
        if (Model_Ad::exist_ad($ad_id)) {
            $arr = array('date_start' => date('Y-m-d h:i:s'),
                'date_end' => date * 'Y-m-d h:i:s', strtotime('+' . DAYS_OF_AD . ' days'),
            );
            return Model_Ad::update($ad_id, $arr);
        } else {
            Message::set_error_message('invalid ad id');
            return false;
        }
    }

    /**
     * available num of ads>weight>=1
     * 
     * not only create, score of an ad can be adjusted
     * 
     * score of this user needs to be adjusted simultaneously
     * 
     * new user score = current user score + original ad score - new ad score  
     * new user ad score = current user ad score - original ad score + new ad score, it's for effeciency
     * 
     * @param int $ad_id
     * @param int $weight
     */
    public static function adjust_score($ad_id, $score) {
        $success = false;
        $user = Transaction_User::get_user();
        //1. valid user
        if ($user) {
            //2. valid ad
            if (Model_Ad::ad_belong_to_user($ad_id, $user['id'])) {
                $ad = Model_Ad::get_one($ad_id);
                $score_restored = $user['score'] - $user['invalid_score'] - $user['ad_score'] + $ad['score'];
                $score_left = $score_restored - $score;
                if ($score_left >= 0) {
                    $ad_arr = array('score' => $score);
                    $user_arr = array('ad_score' => $user['ad_score'] - $ad['score'] + $score);
                    $success = true;
                } elseif ($score_restored >= 0) {
                    $ad_arr = array('score' => $score_restored);
                    $user_arr = array('ad_score' => $user['score'] - $user['invalid_score']);
                    $message = "仍有积分， 但积分不足";
                } else {
                    $message = "可用积分为0";
                }
                Model_Ad::update($ad_id, $ad_arr);
                Model_User::update($uid, $user_arr);
            } else {
                $message = "无效的广告序号";
            }
        } else {
            $message = "用户未登录";
        }
        if (!$success) {
            Message::set_error_message($message);
            return false;
        } else {
            return true;
        }
    }

    /**
     * in controller, check Model_User::has_score($this->uid) to make sure score is more than 1
     * create an ad:
     * 1. new record in ad table
     * 2. adjust score of user
     * 3. increase num_of_questions of user and tags
     * @param array $arr
     * @return boolean
     */
    public static function create_by_user($arr = array()) {
        $uid = $_SESSION['user']['uid'];
        $uname = $_SESSION['user']['uname'];
        $status = 1;
        if (count($arr) > 0 && isset($arr['title'])) {
            $tids = TNAME_SEPERATOR;
            $tnames = TNAME_SEPERATOR;
            $arr['tnames'] = array_unique($arr['tnames']); //remove duplicate entry
            foreach ($arr['tnames'] as $tag) {
                $tnames .= $tag . TNAME_SEPERATOR;
                if ($existing_tag = Model_Tag::exist_tag_by_tag_name($tag)) {
                    if (Model_Tag::is_active_tag($tag)) {
                        $tid = $existing_tag['id'];
                        Model_Tag::increase_num_of_ads($tid);
                        $tids .= $tid . TNAME_SEPERATOR;
                    } else {
                        //disabled tag cannot be added into column
                    }
                } else {
                    $tag_arr = array('name' => $tag, 'num_of_ads' => 1);  //have one already
                    $tid = Model_Tag::create($tag_arr);
                    $tids .= $tid . TNAME_SEPERATOR;
                }
            }
            $arr['tids'] = $tids;
            $arr['tnames'] = $tnames;
            $arr['uid'] = $uid;
            $arr['uname'] = $uname;
            $arr['status'] = $status;
            $arr['num_of_views'] = 0;
            // \Zx\Test\Test::object_log('$arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
            $user = Model_User::get_one($uid);
            if ($user['score'])
                if ($ad_id = Model_Ad::create($arr)) {
                    //self::adjust_score($ad_id, $arr['score']);

                    Message::set_success_message('新广告添加成功');
                    return true;
                } else {
                    Message::set_error_message('新广告添加失败');
                    return false;
                }
        } else {
            Message::set_error_message('新广告信息不完整');
            return false;
        }
    }

    /**
     * tag names change, tag ids change, and num_of_questions of tags change

     * @param type $id
     * @param type $arr
     * @return boolean
     */
    public static function update_ad($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (count($arr) > 0 && (isset($arr['title']) || isset($arr['content']))) {
            if (Model_Ad::update($id, $arr)) {

                //Todo: check tag
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('wrong info');
            return false;
        }
    }

    /**
     * usually done by user
     * 1. set status to S_DELETED
     * 2. if original status is not S_DELETED, 
     *    decrease num_of_ads in tag and user table
     *    and adjust score of a user
     * 3. set ad_id  to 0 for answers
     * @param int $id
     * @return boolean
     */
    public static function delete_by_user($id) {
        $ad = Model_Ad::get_one($id);
        if ($ad['status'] <> Model_Ad::S_DELETED) {
            $arr = array('status' => Model_Ad::S_DELETED);
            if (Model_Ad::update($id, $arr)) {
                Model_Answer::reset_ad_id($id);
                $tids = substr($ad['tids'], 1, -1); //remove first and last tag seperator
                Model_Tag::decrease_num_of_ads_by_tids($tids);
                Model_User::decrease_num_of_ads($ad['uid']);
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        }
    }

    /**
     * only admin can purge an ad
     * 1.delete record in ad table
     * 2. if original status is not S_DELETED, 
     *    decrease num_of_ads in tag and user table
     *    and adjust score of a user
     * 3. set ad_id  to 0 for answers
     * 
     * @param int $id
     * @return boolean
     */
    public static function purge_ad($id) {
        if (Model_Ad::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
            return false;
        }
    }

    /**
     * user can set status to S_INACTIVE, not display in public pages, but not delete it
     * the answers related it will set ad_id to 0
     * @param int $id
     */
    public static function deactivate_ad($id) {
        $arr = array('status' => Model_Ad::S_INACTIVE);
        if (Model_Ad::update($id, $arr)) {
            Model_Answer::reset_ad_id($id);
        }
    }

    /**
     * for cron job
     * backup ad table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM ad";
        $r = Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO ad VALUES ';
            foreach ($r as $row) {
                $fields = '';
                foreach ($row as $value) {
                    $fields .= '"' . $value . '",';
                }
                $fields = substr($fields, 0, -1); //remove last ','
                $str .= '(' . $fields . '),';
            }
            $str = substr($str, 0, -1); //remove last ','
            return $str;
            //Transaction_Swiftmail::send_string_to_admin($str);
        }
    }

}