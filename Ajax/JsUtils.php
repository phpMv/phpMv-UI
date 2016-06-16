<?php

namespace Ajax;

use Ajax\config\DefaultConfig;
use Ajax\config\Config;
use Ajax\lib\CDNJQuery;
use Ajax\lib\CDNGuiGen;
use Ajax\lib\CDNCoreCss;
use Ajax\common\traits\JsUtilsEventsTrait;
use Ajax\common\traits\JsUtilsActionsTrait;
use Ajax\common\traits\JsUtilsAjaxTrait;

/**
 * JQuery PHP library
 *
 * @author jcheron
 * @version 1.004
 * @license Apache 2 http://www.apache.org/licenses/
 */
/**
 * JsUtils Class : Service to be injected
 */
abstract class JsUtils{
	use JsUtilsEventsTrait,JsUtilsActionsTrait,JsUtilsAjaxTrait;

	protected $js;
	protected $cdns;
	protected $params;
	protected $injected;
	/**
	 *
	 * @var JqueryUI
	 */
	protected $_ui;
	/**
	 *
	 * @var Bootstrap
	 */
	protected $_bootstrap;

	/**
	 *
	 * @var Semantic
	 */
	protected $_semantic;
	/**
	 *
	 * @var Config
	 */
	protected $config;

	protected function _setDi($di) {
		if ($this->js!=null&&$di!=null)
			$this->js->setDi($di);
	}

	public abstract function getUrl($url);
	public abstract function addViewElement($identifier,$content,&$view);
	public abstract function createScriptVariable(&$view,$view_var, $output);
	/**
	 * render the content of $controller::$action and set the response to the modal content
	 * @param Controller $initialController
	 * @param string $controller a Phalcon controller
	 * @param string $action a Phalcon action
	 * @param array $params
	 */
	public abstract function forward($initialController,$controller,$action,$params);
	/**
	 * render the content of an existing view : $viewName and set the response to the modal content
 	 * @param Controller $initialControllerInstance
	 * @param View $viewName
	 * @param $params The parameters to pass to the view
	 */
	public abstract function renderContent($initialControllerInstance,$viewName, $params=NULL);

	/**
	 * Collect url parts from the request dispatcher : controllerName, actionName, parameters
	 * @param mixed $dispatcher
	 * @return array
	 */
	public abstract function fromDispatcher($dispatcher);

	/**
	 *
	 * @param JqueryUI $ui
	 * @return \Ajax\JqueryUI
	 */
	public function ui($ui=NULL) {
		if ($ui!==NULL) {
			$this->_ui=$ui;
			if ($this->js!=null) {
				$this->js->ui($ui);
				$ui->setJs($this);
			}
			$bs=$this->bootstrap();
			if (isset($bs)) {
				$this->conflict();
			}
		}
		return $this->_ui;
	}

	/**
	 *
	 * @param Bootstrap $bootstrap
	 * @return \Ajax\Bootstrap
	 */
	public function bootstrap($bootstrap=NULL) {
		if ($bootstrap!==NULL) {
			$this->_bootstrap=$bootstrap;
			if ($this->js!=null) {
				$this->js->bootstrap($bootstrap);
				$bootstrap->setJs($this);
			}
			$ui=$this->ui();
			if (isset($ui)) {
				$this->conflict();
			}
		}
		return $this->_bootstrap;
	}

	/**
	 *
	 * @param Semantic $semantic
	 * @return \Ajax\Semantic
	 */
	public function semantic($semantic=NULL) {
		if ($semantic!==NULL) {
			$this->_semantic=$semantic;
			if ($this->js!=null) {
				$this->js->semantic($semantic);
				$semantic->setJs($this);
			}
			$ui=$this->ui();
			if (isset($ui)) {
				$this->conflict();
			}
		}
		return $this->_semantic;
	}

	protected function conflict() {
		$this->js->_addToCompile("var btn = $.fn.button.noConflict();$.fn.btn = btn;");
	}

	/**
	 *
	 * @param \Ajax\config\Config $config
	 * @return \Ajax\config\Config
	 */
	public function config($config=NULL) {
		if ($config===NULL) {
			if ($this->config===NULL) {
				$this->config=new DefaultConfig();
			}
		} elseif (is_array($config)) {
			$this->config=new Config($config);
		} elseif ($config instanceof Config) {
			$this->config=$config;
		}
		return $this->config;
	}

	public function __construct($params=array(),$injected=NULL) {
		$defaults=array (
				'driver' => 'Jquery',
				'debug' => true
		);
		foreach ( $defaults as $key => $val ) {
			if (isset($params[$key])===false || $params[$key]==="") {
				$params[$key]=$defaults[$key];
			}
		}
		$this->js=new Jquery($params,$this);

		if(\array_key_exists("semantic", $params)){
			$this->semantic(new Semantic());
		}
		$this->cdns=array ();
		$this->params=$params;
		$this->injected=$injected;
	}

	public function __set($property, $value){
		switch ($property){
			case "bootstrap":
				$this->bootstrap($value);
				break;
			case "semantic":
				$this->semantic(value);
				break;
			case "ui":
				$this->ui($value);
				break;
			default:
				throw new \Exception('Unknown property !');
		}
	}

	public function getParam($key){
		return $this->params[$key];
	}

	public function addToCompile($jsScript) {
		$this->js->_addToCompile($jsScript);
	}

	/**
	 * Outputs the called javascript to the screen
	 *
	 * @param string $js code to output
	 * @return string
	 */
	public function output($js) {
		return $this->js->_output($js);
	}

	/**
	 * Document ready method
	 *
	 * @param string $js code to execute
	 * @return string
	 */
	public function ready($js) {
		return $this->js->_document_ready($js);
	}

	/**
	 * gather together all script needing to be output
	 *
	 * @param View $view
	 * @param $view_var
	 * @param $script_tags
	 * @return string
	 */
	public function compile(&$view=NULL, $view_var='script_foot', $script_tags=TRUE) {
		$bs=$this->_bootstrap;
		if (isset($bs)&&isset($view)) {
			$bs->compileHtml($this, $view);
		}
		$sem=$this->_semantic;
		if (isset($sem)&&isset($view)) {
			$sem->compileHtml($this, $view);
		}
		return $this->js->_compile($view, $view_var, $script_tags);
	}

	/**
	 * Clears any previous javascript collected for output
	 *
	 * @return void
	 */
	public function clear_compile() {
		$this->js->_clear_compile();
	}

	/**
	 * Outputs a <script> tag
	 *
	 * @param string $script
	 * @param boolean $cdata If a CDATA section should be added
	 * @return string
	 */
	public function inline($script, $cdata=TRUE) {
		$str=$this->_open_script();
		$str.=($cdata) ? "\n// <![CDATA[\n{$script}\n// ]]>\n" : "\n{$script}\n";
		$str.=$this->_close_script();
		return $str;
	}

	/**
	 * Outputs an opening <script>
	 *
	 * @param string $src
	 * @return string
	 */
	private function _open_script($src='') {
		$str='<script type="text/javascript" ';
		$str.=($src=='') ? '>' : ' src="'.$src.'">';
		return $str;
	}

	/**
	 * Outputs an closing </script>
	 *
	 * @param string $extra
	 * @return string
	 */
	private function _close_script($extra="\n") {
		return "</script>$extra";
	}


	/**
	 * Can be passed a database result or associative array and returns a JSON formatted string
	 *
	 * @param mixed $result result set or array
	 * @param bool $match_array_type match array types (defaults to objects)
	 * @return string json formatted string
	 */
	public function generate_json($result=NULL, $match_array_type=FALSE) {
		// JSON data can optionally be passed to this function
		// either as a database result object or an array, or a user supplied array
		if (!is_null($result)) {
			if (is_object($result)) {
				$json_result=$result->result_array();
			} elseif (is_array($result)) {
				$json_result=$result;
			} else {
				return $this->_prep_args($result);
			}
		} else {
			return 'null';
		}
		return $this->_create_json($json_result, $match_array_type);
	}

	private function _create_json($json_result, $match_array_type) {
		$json=array ();
		$_is_assoc=TRUE;
		if (!is_array($json_result)&&empty($json_result)) {
			show_error("Generate JSON Failed - Illegal key, value pair.");
		} elseif ($match_array_type) {
			$_is_assoc=$this->_is_associative_array($json_result);
		}
		foreach ( $json_result as $k => $v ) {
			if ($_is_assoc) {
				$json[]=$this->_prep_args($k, TRUE).':'.$this->generate_json($v, $match_array_type);
			} else {
				$json[]=$this->generate_json($v, $match_array_type);
			}
		}
		$json=implode(',', $json);
		return $_is_assoc ? "{".$json."}" : "[".$json."]";
	}

	/**
	 * Checks for an associative array
	 *
	 * @param type
	 * @return type
	 */
	public function _is_associative_array($arr) {
		foreach ( array_keys($arr) as $key => $val ) {
			if ($key!==$val) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Ensures a standard json value and escapes values
	 *
	 * @param type
	 * @return type
	 */
	public function _prep_args($result, $is_key=FALSE) {
		if (is_null($result)) {
			return 'null';
		} elseif (is_bool($result)) {
			return ($result===TRUE) ? 'true' : 'false';
		} elseif (is_string($result)||$is_key) {
			return '"'.str_replace(array (
					'\\',"\t","\n","\r",'"','/'
			), array (
					'\\\\','\\t','\\n',"\\r",'\"','\/'
			), $result).'"';
		} elseif (is_scalar($result)) {
			return $result;
		}
	}

	public function getCDNs() {
		return $this->cdns;
	}

	public function setCDNs($cdns) {
		if (is_array($cdns)===false) {
			$cdns=array (
					$cdns
			);
		}
		$this->cdns=$cdns;
	}

	public function genCDNs($template=NULL) {
		$hasJQuery=false;
		$hasJQueryUI=false;
		$hasBootstrap=false;
		$hasSemantic=false;
		$result=array ();
		foreach ( $this->cdns as $cdn ) {
			switch(get_class($cdn)) {
				case "Ajax\lib\CDNJQuery":
					$hasJQuery=true;
					$result[0]=$cdn;
					break;
				case "Ajax\lib\CDNJQuery":
					$hasJQueryUI=true;
					$result[1]=$cdn;
					break;
				case "Ajax\lib\CDNCoreCss":
					if($cdn->getFramework()==="Bootstrap")
						$hasBootstrap=true;
					elseif($cdn->getFramework()==="Semantic")
						$hasSemantic=true;
					if($hasSemantic || $hasBootstrap)
						$result[2]=$cdn;
					break;
			}
		}
		if ($hasJQuery===false) {
			$result[0]=new CDNJQuery("x");
		}
		if ($hasJQueryUI===false&&isset($this->_ui)) {
			$result[1]=new CDNGuiGen("x", $template);
		}
		if ($hasBootstrap===false&&isset($this->_bootstrap)) {
			$result[2]=new CDNCoreCss("Bootstrap","x");
		}
		if ($hasSemantic===false&&isset($this->_semantic)) {
			$result[2]=new CDNCoreCss("Semantic","x");
		}
		ksort($result);
		return implode("\n", $result);
	}

	public function getInjected() {
		return $this->injected;
	}

}
