<?php
namespace Zx\Controller;
use \Zx\Test\Test;
/**
$query_string = $_SERVER['QUERY_STRING'];   //M1=2&M2=3
$uri = $_SERVER['REQUEST_URI');  //   /z2/public/xxx/yyy/zzz?M1=2&M2=3
*/
class Route{
	public static $module_controller_action = array('module'=>'','controller'=>'', 'action'=>'');
	public static $params=array();
	public static function get_modules()
	{
		$ini_array = parse_ini_file(APPLICATION_PATH . 'config/module.php');
		return $ini_array['module'];
	}
        public static function get_routes()
        {
            $ini_array = parse_ini_file(APPLICATION_PATH . 'config/route.php');
            $route_configs = $ini_array['route'];
            $routes = array();
            foreach ($route_configs as $route_config){
                $arr = explode(',', $route_config);
                $routes[$arr[0]] = $arr[1]; //about-us.php->front/article/about_us
            }
            return $routes;
        }
	public static function analyze_url()
	{
		$url = str_replace(URL_PREFIX, '', $_SERVER['REQUEST_URI']); 
		$arr = explode('/',$url);
		$length = count($arr);
		if ($length < 3) {
                    if ($length == 1) {
                    //case 1 about-us.php
                    $original_name = $arr[0];
                    $routes = self::get_routes();
                    if (array_key_exists($original_name, $routes)) {
                        $route = $routes[$original_name];
                        $arr = explode('/',$route);
                        self::$module_controller_action = array(
				'module'=>$arr[0],
				'controller'=>$arr[1], 
				'action'=>$arr[2],
			);
                    }
                    }
		} else {
			//module/controller/action/params
                    //currently remove /pree/public/
			self::$module_controller_action = array(
				'module'=>$arr[0],
				'controller'=>$arr[1], 
				'action'=>$arr[2],
			);
			for ($i=3; $i<$length; $i++) {
				self::$params[] = $arr[$i];
			}
		}
                Test::object_log('$module_controller_action', self::$module_controller_action, __FILE__, __LINE__, __CLASS__, __METHOD__);
		self::set_module();
		self::set_controller();
		self::set_action();
                Test::object_log('$module_controller_action', self::$module_controller_action, __FILE__, __LINE__, __CLASS__, __METHOD__);
		//self::set_params();
	}
/** 
module must be defined in config/module.php
if module does not exist, go to error
*/
	public static function set_module()
	{
		$modules = self::get_modules();
		$module = self::$module_controller_action['module'];
		if (!in_array($module, $modules)) {
			self::set_page_not_exist_url();
		} 
		
		
	}
	public static function set_page_not_exist_url()
	{
				$module = 'error';
			$controller = 'index';
			$action = 'page_not_exist';
				self::$module_controller_action = array(
				'module'=>$module,
				'controller'=>$controller, 
				'action'=>$action,
			);
			self::$params = array();
	}
	public static function get_module()
	{
		if (self::$module_controller_action['module'] == '') {
			self::analyze_url();
			}
			return self::$module_controller_action['module'];
	}
/** 
controller class file must exist in module folder
if controller does not exist, go to error
*/	
	public static function set_controller()
	{
		$module = self::get_module();
		$controller_file_path = APPLICATION_PATH . 'module/' . $module . '/controller/' . 
								self::$module_controller_action['controller'] . '.php';
		if (!file_exists($controller_file_path)) {
			self::set_page_not_exist_url();
		}
	}
	
	public static function get_controller()
	{
			if (self::$module_controller_action['controller'] == '') {
			return self::analyze_url();
		} else {
			return self::$module_controller_action['controller'];
		}
	}
	/**
	at this moment, controller class must exist
	*/
	public static function get_controller_class()
	{
	$module = self::get_module();
		$controller = self::get_controller();
		$controller_file_path = APPLICATION_PATH . 'module/' . $module . '/controller/' . 
								$controller . '.php';
		$controller_class_name = '\\App\\Module\\' . ucfirst($module) . '\\Controller\\' . ucfirst($controller); 
		include_once $controller_file_path;
		return $controller_class_name;
	}
/** 
action method must exist in controller class file
if action does not exist, go to error
*/	
	public static function set_action()
	{
		$controller_class = self::get_controller_class();
		$action = self::$module_controller_action['action'];
		
		if (!method_exists($controller_class,$action)) {
			self::set_page_not_exist_url();
		}
	}
	
	public static function get_action()
	{
			if (self::$module_controller_action['action'] == '') {
			return self::analyze_url();
		} else {
			return self::$module_controller_action['action'];
		}
	}
	/**
	
	*/
	public static function get_params()
	{
		return self::$params;
	
	}
	/**
	params have been set in self::analyze_url()
	*/
	public static function set_params()
	{
	
	}
}