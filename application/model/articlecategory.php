<?php
namespace App\Model;

use \Zx\Model\Base\Articlecategory as Base_Articlecategory;
use \Zx\Model\Mysql;
class Articlecategory extends Base_Articlecategory{
    /**
     * get all cats order by category name
     */
    public static function get_cats()
    {
        return parent::get_all();
    }
}