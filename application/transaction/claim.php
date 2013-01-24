<?php

namespace App\Transaction;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Claim as Model_Claim;
use \App\Model\Claimcategory as Model_Claimcategory;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;
use \App\Model\User as Model_User;
use \App\Model\Score as Model_Score;
use \Zx\Message\Message as Zx_Message;
use \Zx\Model\Mysql as Zx_Mysql;
use \App\Transaction\Swiftmail as Transaction_Swiftmail;

class Claim {

    /**
     * only S_ACTIVE can be claimed
     * when an item is claimed
     * 1. check item can be claimed (must be S_ACTIVE)
     * 2. create a claim in claim table (status is S_CREATED)
     * 3. change status of item to S_CLAIMED
     * 
     * when an item is S_CORRECT and then updated , status is set to S_ACTIVE, the user can claim it again,
     * so get claimant from table use "order by date_created DESC limit 0,1 
     * 
     * @param int $item_type
     * @param int $item_id  for question and answer it's id1, for ad it's id
     * @param array $user  from $_SESSION['user']
     * @param int $cat_id
     * @return boolean
     */
    public static function claim($item_type, $item_id, $cat_id) {
        \Zx\Test\Test::object_log('$item_type', $item_type, __FILE__, __LINE__, __CLASS__, __METHOD__);
        \Zx\Test\Test::object_log('$item_id', $item_id, __FILE__, __LINE__, __CLASS__, __METHOD__);
        \Zx\Test\Test::object_log('$cat_id', $cat_id, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (isset($_SESSION['user'])) {
            $uid = $_SESSION['user']['uid'];
            $uname = $_SESSION['user']['uname'];
        } else {
            $user = Model_User::get_default_claim_user();
            $uid = $user['id'];
            $uname = $user['uname'];
        }        
        switch ($item_type) {
            case '1': //question:
                $item = Model_Question::get_one_by_id1($item_id);  //it's id1
                $item_id = $item['id'];
                $item_name = "问题";
                $can_be_claimed = $item['status'] == Model_Question::S_ACTIVE;
                \Zx\Test\Test::object_log('$item', $item, __FILE__, __LINE__, __CLASS__, __METHOD__);
                \Zx\Test\Test::object_log('Model_Question::S_ACTIVE;', Model_Question::S_ACTIVE, __FILE__, __LINE__, __CLASS__, __METHOD__);
                $new_status = Model_Question::S_CLAIMED;
                break;
            case '2': //answer
                $item = Model_Answer::get_one_by_id1($item_id);  //it's id1
                $item_id = $item['id'];
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
                     \Zx\Test\Test::object_log('$can_be_claimed', 'false', __FILE__, __LINE__, __CLASS__, __METHOD__);

            Zx_Message::set_error_message('感谢您的支持， 该' . $item_name . '无效或已被他人举报。');
        } else {
             \Zx\Test\Test::object_log('$can_be_claimed', 'true', __FILE__, __LINE__, __CLASS__, __METHOD__);
            $arr = array('item_type' => $item_type,
                'item_id' => $item_id,   //all id now
                'claimant_id' => $uid,
                'cat_id' => $cat_id,
                'status' => Model_Claim::S_CREATED, //new claim
            );
             \Zx\Test\Test::object_log('claimed array', '$arr', __FILE__, __LINE__, __CLASS__, __METHOD__);
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
                //$arr = array('uid' => $claimant['id'], 'operation' => 'claimant', 'previous_score' => $score,
                //    'difference' => $score, 'current_score' => $score);
                //Model_Score::create($arr);                
                Zx_Message::set_success_message('您的举报已经转发给网站管理员， 我们会尽快处理');
                return true;
            } else {
                Zx_Message::set_error_message('对不起， 系统出错， 请稍后再试。');
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
        $item_id = $claim['item_id'];
        $original_status = $claim['status'];
        \Zx\Test\Test::object_log('new_status', $status, __FILE__, __LINE__, __CLASS__, __METHOD__);      
        \Zx\Test\Test::object_log('$original_status', $original_status, __FILE__, __LINE__, __CLASS__, __METHOD__);      
        if ($original_status == $status) {
            //nothing to do
        } else {
        if ($original_status == Model_Claim::S_CREATED) {
            if ($status == Model_Claim::S_CORRECT_CLAIM) {
                //correct claim
                //update claim table
                $arr = array('status' => Model_Claim::S_CORRECT_CLAIM);
                 \Zx\Test\Test::object_log('Model_Claim', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);        
                Model_Claim::update($id, $arr);
                switch ($claim['item_type']) {
                    case '1':
                        $item = Model_Question::get_one($item_id);
                        $arr = array('status' => Model_Question::S_DISABLED);
                        \Zx\Test\Test::object_log('Model_Question', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);  
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
                        Model_Answer::reset_ad_id($item_id);
                        break;
                }
                //update claimant and defendant scores
                $claimant = Model_User::get_one($claim['claimant_id']);
                $defendant = Model_User::get_one($item['uid']);
                $score = Model_Claimcategory::get_score_by_cat_id($claim['cat_id']);
                $arr = array('score' => $claimant['score'] + $score);
                \Zx\Test\Test::object_log('$claimant', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);  
                Model_User::update($claimant['id'], $arr);
                $arr = array('invalid_score' => $defendant['invalid_score'] + $score); //don't change number_of_questions/answers/ads
                \Zx\Test\Test::object_log('$defendant', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);  
                Model_User::update($defendant['id'], $arr);
                // score table record transactions
                //$arr = array('uid' => $claimant['id'], 'operation' => 'claimant', 'previous_score' => $score,
                //    'difference' => $score, 'current_score' => $score);
                //Model_Score::create($arr);
                
            } else {
                //$status = Model_Claim::S_WRONG_CLAIM
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
            if ($original_status == Model_Claim::S_CORRECT_CLAIM) {
                //new status =S_WRONG_CLAIM
                //compensate defendant, decrease claimant
                $arr = array('status' => Model_Claim::S_WRONG_CLAIM);
                Model_Claim::update($id, $arr);
                switch ($claim['item_type']) {
                    case '1':
                        $item = Model_Question::get_one($item_id);
                        $arr = array('status' => Model_Question::S_CORRECT);
                        Model_Question::update($item_id, $arr);
                        break;
                    case '2':
                        $item = Model_Answer::get_one($item_id);
                        $arr = array('status' => Model_Answer::S_CORRECT);
                        Model_Answer::update($item_id, $arr);
                        break;
                    case '3':
                        $item = Model_Ad::get_one($item_id);
                        $arr = array('status' => Model_Ad::S_CORRECT);
                        Model_Ad::update($item_id, $arr);
                        //ad id in answer cannot be restored
                        break;
                }
                //update claimant and defendant scores
                $claimant = Model_User::get_one($claim['claimant_id']);
                $defendant = Model_User::get_one($item['uid']);
                $score = Model_Claimcategory::get_score_by_cat_id($claim['cat_id']);
                $arr = array('score' => $claimant['score'] - $score);
                Model_User::update($claimant['id'], $arr);
                $arr = array('invalid_score' => $defendant['invalid_score'] - $score);
                Model_User::update($defendant['id'], $arr);                  
            } else {
                //$original_status == Model_Claim::S_WRONG_CLAIM
                //new status =S_CORRECT_CLAIM
                //same as $original_status=Model_Claim::S_CREATED
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
                        Model_Answer::reset_ad_id($item_id);
                        break;
                }
                //update claimant and defendant scores
                $claimant = Model_User::get_one($claim['claimant_id']);
                $defendant = Model_User::get_one($item['uid']);
                $score = Model_Claimcategory::get_score_by_cat_id($claim['cat_id']);
                $arr = array('score' => $claimant['score'] + $score);
                Model_User::update($claimant['id'], $arr);
                $arr = array('invalid_score' => $defendant['invalid_score'] + $score);
                Model_User::update($defendant['id'], $arr);                
            }
        }
        }
        return true; //always return true currently
    }

    /**
     * usually don't delete any claim
     * if delete it, 
     * set status of item to S_ACTIVE
     * @param int $id  claim id
     * @return boolean
     */
    public static function delete_claim($id) {
        $claim = Model_Claim::get_one($id);
        if ($claim && Model_Claim::delete($id)) {
            switch ($claim['item_type']) {
                case '1':
                    $arr = array('status'=>Model_Question::S_ACTIVE);
                    Model_Question::update($claim['item_id'], $arr);
                    break;
                case '2':
                    $arr = array('status'=>Model_Answer::S_ACTIVE);
                    Model_Answer::update($claim['item_id'], $arr);                    
                    break;
                case '3':
                    $arr = array('status'=>Model_Ad::S_ACTIVE);
                    Model_Ad::update($claim['item_id'], $arr);                    
                    break;
            }
            Zx_Message::set_success_message('success');
            return true;
        } else {
            Zx_Message::set_error_message('fail');
            return false;
        }
    }

    /**
     * for cron job
     * backup claim table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM claim";
        $r = Zx_Mysql::select_all($sql);
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