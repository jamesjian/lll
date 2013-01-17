<?php
/*
 /home1/huarend1/public_html/baoxiancom/application/module/cron/c1.php
 * 

include '../../../library/zx/test/test.php';
echo 'xxx';
define('PHP_ROOT', '/home1/huarend1/public_html/baoxiancom/');
define('LOG_FILE', PHP_ROOT . 'test/my_log.php');
\Zx\Test\Test::object_log('$description', time(), __FILE__, __LINE__, __CLASS__, __METHOD__);      
 *  
 */
echo php_sapi_name();
echo '------------';
//echo phpversion();
define('PHP_ROOT', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR);  
echo PHP_ROOT;
echo '============'; 