<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Ad as Model_Ad;
use \App\Model\User as Model_User;
use \App\Model\Tag as Model_Tag;
use \App\Model\Answer as Model_Answer;
use \Zx\Message\Message as Zx_Message;
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
            Zx_Message::set_error_message('无效的记录');
            return false;
        }
    }

    /**
     * 
     * ad_score of this user needs to be adjusted simultaneously
     * 
     * new ad_score = current ad_score - original ad score + new ad score, it's for effeciency
     * 
     * @param int $ad_id
     * @param int $weight
     */
    public static function adjust_score($ad_id, $score) {
        $success = false;
        $ad = Model_Ad::get_one($ad_id);
        if ($ad) {
            $user = Model_User::get_one($ad['uid']);
            //check score is enough for new score
            $score_restored = $user['score'] - $user['invalid_score'] - $user['ad_score'] + $ad['score'];
            $score_left = $score_restored - $score;
            if ($score_left >= 0) {
                $ad_arr = array('score' => $score);
                $user_arr = array('ad_score' => $user['ad_score'] - $ad['score'] + $score);
                Model_Ad::update($ad_id, $ad_arr);
                Model_User::update($uid, $user_arr);
                $success = true;
            } elseif ($score_restored >= 0) {
                $message = "仍有积分， 但积分不足";
            } else {
                $message = "可用积分为0";
            }
        } else {
            $message = "无效的广告";
        }
        if (!$success) {
            Zx_Message::set_error_message($message);
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
        $user = Model_User::get_one($uid);
        $remaining_score = $user['score'] - $user['invalid_score'] - $user['ad_score'];
        if ($remaining_score > 0) {
            //must have score
            if ($remaining_score < $arr['score']) {
                //if socre higher than remaining score, use remaining score
                $arr['score'] = $remaining_score;
            }
            if (count($arr) > 0 && isset($arr['title'])) {
                //prepare for tags
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
                $arr['uname'] = $_SESSION['user']['uname'];
                $arr['status'] = Model_Ad::S_ACTIVE;
                $arr['num_of_views'] = 0;
                // \Zx\Test\Test::object_log('$arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

                if ($ad_id = Model_Ad::create($arr)) {
                    //self::adjust_score($ad_id, $arr['score']);
                    $arr = array(
                        'num_of_ads' => $user['num_of_ads'] + 1,
                        'ad_score' => $user['ad_score'] + $arr['score'],
                    );
                    Model_User::update($uid, $arr);
                    Zx_Message::set_success_message('新广告添加成功');
                    return true;
                } else {
                    Zx_Message::set_error_message('系统错误， 请重试或与网站管理员联络');
                    return false;
                }
            } else {
                Zx_Message::set_error_message('新广告信息不完整');
                return false;
            }
        } else {
            Zx_Message::set_error_message('您的积分为0， 不能发布新的广告。 发布新问题和回答别人的提问可以获得积分。');
        }
    }

    /**
     * only by admin 
     * when change status:
     * 1. status of ad
     * 2. if change to S_DELETED or S_DISABLED, will reset ad_id in answer table to 0
     * 3. this one only change status, not change any score
     *    if a problem is made by claim, don't use this method, use claim methods to change status, 
     *    
     * @param int $id
     * @param int $status
     */
    public static function update_status($id, $status) {
        $ad = Model_Ad::get_one($id);
        if ($ad) {
             $arr = array('status'=>$status);
            switch ($status) {
                case Model_Ad::S_ACTIVE: 
                    break;
                case Model_Ad::S_CLAIMED: 
                    break;
                case Model_Ad::S_INACTIVE: 
                    break;
                case Model_Ad::S_CORRECT: 
                    break;
                case Model_Ad::S_DELETED: 
                    Model_Answer::reset_ad_id($id);
                    break;                
                case Model_Ad::S_DISABLED: 
                    Model_Answer::reset_ad_id($id);
                    break;                
            }
            Model_Ad::update($id, $arr);
        } else {
            Zx_Message::set_error_message('无效记录。');
        }
    }

    /**
     * status is not involved
     * tag names change, tag ids change, and num_of_questions of tags change

     * @param type $id
     * @param type $arr
     * @return boolean
     */
    public static function update_ad($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $question = Model_Question::get_one($id);
        if ($question['status'] == Model_Question::S_CLAIMED)
        if (count($arr) > 0) {
            /*             * **
             * prepare tag ids
             * 1.compare original tags and current tags
             *   if no difference, ignore them
             *   if has new tags, 
             *     if brand new, insert tag record
             *      else increase num_of_questions of this tag
             *  if remove old tags, 
             *     decrease num_of_questions of this tag
             * 
             * 
             */
            $question = Model_Question::get_one($id);
            $old_tags = explode(TNAME_SEPERATOR, substr($question['tnames'], 1, -1)); //remove first and last TNAME_SEPERATOR
            //$new_tnames = $arr['tnames'];
            $arr['tnames'] = array_unique($arr['tnames']); //remove duplicate
            $new_tags = $arr['tnames']; //it's an array already

            $new_difference = array_diff($new_tags, $old_tags);
            $old_difference = array_diff($old_tags, $new_tags);
            //new tags, increase num of questions of tag or insert new tag record
            if (count($new_difference) > 0) {
                //has new tags
                foreach ($new_difference as $tag) {
                    if ($tag_id = Model_Tag::exist($tag)) {
                        //$tids .= $tag_id . TNAME_SEPERATOR;
                        Model_Tag::increase_num_of_questions($tag_id);
                    } else {
                        //brand new tag will be inserted into tag table
                        $tag_arr = array('name' => $tag, 'num_of_questions' => 1);
                        $tag_id = Model_Tag::create($tag_arr);
                        //$tids .= $tag_id . TNAME_SEPERATOR;
                    }
                }
            }
            //old tags, decrease num of questions of tag
            if (count($old_difference) > 0) {
                //has new tags
                foreach ($new_difference as $tag) {
                    if ($tag_id = Model_Tag::exist($tag)) {
                        //must exist
                        Model_Tag::decrease_num_of_questions($tag_id);
                    }
                }
            }
            if (count($new_difference) > 0 || count($old_difference) > 0) {
                //means different from the original, update tag ids column
                foreach ($new_tags as $tag) {
                    if ($tag_id = Model_Tag::exist($tag)) {
                        //must exist
                        $tids .= $tag_id . TNAME_SEPERATOR;
                        $arr['tids'] = $tids;
                    }
                }
            }
            $arr['tnames'] = TNAME_SEPERATOR . implode(TNAME_SEPERATOR, $arr['tnames']) . TNAME_SEPERATOR; //array to string
            $arr['status'] = Model_Question::S_ACTIVE; //anytime updated, the status will be reset to S_ACTIVE, can be claimed
            if (Model_Question::update($id, $arr)) {
                Zx_Message::set_success_message('更新问题成功');
                return true;
            } else {
                Zx_Message::set_error_message(SYSTEM_ERROR_MESSAGE);
                return false;
            }
        } else {
            Zx_Message::set_error_message('问题信息不完整。');
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
                Zx_Message::set_success_message('success');
                return true;
            } else {
                Zx_Message::set_error_message('fail');
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
            Zx_Message::set_success_message('success');
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
    public static function deactivate($id) {
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