<?php
//ALTER TABLE some_table AUTO_INCREMENT=4
$dsn = 'mysql:dbname=jm3;host=localhost';
$dbh = new PDO($dsn, 'root', '');
$q = "SET NAMES UTF8";
$dbh->exec($q);
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
        $rows_cn[] = trim($data[0]);
    }
}
fclose($handle);
$handle = fopen("usernameen.php", "r");
$rows_en = array();
while (($data = fgetcsv($handle, 100, ",", '"')) !== FALSE) {
    if (trim($data[0]) != '') {
        //empty line will be ignored
        $rows_en[] = trim($data[0]);
    }
}
fclose($handle);
shuffle($rows_en);
shuffle($rows_cn);
$j = 0;
$en_leng = count($rows_en);
$cn_leng = count($rows_cn);
$k = 0;
$q = "insert into ts8wl_user(uname, email) values ";
//$length=15;
for ($i = 0; $i < $en_leng; $i++) {
    //echo $i, '<br />';
    $q .= "('{$rows_en[$i]}', '{$rows_en[$i]}'),";
    //echo $q;
    if (($i % 10) == 0 && $j < $cn_leng) {
        $q .= "('{$rows_cn[$j]}', '{$rows_cn[$j]}'),";
        //echo 'j=', $j, '<br />';
        $j = $j + 1;
    }
    if ($k == 100) {
        //something wrong with name file, so don't use too big number here, 100 is ok
        $q = substr($q, 0, -1); //remove last ','
        //echo $q;
        $dbh->exec($q);
        $q = "insert into ts8wl_user(uname, email) values ";
        $k = 0;
    } else {
        $k = $k + 1;
    }
    //echo $k;
}
$q = substr($q, 0, -1); //remove last ','
$dbh->exec($q);