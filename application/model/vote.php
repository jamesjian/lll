<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Vote as Base_Vote;
use \Zx\Model\Mysql;

class Vote extends Base_Vote {

    /**
     * 
     * @param int $user_id
     * @param int $item_type //1. question, 2. answer, 3. ad
     * @param int $item_id
     * @return boolean 
     */
    public static function is_voted($user_id, $item_type, $item_id) {
        if (parent::get_one($user_id, $item_type, $item_id)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * item type=1
     * @param int $user_id
     * @param int $question_id
     * @return boolean
     */
    public static function is_question_voted($user_id, $question_id) 
    {
        return self::is_voted($user_id, 1, $question_id);
    }
    /**
     * item type=2
     * @param int $user_id
     * @param int $answer_id
     * @return boolean
     */
    public static function is_answer_voted($user_id, $answer_id) 
    {
        return self::is_voted($user_id, 3, $answer_id);
    }
    /**
     * item type=3
     * @param int $user_id
     * @param int $ad_id
     * @return boolean
     */
    public static function is_ad_voted($user_id, $ad_id) 
    {
        return self::is_voted($user_id, 3, $ad_id);
    }

    
}