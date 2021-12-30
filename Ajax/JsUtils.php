<?php
namespace Ajax;

use Ajax\config\DefaultConfig;
use Ajax\config\Config;
use Ajax\common\traits\JsUtilsEventsTrait;
use Ajax\common\traits\JsUtilsActionsTrait;
use Ajax\common\traits\JsUtilsAjaxTrait;
use Ajax\common\traits\JsUtilsInternalTrait;
use Ajax\service\JArray;
use Ajax\service\Javascript;

/**
 * JQuery PHP library
 *
 * @author jcheron
 * @version 1.0.5
 * @license Apache 2 http://www.apache.org/licenses/
 */
/**
 * JsUtils Class : Service to be injected
 */
abstract class JsUtils {
	use JsUtilsEventsTrait,JsUtilsActionsTrait,JsUtilsAjaxTrait,JsUtilsInternalTrait;

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

	/**
	 * Generates an URL
	 * Used internally by phpMv
	 *
	 * @param string $url
	 * @return string the generated url
	 */
	abstract public function getUrl($url);

	/**
	 * Adds the array of controls q in the $view element
	 * Used internally by phpMv
	 *
	 * @param string $identifier
	 * @param string $content
	 * @param object $view
	 */
	abstract public function addViewElement($identifier, $content, &$view);

	/**
	 * Creates the script variable script_foot
	 * Used internally by phpMv
	 *
	 * @param object $view
	 * @param string $view_var
	 *        	default: script_foot
	 * @param string $output
	 */
	abstract public function createScriptVariable(&$view, $view_var, $output);

	/**
	 * Forwards to $controller::$action and set the response to the modal content
	 * Used internally by phpMv
	 *
	 * @param object $initialController
	 * @param string $controller
	 *        	a controller
	 * @param string $action
	 *        	an action
	 * @param array $params
	 */
	abstract public function forward($initialController, $controller, $action, $params);

	/**
	 * render the content of an existing view : $viewName and set the response to the modal content
	 * Used internally by phpMv
	 *
	 * @param object $initialControllerInstance
	 * @param string $viewName
	 * @param array $params
	 *        	The parameters to pass to the view
	 */
	abstract public function renderContent($initialControllerInstance, $viewName, $params = NULL);

	/**
	 * Collects url parts from the request dispatcher : controllerName, actionName, parameters
	 * Used internally by phpMv
	 *
	 * @param mixed $dispatcher
	 * @return array
	 */
	abstract public function fromDispatcher($dispatcher);

	/**
	 * getter or setter of the jQuery-UI variable
	 *
	 * @param JqueryUI $ui
	 * @return JqueryUI
	 */
	public function ui(JqueryUI $ui = NULL) {
		if ($ui !== NULL) {
			$this->_ui = $ui;
			$ui->setJs($this);
			$bs = $this->bootstrap();
			if (isset($bs)) {
				$this->conflict();
			}
		}
		return $this->_ui;
	}

	/**
	 * getter or setter of the Twitter Bootstrap variable
	 *
	 * @param Bootstrap $bootstrap
	 * @return Bootstrap
	 */
	public function bootstrap(Bootstrap $bootstrap = NULL) {
		if ($bootstrap !== NULL) {
			$this->_bootstrap = $bootstrap;
			$bootstrap->setJs($this);
			$ui = $this->ui();
			if (isset($ui)) {
				$this->conflict();
			}
		}
		return $this->_bootstrap;
	}

	/**
	 * getter or setter of the Semantic-UI variable
	 *
	 * @param Semantic $semantic
	 * @return Semantic
	 */
	public function semantic(Semantic $semantic = NULL) {
		if ($semantic !== NULL) {
			$this->_semantic = $semantic;
			$semantic->setJs($this);
			$ui = $this->ui();
			if (isset($ui)) {
				$this->conflict();
			}
		}
		return $this->_semantic;
	}

	/**
	 *
	 * @param Config $config
	 * @return Config
	 */
	public function config($config = NULL) {
		if ($config === NULL) {
			if ($this->config === NULL) {
				$this->config = new DefaultConfig();
			}
		} elseif (\is_array($config)) {
			$this->config = new Config($config);
		} elseif ($config instanceof Config) {
			$this->config = $config;
		}
		return $this->config;
	}

	/**
	 *
	 * @param array $params
	 *        	['debug'=>true,'defer'=>false,'ajax'=>['ajaxTransition'=>null,'attr'=>'','historize'=>false,'attr'=>''],'beforeCompileHtml'=>null,'semantic'=>false,'bootstrap'=>false,'historize'=>true,'autoActiveLinks'=>true]
	 * @param mixed $injected
	 *        	optional param for Symfony/Ubiquity
	 */
	public function __construct($params = array(), $injected = NULL) {
		$ajaxDefault = [
			'ajaxTransition' => null,
			'attr' => '',
			'historize' => false,
			'jsCallback' => null,
			'hasLoader' => true,
			'jqueryDone' => 'html',
			'async' => true,
			'params' => null,
			'headers' => null,
			'jsCondition' => null,
			'ajaxLoader' => null,
			'csrf' => false
		];
		$defaults = [
			'debug' => true,
			'defer' => false,
			'ajax' => $ajaxDefault,
			'historize' => true,
			'autoActiveLinks' => true
		];
		foreach ($defaults as $key => $val) {
			if (isset($params[$key]) === false || $params[$key] === "") {
				$params[$key] = $defaults[$key];
			}
		}
		foreach ($ajaxDefault as $key => $val) {
			if (isset($params["ajax"][$key]) === false || $params["ajax"][$key] === "") {
				$params["ajax"][$key] = $ajaxDefault[$key];
			}
		}

		if (\array_key_exists("semantic", $params)) {
			$this->semantic(new Semantic());
		}
		if (\array_key_exists("bootstrap", $params)) {
			$this->bootstrap(new Bootstrap());
		}
		if (isset($params["ajax"])) {
			if (isset($params["ajax"]["ajaxTransition"])) {
				$this->ajaxTransition = $this->setAjaxDataCall($params["ajax"]["ajaxTransition"]);
			}
			if ($params["ajax"]["historize"]) {
				$params["historize"] = true;
			}
			if ($params["ajax"]["csrf"]) {
				$this->exec($this->addCsrf(), true);
			}
		}
		if ($params["historize"]) {
			$this->execAtLast($this->onPopstate());
		}
		if ($params["autoActiveLinks"]) {
			$this->exec($this->autoActiveLinks(), true);
		}

		$this->params = $params;
		$this->injected = $injected;
		if (isset($params['gc'])) {
			\gc_disable();
		}
		$this->initialize();
	}

	/**
	 * To override for special initialization of the component
	 */
	protected function initialize() {}

	public function __set($property, $value) {
		switch ($property) {
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

	/**
	 *
	 * @param string $key
	 */
	public function getParam($key) {
		if (isset($this->params[$key]))
			return $this->params[$key];
	}

	/**
	 * Outputs the called javascript to the screen
	 *
	 * @param string $array_js
	 *        	code to output
	 * @return string
	 */
	public function output($array_js) {
		if (! is_array($array_js)) {
			$array_js = array(
				$array_js
			);
		}

		foreach ($array_js as $js) {
			$this->jquery_code_for_compile[] = "\t$js\n";
		}
	}

	/**
	 * gather together all script needing to be output
	 *
	 * @param object $view
	 * @param string $view_var
	 *        	view script variable name, default : script_foot
	 * @param boolean $script_tags
	 * @return string
	 */
	public function compile(&$view = NULL, $view_var = 'script_foot', $script_tags = TRUE) {
		if (isset($this->_ui)) {
			$this->_compileLibrary($this->_ui, $view);
		}
		if (isset($this->_bootstrap)) {
			$this->_compileLibrary($this->_bootstrap, $view);
		}
		if (isset($this->_semantic)) {
			$this->_compileLibrary($this->_semantic, $view);
		}

		if (\count($this->jquery_code_for_compile) == 0) {
			return;
		}

		$this->jquery_code_for_compile = \array_merge($this->jquery_code_for_compile, $this->jquery_code_for_compile_at_last);

		// Inline references
		$script = $this->ready(implode('', $this->jquery_code_for_compile));
		if ($this->params["defer"]) {
			$script = $this->defer($script);
		}
		$script .= ";";

		$this->jquery_code_for_compile = $this->jquery_code_for_compile_at_last = [];
		if ($this->params["debug"] === false) {
			$script = $this->minify($script);
		}
		$output = ($script_tags === FALSE) ? $script : $this->inline($script);

		if ($view !== NULL) {
			$this->createScriptVariable($view, $view_var, $output);
		}

		return $output;
	}

	/**
	 * Clears the array of script events collected for output
	 *
	 * @return void
	 */
	public function clear_compile() {
		$this->jquery_code_for_compile = $this->jquery_code_for_compile_at_last = [];
	}

	public function getScript($offset = 0) {
		$code = \array_merge($this->jquery_code_for_compile, $this->jquery_code_for_compile_at_last);
		if ($offset > 0) {
			$code = \array_slice($code, $offset);
		}
		return \implode('', $code);
	}

	public function scriptCount() {
		return \count($this->jquery_code_for_compile);
	}

	/**
	 * Outputs a <script> tag
	 *
	 * @param string $script
	 * @param boolean $cdata
	 *        	If a CDATA section should be added
	 * @return string
	 */
	public function inline($script, $cdata = TRUE) {
		$str = $this->_open_script();
		$str .= ($cdata) ? "\n// <![CDATA[\n{$script}\n// ]]>\n" : "\n{$script}\n";
		$str .= $this->_close_script();
		return $str;
	}

	/**
	 * Can be passed a database result or associative array and returns a JSON formatted string
	 *
	 * @param mixed $result
	 *        	result set or array
	 * @param bool $match_array_type
	 *        	match array types (defaults to objects)
	 * @return string json formatted string
	 */
	public function generate_json($result = NULL, $match_array_type = FALSE) {
		// JSON data can optionally be passed to this function
		// either as a database result object or an array, or a user supplied array
		if (! is_null($result)) {
			if (is_object($result)) {
				$json_result = $result->result_array();
			} elseif (\is_array($result)) {
				$json_result = $result;
			} else {
				return $this->_prep_args($result);
			}
		} else {
			return 'null';
		}
		return $this->_create_json($json_result, $match_array_type);
	}

	private function _create_json($json_result, $match_array_type) {
		$json = array();
		$_is_assoc = TRUE;
		if (! is_array($json_result) && empty($json_result)) {
			show_error("Generate JSON Failed - Illegal key, value pair.");
		} elseif ($match_array_type) {
			$_is_assoc = JArray::isAssociative($json_result);
		}
		foreach ($json_result as $k => $v) {
			if ($_is_assoc) {
				$json[] = $this->_prep_args($k, TRUE) . ':' . $this->generate_json($v, $match_array_type);
			} else {
				$json[] = $this->generate_json($v, $match_array_type);
			}
		}
		$json = implode(',', $json);
		return $_is_assoc ? "{" . $json . "}" : "[" . $json . "]";
	}

	/**
	 * Ensures a standard json value and escapes values
	 *
	 * @param mixed $result
	 * @param boolean $is_key
	 * @return string
	 */
	public function _prep_args($result, $is_key = FALSE) {
		if (is_null($result)) {
			return 'null';
		} elseif (is_bool($result)) {
			return ($result === TRUE) ? 'true' : 'false';
		} elseif (is_string($result) || $is_key) {
			return '"' . str_replace(array(
				'\\',
				"\t",
				"\n",
				"\r",
				'"',
				'/'
			), array(
				'\\\\',
				'\\t',
				'\\n',
				"\\r",
				'\"',
				'\/'
			), $result) . '"';
		} elseif (is_scalar($result)) {
			return $result;
		}
	}

	/**
	 * Constructs the syntax for an event, and adds to into the array for compilation
	 *
	 * @param string $element
	 *        	The element to attach the event to
	 * @param string $js
	 *        	The code to execute
	 * @param string $event
	 *        	The event to pass
	 * @param boolean $preventDefault
	 *        	If set to true, the default action of the event will not be triggered.
	 * @param boolean $stopPropagation
	 *        	Prevents the event from bubbling up the DOM tree, preventing any parent handlers from being notified of the event.
	 * @param boolean $immediatly
	 *        	if true, execute this code immediatly
	 * @param
	 *        	boolean|string false for an event associated with existing DOM elements, or a string selector and then the event is dynamically associated to all elements created in this selector
	 * @return string
	 */
	public function _add_event($element, $js, $event, $preventDefault = false, $stopPropagation = false, $immediatly = true, $listenerOn = false) {
		if (\is_array($js)) {
			$js = implode("\n\t\t", $js);
		}
		if ($preventDefault === true) {
			$js = Javascript::$preventDefault . $js;
		}
		if ($stopPropagation === true) {
			$js = Javascript::$stopPropagation . $js;
		}
		if (\is_string($listenerOn)) {
			$event = "\n\t$(" . Javascript::prep_element($listenerOn) . ").on('{$event}'," . Javascript::prep_element($element) . ",function(event){\n\t\t{$js}\n\t});\n";
		} else {
			if (\array_search($event, $this->jquery_events) === false) {
				$event = "\n\t$(" . Javascript::prep_element($element) . ").bind('{$event}',function(event){\n\t\t{$js}\n\t});\n";
			} else {
				$event = "\n\t$(" . Javascript::prep_element($element) . ").{$event}(function(event){\n\t\t{$js}\n\t});\n";
			}
		}
		if ($immediatly)
			$this->jquery_code_for_compile[] = $event;
		return $event;
	}

	public function getInjected() {
		return $this->injected;
	}
}
