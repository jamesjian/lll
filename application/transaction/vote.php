<?php

namespace App\Transaction;

use \App\Model\Vote as Model_Vote;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \App\Model\Ad as Model_Ad;
use \App\Model\Question as Model_Question;
use \App\Model\Answer as Model_Answer;
use \Zx\Message\Message;
use \Zx\Model\Mysql;

class Vote {

    /**
     * uid, item_type, item_id, active_item are all valid in controller method
     * @param int $uid
     * @param int $item_type
     * @param int $item_id
     * @param record $item  is a redundant parameter to save time
     * @return boolean
     */
    public static function create($uid, $item_type, $item_id, $item) {
        $vote = Model_Vote::get_one($uid, $item_type, $item_id);
        if ($vote) {
            Message::set_error_message('repeate to vote');
            return false;
        } else {
            //new record in vote
            $arr = array('uid' => $uid, 'item_type' => $item_type, 'item_id' => $item_id);
            Model_Vote::create($arr);
            $user = Model_User::get_one($item['user_id']);
            //add score to user
            $arr = array('score' => $user['score'] + 1);
            Model_User::update($item['user_id'], $arr);
            //add number to item
            $arr = array('num_of_votes' => $item['vote'] + 1);
            switch ($item_type) {
                case 1:  //question
                    Model_Question::update($item_id, $arr);
                    break;
                case 2: //answer
                    Model_Answer::update($item_id, $arr);
                    break;
                /** ad is not voted currently
                  case 3:
                  Model_Ad::update($item_id, $arr);
                  break;
                 * 
                 */
            }
            return true;
        }
    }

    function backup_sql() {
        $sql = "SELECT * FROM vote";
        $r = Mysql::select_all($sql);
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