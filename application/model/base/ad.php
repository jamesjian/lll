<?php

namespace App\Model\Base;

defined('SYSTEM_PATH') or die('No direct script access.');

use \Zx\Model\Mysql as Zx_Model;

/*
  id=0 is a dummy ad
  active ads means not disabled by admin
 * including completely correct, claimed or acitve (not claimed)
 * 
 * ad use id only (no id1) because not necessary.
 * 
 * when creat an ad, score will be decreased by SCORE_OF_CREATE_AD
 * 
 * when extend date, score will be decreased by SCORE_OF_CREATE_AD
 * 
 * when updating an ad, date_start and date_end will not be changed
 * 
  update ad set id1=concat(convert(2*id, char(11)), md5(id))
  CREATE TABLE ad (
  id  MEDIUMINT(8) unsigned  AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL DEFAULT '',
  uid  MEDIUMINT(7) unsigned  not null default 0,
  uname varchar(255) not null default '',  #user name is fixed
  tids varchar(255) NOT NULL DEFAULT '',
  tnames varchar(255) not null default '', #tag names are fixed
  content text,
  score   MEDIUMINT(6) unsigned not null default 0, #assigned by user according to num of ads
  num_of_views unsigned MEDIUMINT(8) not null default 0,
  status unsigned tinyint(1) not null default 1,
  date_created datetime,
  date_start datetime,
  date_end datetime
  ) engine=innodb default charset=utf8
 alter table  ts8wl_ad add index uid (uid);
 alter table  ts8wl_ad add index score (score);

 */

class Ad {

    public static $fields = array('id', 'title', 'uid', 'uname',
        'tids', 'tnames', 'content', 'score', 'num_of_views',
        'status', 'date_created', 'date_start', 'date_end');
    public static $table = TABLE_AD;

    /*     * for status
     * 1. when created or updated, it's S_ACTIVE, user consume score, it can be updated, claimed
     * 2. if  an ad not claimed, it can be deleted(not purge)  -> S_DELETED
     *    if claimed, have to wait for admin to check it
     * 3. only S_ACTIVE and S_CORRECT(will change to S_ACTIVE) can be updated by user
     *    when somebody claim it, it's S_CLAIMED, it cannot be updated, deleted by user
     *    when somebody claim it and it's checked by admin and not wrong, it's S_CORRECT, 
     *     can be updated (status will change to S_ACTIVE), 
     *     but cannot be claimed
     *    when somebody claim it and it's checked by admin and it's really bad, it's S_DISABLED,  
     *       cannot be claimed,  updated and deleted
     * 4.  S_ACTIVE->S_CLAIMED->S_CORRECT->            (if updated) S_ACTIVE
     *    (created)       (claimed)       completely correct    
     *     S_ACTIVE->S_CLAIMED->S_DISABLED  
     *    (created)       (claimed)       completely wrong   
     * 5. only purged by admin
     * 
     * S_DISABLED can only be changed to S_DELETED by front user, but can be changed to other status by admin when mistake happened
     * in the front end, only S_DISABLED and S_DELETED will not display, others will display
     * 
     */

    const S_DISABLED = 0; //if this ad is wrong and disabled by admin
    const S_ACTIVE = 1;  //if this ad is active and can be claimed
    const S_CORRECT = 2;   //if this ad completely correct, cannot be claimed
    const S_CLAIMED = 3; //when it's claimed by user
    const S_DELETED = 4; //when it's deleted by user, num of ads will be decreased, 
    // keep record, can be purged by admin
    const S_INACTIVE = 5;  //if this ad is inactive by user

    // (don't want it to be displayed but not deleted, the ad_id in answers will be reset to 0)

    public static function get_one($id) {
        return Zx_Model::get_one(self::$table, $id);
    }

    public static function get_one_by_where($where) {
        return Zx_Model::get_one_by_where(self::$table, $where);
    }

    public static function get_all($where = '1', $offset = 0, $row_count = MAXIMUM_ROWS, $order_by = 'date_created', $direction = 'DESC') {
        return Zx_Model::get_all(self::$table, $where, $offset, $row_count, $order_by, $direction);
    }

    public static function get_num($where = '1') {
        return Zx_Model::get_num(self::$table, $where);
    }

    public static function create($arr) {
        $arr['date_created'] = date('Y-m-d h:i:s');
        return Zx_Model::create(self::$table, self::$fields, $arr);
    }

    public static function update($id, $arr) {
        return Zx_Model::update(self::$table, $id, self::$fields, $arr);
    }

    public static function delete($id) {
        return Zx_Model::delete(self::$table, $id);
    }

}