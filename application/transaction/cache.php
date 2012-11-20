<?php
namespace App\Transaction;
defined('SYSTEM_PATH') or die('No direct script access.');
/**
 * cache, can use file or database 
 *CREATE TABLE IF NOT EXISTS `cache` (
  `id` varchar(255) NOT NULL COMMENT 'key name',
  `data` text COMMENT 'serialized value',
  `created` int(11) DEFAULT NULL COMMENT 'unix timestamp',
  `expire` int(11) DEFAULT NULL COMMENT 'unix timestamp, if 0, never expires',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB
 */
/**
 * pattern: 
 * 
 * $cache_id = $state_name . '_list';
 * $list = App_Cache::get($cache_id);
    if ( $list == NO_CACHE_DATA) {
        $list = '';
        foreach ($postcodes as $postcode) {
            $list .= "<option value='{$postcode->id}'>{$postcode->suburb}({$postcode->postcode})</option>";
        }
         //App_Test::objectLog('$postcode_option_list',$postcode_option_list, __FILE__, __LINE__, __CLASS__, __METHOD__);
        App_Cache::set($cache_id, $list);  //save to cache table
    }
 * 
 */
/**
 * don't use false to check if the value exists, because sometimes the value
 * exists and it is "false"
 */

class Cache
{
    
    /**
     * get data from cache table
     * @param <string> $id
     * @return <mixed>  unserialized data type, maybe array, string or object
     * may be NO_CACHE_DATA when the data doesn't exist
     */
    public static function get($id)
    {
        $data = Model_Cache::get_data($id);
        return $data;
    }
    /**
     * set(insert or update) data into cache table
     * @param <string> $id     *
     * @param <mixed> $data
     * @param <integer> $expire, 0 means never expired
     * @return <boolean>  if set successfully, return true
     */

    public static function set($id, $data, $expire=0)
    {
        Model_Cache::set_data($id, $data, $expire);
        return true;
    }
    /**
     * remove one cache entry
     * @param <string> $id
     * @return <boolean>
     */
    public static function delete($id)
    {
        Model_Cache::delete_cache($id);
        return true;
    }
    /**
     * remove all cache entries
     * sometimes need to reset all caches
     */
    public static function delete_all()
    {
        Model_Cache::delete_all_cache();
        return true;
    }

    /**
     * always use this after get($cache_id)
     * $data = get(cache_id);
     * if exist($data) use data
     * else create new data
     * @param <mixed> $data
     * @return <boolean>
     */
    public static function exist($data)
    {
        if ($data != NO_CACHE_DATA) return true;
        else return false;
    }
   
}
