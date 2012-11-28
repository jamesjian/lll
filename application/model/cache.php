<?php
namespace App\Model;
defined('SYSTEM_PATH') or die('No direct script access.');
use \App\Model\Base\Cache as Base_Cache;
/**
 * Description of cache
CREATE TABLE IF NOT EXISTS `cache` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` text COMMENT 'serialized value',
  `date_created1` int(11) DEFAULT NULL COMMENT 'unix timestamp',
  `date_created2` datetime NOT NULL,
  `expire` int(11) DEFAULT NULL COMMENT 'unix timestamp, if 0, never expires',
  PRIMARY KEY (`cache_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 * Note: it's different from the other tables, the id is a string, not an integer
 * time use unix timestamp(integer), not datetime
 *
 */
//in case 'empty' or 'false' is the value of the cache, use NO_CACHE_DATA as the value
//when there is no value for a particular cache id

class Cache extends Base_Cache
{
   /**
     * @param <string> $cache_id
     * @return <mixed> if data exists, return unserialized data
     * else return NO_CACHE_DATA
     */
    public static function get_data($cache_name)
    {
        $cache = parent::get_record($cache_name);
        if ($cache != NO_CACHE_DATA) {
            return $cache->cache_value;
        } else {
            return NO_CACHE_DATA;
        }
    }
 /**
     * @param <string> $cache_id
     * @param <mixed> $data
     * @param <integer> $expire  unix timestamp, if it's 0, means never expire
     * @return <boolean>
     */
    public static function set_data($cache_name, $cache_value, $expire=0)
    {
        //App_Test::objectLog('$data',$data, __FILE__, __LINE__, __CLASS__, __METHOD__);
        if (!empty($cache_name)) {
            if (parent::exist_cache($cache_name)) {
                return parent::update_a_record($cache_name, $cache_value, $expire);
            } else {
                return parent::create_a_record($cache_name, $cache_value, $expire);
            }
        } else return false;
    }    
}
