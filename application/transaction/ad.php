<?php
namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Ad as Model_Ad;
use \App\Model\User as Model_User;
use \Zx\Message\Message;
use App\Transaction\User as Transaction_User;
use \Zx\Model\Mysql;

class Ad {
    /**
     * mainly for front end
     * @param array $ad  record
     * @return string 
     */
    public static function get_link($ad)
    {
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
     * @param int $ad_id
     * @param int $weight
     */
    public static function adjust_score($ad_id, $score) {
        $success = false;
        $user = Transaction_User::get_user();
        //1. valid user
        if ($user) {
            //2. valid ad
            if (Model_Ad::ad_belong_to_user($ad_id)) {
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
     * in controller, check Model_User::available_score($this->uid) to make sure score is more than 1
     * @param array $arr
     * @return boolean
     */
    public static function create_by_user($arr = array()) {
        $score = $arr['score'];
        $arr['score'] = 0; //create ad firstly, then adjust score
        if (count($arr) > 0 && isset($arr['title'])) {
            if ($ad_id = Model_Ad::create($arr)) {
                self::adjust_score($ad_id, $score);
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

    public static function delete_ad($id) {
        if (Model_Ad::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
            return false;
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