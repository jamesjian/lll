<?php
/**
 * __DIR__ IS NOT SUPPORTED IN PHP 5.2
 * define('PHP_ROOT', dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);  
 */
define('PHP_ROOT', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR);  
/*
  F:\jian\wamp\www\pree\
  /home1/huarend1/public_html/baoxiancom/
 * 
 */
include PHP_ROOT . 'application/config/constant.php';
/**
 * because PHP version in cron interface is only 5.2, does not support namespace
 * so autoloader.php is very useful
   include SYSTEM_PATH . 'autoloader.php';
 * 
 */
include SYSTEM_PATH . 'test/test_no_namespace.php';
include SYSTEM_PATH . 'model/mysql_no_namespace.php';
require LIBRARY_PATH . 'Swift-4.2.1/lib/swift_required.php';
include APPLICATION_PATH . 'transaction/article_no_namespace.php';
include APPLICATION_PATH . 'transaction/swiftmail_no_namespace.php';
