<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

use \App\Model\Base\Abcategory as Base_Claimcategory;
use \Zx\Model\Mysql;

/**
 * currently store data in array rather than database to improve performance
 */
class Claimcategory extends Base_Claimcategory {

    /**
     * get active cats order by category name
     */
    public static function get_cats() {
        return array('1' => '造谣诽谤', '2' => '种族或宗教歧视', '3' => '色情',
            '4' => '暴力， 虐待（人或动物）',
            '5' => '违禁物品（毒品， 武器， 人体器官等）', '6' => '误导欺诈',
            '7' => '与澳洲无关或无实质内容');
    }

    public static function get_scores() {
        return array('1' => 10, '2' => 10, '3' => 10, '4' => 10, '5' => 10, '6' => 10, '7' => 1);
    }

    /**
     * 
     * @param int $cat_id
     * @return boolean or integer, false if not found
     */
    public static function get_score_by_cat_id($cat_id=0) {
        $scores = self::get_scores();
        if (array_key_exists($cat_id, $scores)) {
            return $scores[$cat_id];
        } return false;
    }
}