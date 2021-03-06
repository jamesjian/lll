<?php

//general 
if (PHP_SAPI === 'cgi-fcgi') {
    define('SERVER_NAME', 'huarendian.com');
} elseif (PHP_SAPI == 'cli') {
    define('SERVER_NAME', 'localhost');
} else {
    define('SERVER_NAME', $_SERVER['SERVER_NAME']);
}
include 'constant_db.php';
define('HTML_ROOT', 'http://' . SERVER_NAME . URL_PREFIX);
define('LIBRARY_PATH', dirname(PHP_ROOT) . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', LIBRARY_PATH . 'zx' . DIRECTORY_SEPARATOR);
define('APPLICATION_PATH', PHP_ROOT . 'application' . DIRECTORY_SEPARATOR);
//cache and session table is controlled by library, they don't have TABLE_PREFIX
$tables = array('ad','answer', 'article', 'article_category','article_reply' ,'claim',
                'claim_category','page', 'page_category','question', 'region', 
    'score',
                'staff','tag','user','vote',);
foreach ($tables as $table) {
    define('TABLE_' . strtoupper($table), TABLE_PREFIX . $table);  //TABLE_PREFIX is defined in constant_db.php
}


define('FRONT_VIEW_PATH', APPLICATION_PATH . 'module/front/view' . DIRECTORY_SEPARATOR);
define('USER_VIEW_PATH', APPLICATION_PATH . 'module/user/view' . DIRECTORY_SEPARATOR);
define('ADMIN_VIEW_PATH', APPLICATION_PATH . 'module/admin/view' . DIRECTORY_SEPARATOR);
define('FRONT_HTML_ROOT', HTML_ROOT . 'front/');
define('USER_HTML_ROOT', HTML_ROOT . 'user/');
define('ADMIN_HTML_ROOT', HTML_ROOT . 'admin/');
define('HTML_IMAGE_ROOT', HTML_ROOT . 'image/');


define('PHP_PUBLIC_PATH', PHP_ROOT); //for file upload

define('PHP_UPLOAD_PATH', PHP_PUBLIC_PATH . 'upload' . DIRECTORY_SEPARATOR);
define('HTML_UPLOAD_PATH', HTML_ROOT . 'upload/');

define('PHP_CKEDITOR_PATH', PHP_PUBLIC_PATH . 'js/ckeditor' . DIRECTORY_SEPARATOR);
define('HTML_CKEDITOR_PATH', HTML_ROOT . 'js/ckeditor/');

define('SESSION_LIEFTIME', 12000); //used by session class

define('BR', '<br />');
define('LOG_FILE', PHP_ROOT . 'test/my_log.php');
define('MAXIMUM_ROWS', 999999);
define('SITENAME', 'NAME OF THIS SITE');



define('NUM_OF_TNAMES_PER_ITEM', 5);
define('TNAME_SEPERATOR', '@');
define('NUM_OF_ITEMS_IN_ONE_PAGE', 20);
define('NUM_OF_QUESTIONS_IN_FRONT_PAGE', 100);
define('NUM_OF_ADS_IN_FRONT_PAGE', 100);
define('NUM_OF_ANSWERS_IN_FRONT_PAGE', 10);
define('NUM_OF_RECENT_ADS_IN_FRONT_PAGE', 10);
define('NUM_OF_RECENT_QUESTIONS_IN_FRONT_PAGE', 10);
define('NUM_OF_RECENT_ANSWERS_IN_FRONT_PAGE', 10);
define('NUM_OF_TAGS_IN_FRONT_PAGE', 100);
define('NUM_OF_USERS_IN_FRONT_PAGE', 100);


define('DAYS_OF_AD', 28);  //4 weeks
define('SCORE_OF_CREATE_QUESTION', 1);
define('SCORE_OF_CREATE_ANSWER', 1);
define('SCORE_OF_CREATE_AD', 1);
define('SCORE_OF_VOTE_QUESTION', 1);
define('SCORE_OF_VOTE_ANSWER', 1);

define('NUM_OF_RECORDS_IN_ADMIN_PAGE', 20);
define('NUM_OF_ARTICLES_IN_CAT_PAGE', 30);
define('NUM_OF_ITEMS_IN_PAGINATION', 11); //use odd number
//namespace related
define('APP_NAMESPACE', 'App');
define('CONTROLLER_NAMESPACE', 'Controller');
define('MODEL_NAMESPACE', 'Model');
define('VIEW_NAMESPACE', 'View');
define('FRONT_NAMESPACE', 'Front');
define('MEMBER_NAMESPACE', 'Mem');
define('ADMIN_NAMESPACE', 'Admin');
define('VOTE_TITLE','关注');
define('CLAIM_TITLE','举报');
define('SYSTEM_ERROR_MESSAGE','系统故障， 请重试或联系网站管理员。');



