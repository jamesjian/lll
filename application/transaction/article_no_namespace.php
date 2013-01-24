<?php
/**
 * it's for PHP 5.2 
 * same as App\Transaction\Swiftmail
 * without NAMESPACE
 */


class Article {

    /**
     * for cron job
     * backup article table and then email to admin
     */
    public static function backup_sql() {
        $sql = "SELECT * FROM article";
        $r = Zx_Mysql::select_all($sql);
        //Test::object_log('$description', $r, __FILE__, __LINE__, __CLASS__, __METHOD__);
        
        if ($r) {
            $str = 'INSERT INTO article VALUES ';
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
        } 
    }

}