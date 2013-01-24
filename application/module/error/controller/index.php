<?php
namespace App\Module\Error\Controller;
use \Zx\View\View as Zx_View;
class Index {
    public $template_path;

    public function init() {
        $this->template_path = APPLICATION_PATH . 'module/error/view/templates/';
        Zx_View::set_template_file($this->template_path . 'template.php');
        Zx_View::set_template_var('title', 'error page');
        Zx_View::set_template_var('keyword', 'error page');
    }
    public static function index()
	{
		echo "error message";
	}
	public static function page_not_exist()
	{
		echo "page does not exist";
	}
}