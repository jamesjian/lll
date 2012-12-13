<?php
//this file is only for company_and_user form, if for another
//need to be modified for kohana database session
//use pdo or mysql or mysqli to fetch session data from database 
define('DOCROOT', realpath(dirname('../../index.php')).DIRECTORY_SEPARATOR);
switch ($_SERVER['SERVER_NAME']){
    case 'localhost':
        define('PHPFILEROOT', realpath(dirname('../../../application/empty.php')).DIRECTORY_SEPARATOR);
        include PHPFILEROOT . 'application/config/constant_.php';
        break;
    case 'test.com.au':
    case 'www.test.com.au':
        define('PHPFILEROOT', '/home/fengyunl/application' . DIRECTORY_SEPARATOR);
        include PHPFILEROOT . 'application/config/constant_test.php';
        break;
    case 'test.com':
        define('PHPFILEROOT', '/home/hang2005/public_html/test/application' . DIRECTORY_SEPARATOR);
        include PHPFILEROOT . 'application/config/constant_preenet.php';
        break;
}
include PHPFILEROOT . 'application/config/constant_from_db.php';
/**
 * the constants in system_parameter.php are generated from system_parameter table 
 * by admin/system_parameter module, when the data in system_parameter table is changed, 
 * system_parameter.php is changed immediately
 */
//include PHPFILEROOT . 'application/config/system_parameter.php';
include PHPFILEROOT . 'application/classes/app/test.php';
App_Test::objectLog('test','xxxx', __FILE__, __LINE__, __CLASS__, __METHOD__);
App_Test::objectLog('PHPFILEROOT',PHPFILEROOT, __FILE__, __LINE__, __CLASS__, __METHOD__);
/**
 * The directory in which your application specific resources are located.
 * The application directory must contain the bootstrap.php file.
 *
 * @see  http://kohanaframework.org/guide/about.install#application
 */
$application = 'application';

/**
 * The directory in which your modules are located.
 *
 * @see  http://kohanaframework.org/guide/about.install#modules
 */
$modules = 'vendor/kohana3.2/modules';

/**
 * The directory in which the Kohana resources are located. The system
 * directory must contain the classes/kohana.php file.
 *
 * @see  http://kohanaframework.org/guide/about.install#system
 */
$system = 'vendor/kohana3.2/system';

/**
 * The default extension of resource files. If you change this, all resources
 * must be renamed to use the new extension.
 *
 * @see  http://kohanaframework.org/guide/about.install#ext
 */
define('EXT', '.php');

/**
 * Set the PHP error reporting level. If you set this in php.ini, you remove this.
 * @see  http://php.net/error_reporting
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In a production environment, it is safe to ignore notices and strict warnings.
 * Disable them by using: E_ALL ^ E_NOTICE
 *
 * When using a legacy application with PHP >= 5.3, it is recommended to disable
 * deprecated notices. Disable with: E_ALL & ~E_DEPRECATED
 */
if ($_SERVER['SERVER_NAME'] == 'test') {
	error_reporting(E_ALL | E_STRICT);
} else {
	error_reporting(0);
}
if ( is_dir(PHPFILEROOT.$application))
	$application = PHPFILEROOT.$application;
if ( is_dir(PHPFILEROOT.$modules))
	$modules = PHPFILEROOT.$modules;

if (is_dir(PHPFILEROOT.$system))
	$system = PHPFILEROOT.$system;

// Define the absolute paths for configured directories
define('APPPATH', realpath($application).DIRECTORY_SEPARATOR);
define('MODPATH', realpath($modules).DIRECTORY_SEPARATOR);
define('SYSPATH', realpath($system).DIRECTORY_SEPARATOR);

// Clean up the configuration vars
unset($application, $modules, $system);

// Bootstrap the application
require APPPATH.'bootstrap'.EXT;
include PHPFILEROOT.'application/config/constant_name.php';
//session_start();  
//$ck_upload_path = $_SESSION['CK_UPLOAD_PATH'];
$ck_upload_path = App_Session::get_session('CK_UPLOAD_PATH', '');
include_once('j_ckedit.class.php');
//$company_id = $_SESSION['editing_company_id'];
$upload_dir = 'upload/' .$ck_upload_path . '/';
echo CKEDITOR::ckFile($upload_dir);
