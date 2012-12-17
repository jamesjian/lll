<?php

namespace App\Transaction;

use \App\Model\Tag as Model_Tag;
use \App\Transaction\Tool as Transaction_Tool;
use \Zx\Message\Message;
use \Zx\Model\Mysql;

class Tag {

    public static function create($arr = array()) {
        if (count($arr) > 0 && isset($arr['name'])
                && !Model_Tag::exist_tag_by_tag_name($arr['name'])) {
            //initialize
            if (!isset($arr['num_of_questions']))
                $arr['num_of_questions'] = 0;
            if (!isset($arr['num_of_ads']))
                $arr['num_of_ads'] = 0;
            if (!isset($arr['num_of_views']))
                $arr['num_of_views'] = 0;
            if (Model_Tag::create($arr)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('wrong info');
            return false;
        }
    }

    public static function update($id, $arr) {
        //\Zx\Test\Test::object_log('arr', $arr, __FILE__, __LINE__, __CLASS__, __METHOD__);

        if (isset($arr['name']) && !Model_Tag::duplicate_tag_name($id, $arr['name'])) {
            if (Model_Tag::update($id, $arr)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('wrong info or duplicate');
            return false;
        }
    }

    public static function delete_tag($id) {
        $tag = Model_Tag::get_one($id);
        if ($tag['num_of_questions'] == 0 && $tag['num_of_ads'] == 0) {
            if (Model_Tag::delete($id)) {
                Message::set_success_message('success');
                return true;
            } else {
                Message::set_error_message('fail');
                return false;
            }
        } else {
            Message::set_error_message('cannot be deleted because it has question or ad');
            return false;
        }
    }
    /**
     * only by admin
     * when transfer tag:
     * 1. question table tids, tnames
     * 2. ad table tids, tnames
     * 3. tag table: original tag: decrease num_of_questions and num_of_ads, maybe different
     *               new tags: increase num_of_questions and num_of_ads, maybe different
     * @param str $tag_source  usually one tag name such as “悉尼大学” or "sydney university'
     * @param str $tag_dest usually multiple tag names such as @悉尼@大学@
     */
    public static function transfer_tag($tag_source, $tag_dest) {
        $tname = Transaction_Tool::get_clear_string($tag_source);
        $tag_source = Model_Tag::get_tag_by_name($tname);
        if ($tag_source) {
            $tid_source = $tag_source['id'];
            $tname_source = $tag_source['name'];
            $num_of_questions_source = $tag_source['num_of_questions'];
            $num_of_ads_source = $tag_source['num_of_ads'];
            $tdest_arr = explode(TNAME_SEPERATOR, $tag_dest);
            foreach ($tdest_arr as $tname) {
                $tname = Transaction_Tool::get_clear_string($tname);
                if ($tag=  Model_Tag::exist_tag_by_tag_name($tag_name)) {
                    $tid = $tag['id'];
                    $tname = $tag['name'];
                } else {
                    $tag = Model_Tag::create($arr);
                    $tid = $tag['id'];
                    $tname = $tag['name'];
                }
                $tids = $tid . TNAME_SEPERATOR;
                $tnames = $tname . TNAME_SEPERATOR;
            }
            $q = "UPDATE " . TABLE_TAG . ' SET num_of_questions=0, num_of_ads=0 WHERE id=' . $tid_source;
            foreach ($tid_arr as $tid) {
                $q = "UPDATE " . TABLE_TAG . ' SET num_of_questions=num_of_questions+' . $num_of_questions_source . ',' .
                        'num_of_ads=num_of_ads+' . $num_of_ads_source. ' WHERE id=' . $tid;
                    ;
                
            }
            $q = "UPDATE " . TABLE_QUESTION . " SET tids=REPLACE('$tid_source','$tids', tids)" . " WHERE tids LIKE '%$tid_source%'";
            $q = "UPDATE " . TABLE_QUESTION . " SET tnamess=REPLACE('$tname_source','$tnames', tnames)" . " WHERE tnames LIKE '%$tname_source%'";
        }
        
    }
    /**
     * merge $id_source to $id_dest
     * 1. tag table,  sum of num_of_questions, num_of_ads
     * 2. if has question, question table: change tids and tnames
     * 3. if has ad, ad table: change tids and tnames
     * 4. delete source tag
     * @param int $id_source
     * @param int $id_dest
     */
    public static function merge_tag($id_source, $id_dest) {
        $s_tag = Model_Tag::get_one($id_source);
        $d_tag = Model_Tag::get_one($id_dest);
        //must exist
        if ($s_tag && $d_tag) {
            $s_tag_name = $s_tag['name'];
            $d_tag_name = $d_tag['name'];
            //tag
            $d_arr = array(
                'num_of_questions' => $d_tag['num_of_questions'] + $s_tag['num_of_questions'],
                'num_of_ads' => $d_tag['num_of_ads'] + $s_tag['num_of_ads'],
                'num_of_views' => $d_tag['num_of_views'] + $s_tag['num_of_views'],
            );
            Model_Tag::update($id_dest, $d_arr);
            //question
            $q = "UPDATE question SET 
                        tids=REPLACE(tids, '@{$id_source}@', '@{$id_dest}@'),
                        tnames=REPLACE(tnames, '@{$s_tag_name}@', '@{$d_tag_name}@'),                        
                     WHERE tids LIKE '%@{$id_source}@%";
            Mysql::exec($q);
            //ad
            $q = "UPDATE ad SET 
                        tids=REPLACE(tids, '@{$id_source}@', '@{$id_dest}@'),
                        tnames=REPLACE(tnames, '@{$s_tag_name}@', '@{$d_tag_name}@'),                        
                     WHERE tids LIKE '%@{$id_source}@%";
            Mysql::exec($q);
            //tag
            Model_Tag::delete($id_source);
        } else {
            Message::set_error_message('not exist');
            return false;
        }
    }

    function backup_sql() {
        $sql = "SELECT * FROM tag";
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