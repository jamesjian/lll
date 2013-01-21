<?php

namespace App\Model;

defined('SYSTEM_PATH') or die('No direct script access.');

/**
 * most of region method will not from database
 */
use \App\Model\Base\Region as Base_Region;
use \Zx\Model\Mysql;

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
}