<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Score as Base_Score;
use \Zx\Model\Mysql as Zx_Mysql;

class Score extends Base_Score {
 public static function get_records_by_uid_and_page_num($uid, $where = '1', $page_num = 1, $order_by = 'date_created', $direction = 'ASC') {
        $where = ' (uid=' . $uid . ')  AND (' . $where . ')';
        $offset = ($page_num - 1) * NUM_OF_ITEMS_IN_ONE_PAGE;
        return parent::get_all($where, $offset, NUM_OF_ITEMS_IN_ONE_PAGE, $order_by, $direction);
    }

    public static function get_num_of_records_by_uid($uid, $where = '1') {
        $where = ' (uid=' . $uid . ')  AND (' . $where . ')';
        return parent::get_num($where);
    }
    /**
     * 
     * @param int $uid
     * @param int $item_type
     * @param int $item_id
     * @param int $claim_id  if related to claim
     * @param int $type  type of operation use const of base class
     * @param string $description
     * @param int $old_score>=0
     * @param int $difference     can be negative
     * @param int $new_score>=0
     * @param int $status
     */
    public static function new_record($uid=0, $item_type=1, $item_id=0, 
            $claim_id=0, $type=parent::T_CREATE_QUESTION, $description='', 
            $old_score=0, $difference=0, $new_score=0, $status=parent::S_ACTIVE)
    {
        $arr = array('uid'=>$uid, 'item_type'=>$item_type, 'item_id'=>$item_id,
            'claim_id'=>$claim_id, 'type'=>$type,'description'=>$description,
            'old_score'=>$old_score, 'difference'=>$difference, 'new_score'=>$new_score,
            'status'=>$status,
        );
        return parent::create($arr);
    }
    /**
     * 
     * @param int $uid
     * @param int $qid  question id
     * @param int $old_score
     * @param int $difference
     */
    public static function create_question($uid, $qid, $old_score, $difference)
    {
        $item_type = 1;//question 
        $claim_id = 0;
        $type = parent::T_CREATE_QUESTION;
        $description = '新问题';
        $new_score = $old_score + $difference;
        self::new_record($uid, $item_type, $qid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }
    /**
     * 
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function create_answer($uid, $aid, $old_score, $difference)
    {
        $item_type = 2;//answer 
        $claim_id = 0;
        $type = parent::T_CREATE_ANSWER;
        $description = '新回答';
        $new_score = $old_score + $difference;
        self::new_record($uid, $item_type, $aid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }    
    /**
     * new ad will decrease score
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function create_ad($uid, $ad_id, $old_score, $difference)
    {
        $item_type = 3;//ad 
        $claim_id = 0;
        $type = parent::T_CREATE_AD;
        $description = '新广告';
        $new_score = $old_score - $difference;
        self::new_record($uid, $item_type, $ad_id, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }    
    /**
     * claimant will get score
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function correct_claim_question($uid, $qid, $claim_id, $old_score, $difference)
    {
        $item_type = 1;//question 
        $type = parent::T_CORRECT_CLAIM_QUESTION;
        $description = '举报问题';
        $new_score = $old_score + $difference;
        self::new_record($uid, $item_type, $qid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }     
    /**
     * claimant will get score
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function correct_claim_answer($uid, $aid, $claim_id, $old_score, $difference)
    {
        $item_type = 2;//question 
        $type = parent::T_CORRECT_CLAIM_ANSWER;
        $description = '举报回答';
        $new_score = $old_score + $difference;
        self::new_record($uid, $item_type, $aid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }   
    /**
     * claimant will get score
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function correct_claim_ad($uid, $ad_id, $claim_id, $old_score, $difference)
    {
        $item_type = 3;//question 
        $type = parent::T_CORRECT_CLAIM_AD;
        $description = '举报广告';
        $new_score = $old_score + $difference;
        self::new_record($uid, $item_type, $ad_id, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }      
    /**
     * defendant will lost score
     * @param int $uid
     * @param int $qid  question id
     * @param int $old_score
     * @param int $difference
     */
    public static function disable_question($uid, $qid, $claim_id, $old_score, $difference)
    {
        $item_type = 1;//question 
        $type = parent::T_DISABLE_QUESTION;
        $description = '问题违规';
        $new_score = $old_score - $difference;
        self::new_record($uid, $item_type, $qid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }
    /**
     * defendant will lost score
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function disable_answer($uid, $aid, $claim_id, $old_score, $difference)
    {
        $item_type = 2;//answer 
        $claim_id = 0;
        $type = parent::T_DISABLE_ANSWER;
        $description = '回答违规';
        $new_score = $old_score - $difference;
        self::new_record($uid, $item_type, $aid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }    
    /**
     * new ad will decrease score
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function disable_ad($uid, $ad_id, $claim_id, $old_score, $difference)
    {
        $item_type = 3;//ad 
        $type = parent::T_DISABLE_AD;
        $description = '广告违规';
        $new_score = $old_score - $difference;
        self::new_record($uid, $item_type, $ad_id, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }     
/**
     * 
     * @param int $uid
     * @param int $qid  question id
     * @param int $old_score
     * @param int $difference
     */
    public static function delete_question($uid, $qid, $old_score, $difference)
    {
        $item_type = 1;//question 
        $claim_id = 0;
        $type = parent::T_DELETE_QUESTION;
        $description = '删除问题';
        $new_score = $old_score - $difference;
        self::new_record($uid, $item_type, $qid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }
    /**
     * 
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function delete_answer($uid, $aid, $old_score, $difference)
    {
        $item_type = 2;//answer 
        $claim_id = 0;
        $type = parent::T_DELETE_ANSWER;
        $description = '删除回答';
        $new_score = $old_score - $difference;
        self::new_record($uid, $item_type, $aid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }        
/**
     * 
     * @param int $uid
     * @param int $qid  question id
     * @param int $old_score
     * @param int $difference
     */
    public static function vote_question($uid, $qid, $old_score, $difference)
    {
        $item_type = 1;//question 
        $claim_id = 0;
        $type = parent::T_VOTE_QUESTION;
        $description = '问题受关注';
        $new_score = $old_score + $difference;
        self::new_record($uid, $item_type, $qid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }
    /**
     * 
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function vote_answer($uid, $aid, $old_score, $difference)
    {
        $item_type = 2;//answer 
        $claim_id = 0;
        $type = parent::T_VOTE_ANSWER;
        $description = '回答受关注';
        $new_score = $old_score + $difference;
        self::new_record($uid, $item_type, $aid, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }       
    /**
     * extend ad will decrease score
     * @param int $uid
     * @param int $aid answer id
     * @param int $old_score
     * @param int $difference
     */
    public static function extend_ad($uid, $ad_id, $old_score, $difference)
    {
        $item_type = 3;//ad 
        $claim_id = 0;
        $type = parent::T_EXTEND_AD;
        $description = '广告延期';
        $new_score = $old_score - $difference;
        self::new_record($uid, $item_type, $ad_id, $claim_id, $type, 
                $description, $old_score, $difference, $new_score);
    }      
}