<?php

$dsn = 'mysql:dbname=jm3;host=localhost';
$dbh = new PDO($dsn, 'root', '');
/*
$q = 'SELECT link_name, profession_id
            FROM misc_professions_allianz
            ';
//$params = array(':where'=>1, ':offset'=>0, ':row_count'=>9999, 
//		                ':order_by'=>'title', ':direction'=>'asc');			
$sth = $dbh->prepare($q);
$sth->execute();
$r = $sth->fetchAll();
foreach ($r as $row) {}
*/
        $handle = fopen("username.php", "r");
        $rows_cn = array();
        while (($data = fgetcsv($handle, 100, ",", '"')) !== FALSE) {
            if (trim($data[0]) != '') {
                //empty line will be ignored
                $rows_cn[] = $data[0];
            }
        }
        fclose($handle);
        $handle = fopen("usernameen.php", "r");
        $rows_en = array();
        while (($data = fgetcsv($handle, 100, ",", '"')) !== FALSE) {
            if (trim($data[0]) != '') {
                //empty line will be ignored
                $rows_en[] = $data[0];
            }
        }
        fclose($handle);    
        shuffle($rows_en); shuffle($rows_cn);
$j=0;
$length = count($rows_en);
//$length=5;
for ($i=0; $i<$length; $i++){
    echo $i, '<br />';
    $q = "insert into ts8wl_user(user_name, email) values ('" .$rows_en[$i] . "', '".$rows_en[$i] . "')";
    //echo $q;
    $dbh->exec($q);
    if (($i%100)==1) {
            $q = "insert into ts8wl_user(user_name, email) values ('" .$rows_cn[$j] . "', '".$rows_cn[$i] . "')";
    $dbh->exec($q);
    echo 'j=', $j, '<br />';
    $j=$j+1;
    
    }
}