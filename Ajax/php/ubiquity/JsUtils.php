<?php
namespace Ajax\php\ubiquity;

use Ajax\semantic\components\validation\Rule;
use Ubiquity\controllers\Startup;
use Ubiquity\utils\http\URequest;
use Ubiquity\security\csp\ContentSecurityManager;
use Ubiquity\utils\http\UResponse;

class JsUtils extends \Ajax\JsUtils {

	/**
	 * Outputs an opening <script>
	 *
	 * @param string $src
	 * @return string
	 */
	protected function _open_script($src = '') {
		$str = '<script ';
		if (($this->params['csp'] ?? false) === 'nonce' && ContentSecurityManager::isStarted()) {
			$nonce = ContentSecurityManager::getNonce('jsUtils');
			$str .= ' nonce="' . $nonce . '" ';
		}
		$str .= ($src == '') ? '>' : ' src="' . $src . '">';
		return $str;
	}

	public function inline($script, $cdata = true) {
		if (($this->params['csp'] ?? false) === 'hash' && ContentSecurityManager::isStarted()) {
			$script = ($cdata) ? "\n// <![CDATA[\n{$script}\n// ]]>\n" : "\n{$script}\n";
			ContentSecurityManager::getHash('jsUtils', $script);
		}
		return $this->_open_script() . $script . $this->_close_script();
	}

	public function getUrl($url) {
		return URequest::getUrl($url);
	}

	public function addViewElement($identifier, $content, &$view) {
		$controls = $view->getVar("q");
		if (isset($controls) === false) {
			$controls = array();
		}
		$controls[$identifier] = $content;
		$view->setVar("q", $controls);
	}

	public function createScriptVariable(&$view, $view_var, $output) {
		$view->setVar($view_var, $output);
	}

	public function forward($initialController, $controller, $action, $params = array()) {
		return $initialController->forward($controller, $action, $params, true, true, true);
	}

	public function renderContent($initialControllerInstance, $viewName, $params = NULL) {
		return $initialControllerInstance->loadView($viewName, $params, true);
	}

	public function fromDispatcher($dispatcher) {
		return Startup::$urlParts;
	}

	/**
	 * Performs jQuery compilation and displays a view
	 *
	 * @param string $viewName
	 * @param mixed $parameters
	 *        	Variable or associative array to pass to the view <br> If a variable is passed, it will have the name <b> $ data </ b> in the view, <br>
	 *        	If an associative array is passed, the view retrieves variables from the table's key names
	 * @param boolean $asString
	 *        	If true, the view is not displayed but returned as a string (usable in a variable)
	 */
	public function renderView($viewName, $parameters = [], $asString = false) {
		if (isset($this->injected)) {
			$view = $this->injected->getView();
			$this->compile($view);
			return $this->injected->loadView($viewName, $parameters, $asString);
		}
		throw new \Exception(get_class() . " instance is not properly instancied : you omitted the second parameter \$controller!");
	}

	/**
	 * Performs jQuery compilation and displays the default view
	 *
	 * @param mixed $parameters
	 *        	Variable or associative array to pass to the view <br> If a variable is passed, it will have the name <b> $ data </ b> in the view, <br>
	 *        	If an associative array is passed, the view retrieves variables from the table's key names
	 * @param boolean $asString
	 *        	If true, the view is not displayed but returned as a string (usable in a variable)
	 */
	public function renderDefaultView($parameters = [], $asString = false) {
		return $this->renderView($this->injected->getDefaultViewName(), $parameters, $asString);
	}

	/**
	 * Loads and eventually executes a jsFile with php parameters, using the default template engine.
	 *
	 * @param string $jsFile
	 * @param array $parameters
	 * @param boolean $immediatly
	 * @return string|null
	 * @throws \Exception
	 */
	public function execJSFromFile($jsFile, $parameters = [], $immediatly = true) {
		if (isset($this->injected)) {
			$view = $this->injected->getView();
			if (isset($parameters))
				$view->setVars($parameters);
			$js = $view->render($jsFile . '.js', true);
			return $this->exec($js, $immediatly);
		}
		throw new \Exception(get_class() . " instance is not properly instancied : you omitted the second parameter \$controller!");
	}

	/**
	 * Returns an instance of JsUtils initialized with Semantic (for di injection)
	 *
	 * @param \Ubiquity\controllers\Controller $controller
	 * @param array $options
	 * @return \Ajax\php\ubiquity\JsUtils
	 */
	public static function diSemantic($controller, $options = [
		'defer' => true,
		'gc' => true
	]) {
		$jquery = new JsUtils($options, $controller);
		$jquery->semantic(new \Ajax\Semantic());
		$jquery->setAjaxLoader("<div class=\"ui active centered inline text loader\">Loading</div>");
		return $jquery;
	}

	/**
	 * Returns an instance of JsUtils initialized with Bootstrap (for di injection)
	 *
	 * @param \Ubiquity\controllers\Controller $controller
	 * @param array $options
	 * @return \Ajax\php\ubiquity\JsUtils
	 */
	public static function diBootstrap($controller, $options = [
		'defer' => true,
		'gc' => true
	]) {
		$jquery = new JsUtils($options, $controller);
		$jquery->bootstrap(new \Ajax\Bootstrap());
		$jquery->setAjaxLoader("<div class=\"d-flex justify-content-center\"><div class=\"spinner-border\" role=\"status\"><span class=\"sr-only\">Loading...</span></div></div>");
		return $jquery;
	}

	/**
	 * Adds a new semantic validation ajax rule.
	 * To use with @checkValidationRule in controllers.
	 * @param string $ruleName
	 * @param string $checkUrl
	 * @param string $params
	 * @param string $method
	 * @return void
	 */
	public function ajaxValidationRule(string $ruleName,string $checkUrl,string $params='{_value:value}',string $method='post'): void {
		$this->exec(Rule::ajax($this, $ruleName, $checkUrl, $params, 'result=data.result;', $method), true);
	}

	/**
	 * Checks a validation rule declared with @ajaxValidationRule.
	 * @param $callback a callback using the value to validate : function($value){}
	 * @return void
	 */
	public function checkValidationRule($callback): void {
		if (URequest::isPost()) {
			$result = [];
			UResponse::asJSON();
			$value = $_POST['_value'];
			$result['result'] = $callback($value);
			echo \json_encode($result);
		}
	}
}
