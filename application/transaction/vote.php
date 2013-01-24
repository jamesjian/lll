<?php

namespace App\Transaction;

use \App\Model\Vote as Model_Vote;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \Zx\Message\Message as Zx_Message;
use \Zx\Model\Mysql as Zx_Mysql;

class Vote {

    /**
     * vote is only made by loggedin user
     * no vote on ad
     * vote is different from claim, it's confirmed immediately
     * claim needs to be confirmed by admin
     * uid, item_type, item_id, active_item are all valid in controller method
     * 1, check status of item, it should be active
     * 2. check if repeat to vote
     * 3. create record in vote table
     * 4. add score to user
     * 5. add number to item  (num of votes)
     * 
     * 
     * @param int $item_type
     * @param int $item_id  for question and answer it's id1
     * @return boolean
     */
    public static function create($item_type, $item_id) {
            $uid = $_SESSION['user']['uid'];
        switch ($item_type) {
            case '1': //question:
                $item = Model_Question::get_one_by_id1($item_id);
                $can_be_voted_status = $item['status'] == Model_Question::S_ACTIVE ||
                        $item['status'] == Model_Question::S_CORRECT;
                $item_name = "问题";
                break;
            case '2': //answer
                $item = Model_Answer::get_one_by_id1($item_id);
                $item_name = "回答";
                $can_be_voted_status = $item['status'] == Model_Answer::S_ACTIVE ||
                        $item['status'] == Model_Answer::S_CORRECT;                
                break;
        }

        if (!$can_be_voted_status) {
            Zx_Message::set_error_message('感谢您的支持， 该' . $item_name . '已无效。');
            return false;
        } else {
            $vote = Model_Vote::get_one($uid, $item_type, $item_id);
            if ($vote) {
                Zx_Message::set_error_message('感谢您的支持，您只能对同一' . $item_name . '投一次票。');
                return false;
            } else {
                $arr = array('uid' => $uid,
                    'item_type' => $item_type,
                    'item_id' => $item_id,
                    'id1' => $id1,  //redundant field
                );
                if (Model_Vote::create($arr)) {
                    $user = Model_User::get_one($item['uid']);
                    //add score to user
                    $arr = array('score' => $user['score'] + SCORE_OF_VOTE);
                    Model_User::update($item['uid'], $arr);
                    //add number to item
                    $arr = array('num_of_votes' => $item['num_of_votes'] + 1);
                    switch ($item_type) {
                        case 1:  //question
                            Model_Question::update($item_id, $arr);
                            break;
                        case 2: //answer
                            Model_Answer::update($item_id, $arr);
                            break;
                    }
                    Zx_Message::set_success_message('您的投票已被记录， 感谢您的支持。');
                    return true;
                } else {
                    Zx_Message::set_error_message('对不起， 系统出错， 请稍后再试。');
                    return false;
                }
            }
        }
    }

    function backup_sql() {
        $sql = "SELECT * FROM vote";
        $r = Zx_Mysql::select_all($sql);
        if ($r) {
            $str = 'INSERT INTO tag VALUES ';
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