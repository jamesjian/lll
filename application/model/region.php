<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

/**
 * most of region method will not from database
 */
use \App\Model\Base\Region as Base_Region;
use \App\Model\Question as Model_Question;
use \App\Model\Ad as Model_Ad;
use \Zx\Model\Mysql as Zx_Mysql;

class Region extends Base_Region{

    public static function get_states_abbr() {
        return array('ACT', 'NSW', 'VIC', 'QLD', 'NT', 'TAS', 'WA', 'SA');
    }

    public static function get_states_full() {
        return array('ACT' => 'Australia Capital Territory',
            'NSW' => 'New South Walse',
            'VIC' => 'Victoria',
            'QLD' => 'Queensland',
            'NT' => 'Northern Territory',
            'TAS' => 'Tasmania',
            'WA' => 'Western Australia',
            'SA' => 'South Australia');
    }

    public static function get_au_states_abbr() {
        return array('AU'=>'全澳', 'ACT' => 'ACT', 'NSW' => 'NSW','VIC' =>  'VIC', 'QLD' => 'QLD', 
           'NT' => 'NT', 'TAS' => 'TAS',  'WA' => 'WA','SA' => 'SA');
    }

    public static function get_au_states_full() {
        return array('AU' => 'Australia',
            'ACT' => 'Australia Capital Territory',
            'NSW' => 'New South Walse',
            'VIC' => 'Victoria',
            'QLD' => 'Queensland', 'NT' => 'Northern Territory',
            'TAS' => 'Tasmania',
            'WA' => 'Western Australia', 'SA' => 'South Australia');
    }
    /** 
     * from database
     * @param type $where
     * @param type $order_by
     * @param type $direction
     */
    public static function get_states($where, $order_by, $direction)
    {
        return parent::get_all($where, 0, 100, 'date_created', 'DESC');
    }
    /** 
     * calculate num_of_questions and num_of_ads
     */
    public static function calculate()
    {
        //num_of_questions
        $q = "SELECT region, COUNT(id) AS num_of_questions FROM " . Model_Question::$table . " GROUP BY region";
        $result = Zx_Mysql::select_all($q);
        if ($result){
            foreach ($result as $row) {
                $region = $row['region']; $num_of_questions = $row['num_of_questions'];
                $q = "UPDATE " . parent::$table . " SET num_of_questions=$num_of_questions WHERE state='$region'"; 
                Zx_Mysql::exec($q);
            }
        }
        //num_of_ads
        $q = "SELECT region, COUNT(id) AS num_of_ads FROM " . Model_Ad::$table . " GROUP BY region";
        $result = Zx_Mysql::select_all($q);
        if ($result){
            foreach ($result as $row) {
                $region = $row['region']; $num_of_ads = $row['num_of_ads'];
                $q = "UPDATE " . parent::$table . " SET num_of_ads=$num_of_ads WHERE state='$region'"; 
                Zx_Mysql::exec($q);
            }
        }        
    }
}