<?php
namespace Ajax\php\ci;

class JsUtils extends \Ajax\JsUtils{
	protected $ci;
	protected $_my_controller_paths= array();
	protected $_my_controllers= array();

	public function __construct($params=array(),$injected=NULL){
		parent::__construct($params,$injected);
		$this->_my_controller_paths = array(APPPATH);
	}
	public function getUrl($url){
		return site_url($url);
	}

	public function getCi(){
		if(isset($this->ci)===false){
			$this->ci =& get_instance();
			$this->ci->load->helper('url');
		}
		return $this->ci;
	}

	public function addViewElement($identifier,$content,&$view){
		if(\array_key_exists("q", $view)===false){
			$view["q"]=array();
		}
		$view["q"][$identifier]=$content;
	}

	public function createScriptVariable(&$view,$view_var, $output){
		$view[$view_var]=$output;
	}

	public function forward($initialControllerInstance,$controllerName,$actionName,$params=NULL){
		$ci=$this->getCi();
		$controllerName=strtolower($controllerName);
		$this->controller($controllerName);
		\ob_start();
		$ci->{$controllerName}->{$actionName}($params);
		$result=ob_get_contents();
		\ob_end_clean();
		return $result;
	}

	public function renderContent($initialControllerInstance,$viewName, $params=NULL) {
		return $initialControllerInstance->load->view($viewName, $params, true);
	}

	public function fromDispatcher($dispatcher){
		return array_values($dispatcher->uri->segment_array());
	}

	public function controller($controller, $name = '', $db_conn = FALSE){
		if (\is_array($controller)){
			foreach ($controller as $babe){
				$this->controller($babe);
			}
			return;
		}
		if ($controller == ''){
			return;
		}
		$path = '';
		// Is the controller in a sub-folder? If so, parse out the filename and path.
		if (($last_slash = strrpos($controller, '/')) !== FALSE){
			// The path is in front of the last slash
			$path = substr($controller, 0, $last_slash + 1);
			// And the controller name behind it
			$controller = substr($controller, $last_slash + 1);
		}

		if ($name == ''){
			$name = $controller;
		}

		if (in_array($name, $this->_my_controllers, TRUE)){
			return;
		}

		$CI =$this->getCi();
		if (isset($CI->$name)){
			show_error('The controller name you are loading is the name of a resource that is already being used: '.$name);
		}
		$controller = strtolower($controller);
		foreach ($this->_my_controller_paths as $mod_path){
			if ( ! file_exists($mod_path.'controllers/'.$path.$controller.'.php')){
				continue;
			}
			if ($db_conn !== FALSE && ! class_exists('CI_DB')){
				if ($db_conn === TRUE){
					$db_conn = '';
				}
				$CI->load->database($db_conn, FALSE, TRUE);
			}
			if ( ! class_exists('CI_Controller')){
				load_class('Controller', 'core');
			}
			require_once($mod_path.'controllers/'.$path.$controller.'.php');
			$controller = ucfirst($controller);
			$CI->$name = new $controller();

			$this->_my_controllers[] = $name;
			return;
		}
		show_error('Unable to locate the controller you have specified: '.$controller);
	}
}
