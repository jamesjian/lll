<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Claim as Model_Claim;
use \App\Model\Claimcategory as Model_Claimcategory;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;
use \App\Model\Score as Model_Score;
use \Zx\Message\Message;
use \Zx\Model\Mysql;
use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Claim {

    /**
     * only S_ACTIVE can be claimed
     * when an item is claimed
     * 1. check item can be claimed (must be S_ACTIVE)
     * 2. create a claim in claim table (status is S_CREATED)
     * 3. change status of item to S_CLAIMED
     * 
     * when an item is updated, status is set to S_ACTIVE, the user can claim it again,
     * so get claimant from table use "order by date_created DESC limit 0,1 
     * 
     * @param int $item_type
     * @param int $item_id
     * @param array $user  from $_SESSION['user']
     * @param int $cat_id
     * @return boolean
     */
    public static function claim($item_type, $item_id, $cat_id, $user) {
        switch ($item_type) {
            case '1': //question:
                $item = Model_Question::get_one($item_id);
                $item_name = "问题";
                $can_be_claimed = $item['status'] == Model_Question::S_ACTIVE;
                $new_status = Model_Question::S_CLAIMED;
                break;
            case '2': //answer
                $item = Model_Answer::get_one($item_id);
                $item_name = "回答";
                $can_be_claimed = $item['status'] == Model_Answer::S_ACTIVE;
                $new_status = Model_Answer::S_CLAIMED;
                break;
            case '3': //ad:
                $item = Model_Ad::get_one($item_id);
                $item_name = "广告";
                $can_be_claimed = $item['status'] == Model_Ad::S_ACTIVE;
                $new_status = Model_Ad::S_CLAIMED;
                break;
        }
        if (!$can_be_claimed) {
            Message::set_error_message('感谢您的支持， 该' . $item_name . '无效或已被他人举报。');
        } else {
            $arr = array('item_type' => $item_type,
                'item_id' => $item_id,
                'claimant_id' => $user['id'],
                'cat_id' => $cat_id,
                'status' => Model_Claim::S_CREATED, //new claim
            );
            if (Model_Claim::create($arr)) {
                $arr = array('status' => $new_status);
                switch ($item_type) {
                    case '1':
                        Model_Question::update($item_id, $arr);
                        break;
                    case '2':
                        Model_Answer::update($item_id, $arr);
                        break;
                    case '3':
                        Model_Ad::update($item_id, $arr);
                        break;
                }
                //to do: score table record transactions
                $arr = array('uid' => $claimant['id'], 'operation' => 'claimant', 'previous_score' => $score,
                    'difference' => $score, 'current_score' => $score);
                Model_Score::create($arr);                
                Message::set_success_message('您的举报已经转发给网站管理员， 我们会尽快处理');
                return true;
            } else {
                Message::set_error_message('对不起， 系统出错， 请稍后再试。');
                return false;
            }
        }
    }

    /**
     * only by admin to update the status of a claim
     * when update the status of a claim
     * 1. check current status of the claim, if "not confirmed", go ahead, 
     * 2. if the item is bad, 
     *        change status of item to S_DISABLED
     *        change status of claim to S_CORRECT_CLAIM
      add score to claimant
     *          subtract score from defendant*                     
     *    if the item is good, 
     *        change status of item to S_CORRECT
     *        change status of claim to S_WRONG_CLAIM

     * @param int $id  claim id
     * @param int $status it's from radio field of a form,  1: correct claim, 0: wrong claim
     * 
     * @return boolean
     */
    public static function update_claim($id, $status) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $claim = Model_Claim::get_one($id);
        $original_status = $claim['status'];
        if ($original_status == Model_Claim::S_CREATED) {
            $item_id = $claim['item_id'];

            if ($status == 1) {
                //correct claim
                $arr = array('status' => Model_Claim::S_CORRECT_CLAIM);
                Model_Claim::update($id, $arr);
                switch ($claim['item_type']) {
                    case '1':
                        $item = Model_Question::get_one($item_id);
                        $arr = array('status' => Model_Question::S_DISABLED);
                        Model_Question::update($item_id, $arr);
                        break;
                    case '2':
                        $item = Model_Answer::get_one($item_id);
                        $arr = array('status' => Model_Answer::S_DISABLED);
                        Model_Answer::update($item_id, $arr);
                        break;
                    case '3':
                        $item = Model_Ad::get_one($item_id);
                        $arr = array('status' => Model_Ad::S_DISABLED);
                        Model_Ad::update($item_id, $arr);
                        break;
                }
                $claimant = Model_User::get_one($claim['uid']);
                $defendant = Model_User::get_one($item['uid']);
                $score = Model_Claimcategory::get_score_by_cat_id($claim['cat_id']);
                $arr = array('score' => $claimant['score'] + $score);
                Model_User::update($claimant['id'], $arr);
                $arr = array('score' => $defendant['score'] - $score);
                Model_User::update($defendant['id'], $arr);
                // score table record transactions
                $arr = array('uid' => $claimant['id'], 'operation' => 'claimant', 'previous_score' => $score,
                    'difference' => $score, 'current_score' => $score);
                Model_Score::create($arr);
            } else {
                $arr = array('status' => Model_Claim::S_WRONG_CLAIM);
                Model_Claim::update($id, $arr);
                switch ($claim['item_type']) {
                    case '1':
                        $arr = array('status' => Model_Question::S_CORRECT);
                        Model_Question::update($item_id, $arr);
                        break;
                    case '2':
                        $arr = array('status' => Model_Answer::S_CORRECT);
                        Model_Answer::update($item_id, $arr);
                        break;
                    case '3':
                        $arr = array('status' => Model_Ad::S_CORRECT);
                        Model_Ad::update($item_id, $arr);
                        break;
                }
            }
        } else {
            $new_status = ($status == 1) ? Model_Claim::S_CORRECT_CLAIM : Model_Claim::S_WRONG_CLAIM;
            if ($original_status <> $new_status) {
                Message::set_error_message('success');
            } else {
                //no change, nothing to do
            }
        }
    }

    public static function delete_claim($id) {
        if (Model_Claim::delete($id)) {
            Message::set_success_message('success');
            return true;
        } else {
            Message::set_error_message('fail');
            return false;
        }
    }

    /**
     * for cron job
     * backup claim table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM claim";
        $r = Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO claim VALUES ';
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