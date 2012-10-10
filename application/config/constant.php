<?php

//general 
define('SERVER_NAME', $_SERVER['SERVER_NAME']);

switch (SERVER_NAME) {
    case 'localhost':
        define('URL_PREFIX', '/pree/');
        break;
    default:
        define('URL_PREFIX', '/');
}

define('HTML_ROOT', 'http://' . SERVER_NAME . URL_PREFIX);
define('LIBRARY_PATH', PHP_ROOT . 'library' . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', LIBRARY_PATH . 'zx' . DIRECTORY_SEPARATOR);
define('APPLICATION_PATH', PHP_ROOT . 'application' . DIRECTORY_SEPARATOR);

define('FRONT_VIEW_PATH', APPLICATION_PATH . 'module/front/view' . DIRECTORY_SEPARATOR);
define('ADMIN_VIEW_PATH', APPLICATION_PATH . 'module/admin/view' . DIRECTORY_SEPARATOR);
define('FRONT_HTML_ROOT', HTML_ROOT . 'front/');
define('ADMIN_HTML_ROOT', HTML_ROOT . 'admin/');


define('PHP_PUBLIC_PATH', PHP_ROOT); //for file upload

define('PHP_UPLOAD_PATH', PHP_PUBLIC_PATH . 'upload' . DIRECTORY_SEPARATOR);
define('HTML_UPLOAD_PATH', HTML_ROOT . 'upload/');

define('PHP_CKEDITOR_PATH', PHP_PUBLIC_PATH . 'js/ckeditor' . DIRECTORY_SEPARATOR);
define('HTML_CKEDITOR_PATH', HTML_ROOT . 'js/ckeditor/');

define('SESSION_LIEFTIME', 1200); //used by session class

define('BR', '<br />');
define('LOG_FILE', PHP_ROOT . 'test/my_log.php');
define('MAXIMUM_ROWS', 999999);



define('NUM_OF_RECORDS_IN_ADMIN_PAGE', 3);
define('NUM_OF_BLOGS_IN_CAT_PAGE', 30);
define('NUM_OF_ITEMS_IN_PAGINATION', 11); //use odd number
//namespace related
define('APP_NAMESPACE', 'App');
define('CONTROLLER_NAMESPACE', 'Controller');
define('MODEL_NAMESPACE', 'Model');
define('VIEW_NAMESPACE', 'View');
define('FRONT_NAMESPACE', 'Front');
define('MEMBER_NAMESPACE', 'Mem');
define('ADMIN_NAMESPACE', 'Admin');

//database related



switch (SERVER_NAME) {
    case 'localhost':
        define('DBNAME', 'z2');
        define('DBHOST', 'localhost');
        define('DBUSER', 'root');
        define('DBPASS', '');        
        break;
    default:
        define('DBNAME', 'huarend1_baoxian');
        define('DBHOST', 'localhost');
        define('DBUSER', 'huarend1_baoxian');
        define('DBPASS', 'L=Xk4OvqM}z(');

}