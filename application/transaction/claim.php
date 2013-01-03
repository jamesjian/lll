<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Claim as Model_Claim;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;
use \Zx\Message\Message;
use \Zx\Model\Mysql;
use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Claim {

    /**
     * when an item is claimed
     * 1. check item status is "not claimed"
     * 2. create a claim in claim table
     * 3. change status of item to "claimed"
     * 
     * when an item is updated, the user can claim it again,
     * so get claimant from table use "order by date_created DESC limit 0,1 
     * 
     * @param int $item_type
     * @param int $item_id
     * @param array $user  from $_SESSION['user']
     * @param int $cat_id
     * @return boolean
     */
    public static function create_claim($item_type, $item_id, $user, $cat_id) {
        switch ($item_type) {
            case '1': //question:
                $item = Model_Question::get_one($item_id);
                $item_name = "问题";
                break;
            case '2': //answer
                $item = Model_Answer::get_one($item_id);
                $item_name = "回答";
                break;
            case '3': //ad:
                $item = Model_Ad::get_one($item_id);
                $item_name = "广告";
                break;
        }
        if ($item['status'] == 'claimed') {
            Message::set_error_message('感谢您的支持， 该' . $item_name . '已被他人举报。');
        } else {
            $arr = array('item_type' => $item_type,
                'item_id' => $item_id,
                'claimant_id' => $user['id'],
                'cat_id' => $cat_id,
                'status' => 1,
            );
            if (Model_Claim::create($arr)) {
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
     * 2. if the item is bad, change status to "valid/confirmed" 
     *    if the item is good, change status to "invalid/cancelled"
     * 3. if the claim is confirmed, 
     *    add score to claimant
     *    subtract score from defendant
     *    change status of item to "disabled"
     * 
     * confirmed=>cancelled, cancelled=>confirmed
     * @param int $id  claim id
     * @param int $status  'confirmed','cancelled'
     * 
     * @return boolean
     */
    public static function update_claim($id, $new_status) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $claim = Model_Claim::get_one($id);
        $original_status = $claim['status'];
        switch ($claim['item_type']) {
            case '1':
                $item = Model_Question::get_one($claim['item_id']);
                break;
            case '2':
                $item = Model_Answer::get_one($claim['item_id']);
                break;
            case '3':
                $item = Model_Ad::get_one($claim['item_id']);
                break;
        }
        $item_id = $item['id'];
        $claimant = Model_User::get_one($claim['uid']);
        $defendant = Model_User::get_one($item['uid']);
        if ($claim <> $status) {
            //a. claim status to confirmed
            $arr = array('status' => $status);
            Model_Claim::update($id, $arr);



            switch ($original_status) {
                case Model_Claim::STATUS_NOT_CONFIRMED:
                    //not confirmed to confirmed or cancelled
                    switch ($new_status) {
                        case Model_Claim::STATUS_CONFIRMED:
                            /*                             * 1. not confirmed to confirmed
                              b. item status to disabled
                              c. user score decreased
                             */
                            switch ($claim['item_type']) {
                                case '1':
                                    $arr = array('status' => Model_Question::STATUS_DISABLED);
                                    Model_Question::update($item_id, $arr);
                                    break;
                                case '2':
                                    $arr = array('status' => Model_Answer::STATUS_DISABLED);
                                    Model_Answer::update($item_id, $arr);
                                    break;
                                case '3':
                                    $arr = array('status' => Model_Ad::STATUS_DISABLED);
                                    Model_Ad::update($item_id, $arr);
                                    break;
                            }
                            $arr = array('score' => $claimant['score'] + $score);
                            Model_User::update($claimant['id'], $arr);
                            $arr = array('score' => $defendant['score'] - $score);
                            Model_User::update($defendant['id'], $arr);
                            // score table record transactions
                            $arr = array('uid' => $claimant['id'], 'operation' => 'claimant', 'previous_score' => $score,
                                'difference' => $score, 'current_score' => $score
                            );
                            Model_Score::create($arr);
                            break;
                        case Model_Claim::STATUS_CANCELLED:
                            /*
                             * 2. not confirmed to cancelled
                             * b. item status to valid
                             */
                            break;
                    }
                    break;
                case Model_Claim::STATUS_CONFIRMED:
                    /*
                     * 3. confirmed to cancelled
                      b. item status to valid
                      c. user score increased
                     * 
                     */
                    break;
                case Model_Claim::STATUS_CANCELLED:
                    /*
                     * 4. cancelled to confirmed
                     * b. item status to disabled
                     * c. user score decreased
                     */
                    break;
            }
        } else {
            //nothing to change
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