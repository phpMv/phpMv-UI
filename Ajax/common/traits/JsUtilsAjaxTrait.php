<?php
namespace Ajax\common\traits;

use Ajax\service\AjaxTransition;
use Ajax\service\JString;
use Ajax\service\Javascript;

/**
 *
 * @author jc
 * @property array $jquery_code_for_compile
 * @property array $params
 */
trait JsUtilsAjaxTrait {

	protected $ajaxTransition;

	protected $ajaxLoader = "<div class=\"ui active centered inline text loader\">Loading</div>";

	abstract public function getUrl($url);

	abstract public function _add_event($element, $js, $event, $preventDefault = false, $stopPropagation = false, $immediatly = true, $listenerOn = false);

	abstract public function interval($jsCode, $time, $globalName = null, $immediatly = true);

	protected function _ajax($method, $url, $responseElement = '', $parameters = []) {
		if (isset($this->params['ajax'])) {
			extract($this->params['ajax']);
		}
		extract($parameters);

		$jsCallback = isset($jsCallback) ? $jsCallback : '';
		$retour = $this->_getAjaxUrl($url, $attr);
		$originalSelector = $responseElement;
		$responseElement = $this->_getResponseElement($responseElement);
		$retour .= "var self=this;\n";
		$before = isset($before) ? $before : "";
		$retour .= $before;
		if ($hasLoader === true && JString::isNotNull($responseElement)) {
			$this->addLoading($retour, $responseElement, $ajaxLoader);
		} elseif ($hasLoader === 'response') {
			$this->addResponseLoading($retour, $responseElement, $ajaxLoader);
		} elseif ($hasLoader === 'internal-x') {
			$this->addLoading($retour, '$(this).closest(".item, .step")', $ajaxLoader);
		} elseif ($hasLoader === 'internal') {
			$retour .= "\n$(this).addClass('loading');";
		} elseif (\is_string($hasLoader)) {
			$this->addLoading($retour, $hasLoader, $ajaxLoader);
		}
		$ajaxParameters = [
			"url" => "url",
			"method" => "'" . \strtoupper($method) . "'"
		];

		$ajaxParameters["async"] = ($async ? "true" : "false");

		if (isset($params)) {
			$ajaxParameters["data"] = self::_correctParams($params, $parameters);
		}
		if (isset($headers)) {
			$ajaxParameters["headers"] = $headers;
		}
		if ($csrf) {
			$csrf = (is_string($csrf)) ? $csrf : 'csrf-token';
			$parameters["beforeSend"] = "jqXHR.setRequestHeader('{$csrf}', $('meta[name=\"{$csrf}\"]').attr('content'));";
		}
		if (isset($partial)) {
			$ajaxParameters["xhr"] = "xhrProvider";
			$retour .= "var xhr = $.ajaxSettings.xhr();function xhrProvider() {return xhr;};xhr.onreadystatechange = function (e) { if (3==e.target.readyState){let response=e.target.responseText;" . $partial . ";}; };";
		} elseif (isset($upload)) {
			$ajaxParameters["xhr"] = "xhrProvider";
			$retour .= 'var xhr = $.ajaxSettings.xhr();function xhrProvider() {return xhr;};xhr.upload.addEventListener("progress", function(event) {if (event.lengthComputable) {' . $upload . '}}, false);';
		}
		$this->createAjaxParameters($ajaxParameters, $parameters);
		$retour .= "$.ajax({" . $this->implodeAjaxParameters($ajaxParameters) . "}).done(function( data, textStatus, jqXHR ) {\n";
		$retour .= $this->_getOnAjaxDone($responseElement, $jqueryDone, $ajaxTransition, $jsCallback, ($historize ? $originalSelector : null)) . "})";
		if (isset($error)) {
			$retour .= '.fail(function( jqXHR, textStatus, errorThrown ){' . $error . '})';
		}
		$retour .= '.always(function( dataOrjqXHR, textStatus, jqXHROrerrorThrown ) {' . ($always ?? '') . $this->removeLoader($hasLoader) . '})';
		$retour .= ";\n";
		$retour = $this->_addJsCondition($jsCondition, $retour);
		if ($immediatly) {
			$this->jquery_code_for_compile[] = $retour;
		}
		return $retour;
	}

	protected function createAjaxParameters(&$original, $parameters) {
		$validParameters = [
			"contentType" => "%value%",
			"dataType" => "'%value%'",
			"beforeSend" => "function(jqXHR,settings){%value%}",
			"complete" => "function(jqXHR){%value%}",
			"processData" => "%value%"
		];
		foreach ($validParameters as $param => $mask) {
			if (isset($parameters[$param])) {
				$original[$param] = \str_replace("%value%", $parameters[$param], $mask);
			}
		}
	}

	protected function implodeAjaxParameters($ajaxParameters) {
		$s = '';
		foreach ($ajaxParameters as $k => $v) {
			if ($s !== '') {
				$s .= ',';
			}
			if (is_array($v)) {
				$s .= "'{$k}':{" . self::implodeAjaxParameters($v) . "}";
			} else {
				$s .= "'{$k}':{$v}";
			}
		}
		return $s;
	}

	protected function _addJsCondition($jsCondition, $jsSource) {
		if (isset($jsCondition)) {
			return "if(" . $jsCondition . "){\n" . $jsSource . "\n}";
		}
		return $jsSource;
	}

	protected function _getAjaxUrl($url, $attr) {
		$url = $this->_correctAjaxUrl($url);
		$retour = "url='" . $url . "';";
		$slash = "/";
		if (JString::endswith($url, "/") === true) {
			$slash = "";
		}

		if (JString::isNotNull($attr)) {
			if ($attr === "value") {
				$retour .= "url=url+'" . $slash . "'+$(this).val();\n";
			} elseif ($attr === "html") {
				$retour .= "url=url+'" . $slash . "'+$(this).html();\n";
			} elseif (\substr($attr, 0, 3) === "js:") {
				$retour .= "url=url+'" . $slash . "'+" . \substr($attr, 3) . ";\n";
			} elseif ($attr !== null && $attr !== "") {
				$retour .= "let elmUrl=$(this).attr('" . $attr . "')||'';";
				$retour .= "url=(!/^((http|https|ftp):\/\/)/.test(elmUrl))?url+'" . $slash . "'+elmUrl:elmUrl;\n";
			}
		}
		return $retour;
	}

	protected function onPopstate() {
		return "window.onpopstate = function(e){if(e.state){var target=e.state.jqueryDone;$(e.state.selector)[target](e.state.html);}};";
	}

	protected function autoActiveLinks($previousURL = "window.location.href") {
		$result = "\nfunction getHref(url) { return \$('a').filter(function(){return \$(this).prop('href') == url; });}";
		$result .= "\nvar myurl={$previousURL};if(window._previousURL) getHref(window._previousURL).removeClass('active');getHref(myurl).addClass('active');window._previousURL=myurl;";
		return $result;
	}

	protected function _getOnAjaxDone($responseElement, $jqueryDone, $ajaxTransition, $jsCallback, $history = null) {
		$retour = "";
		$call = null;
		if (JString::isNotNull($responseElement)) {
			if (isset($ajaxTransition)) {
				$call = $this->setAjaxDataCall($ajaxTransition);
			} elseif (isset($this->ajaxTransition)) {
				$call = $this->ajaxTransition;
			}
			if (\is_callable($call))
				$retour = "\t" . $call($responseElement, $jqueryDone) . ";\n";
			else
				$retour = "\t{$responseElement}.{$jqueryDone}( data );\n";
		}
		if (isset($history)) {
			if ($this->params["autoActiveLinks"]) {
				$retour .= $this->autoActiveLinks("url");
			}
			$retour .= "\nwindow.history.pushState({'html':data,'selector':" . Javascript::prep_value($history) . ",'jqueryDone':'{$jqueryDone}'},'', url);";
		}
		$retour .= "\t" . $jsCallback . "\n";
		return $retour;
	}

	protected function removeLoader($hasLoader) {
		if ($hasLoader === true) {
			return "\n$('body').find('.ajax-loader').remove();";
		}
		if ($hasLoader === 'internal') {
			return "\n$(self).removeClass('loading');";
		}
		if ($hasLoader === 'internal-x') {
			return "\n$(self).children('.ajax-loader').remove();";
		}
		return "\n$('body').find('.loading').removeClass('loading');";
	}

	protected function _getResponseElement($responseElement) {
		if (JString::isNotNull($responseElement)) {
			$responseElement = Javascript::prep_jquery_selector($responseElement);
		}
		return $responseElement;
	}

	protected function _getFormElement($formElement) {
		if (JString::isNotNull($formElement)) {
			$formElement = Javascript::prep_value($formElement);
		}
		return $formElement;
	}

	protected function _correctAjaxUrl($url) {
		if ($url !== "/" && JString::endsWith($url, "/") === true)
			$url = substr($url, 0, strlen($url) - 1);
		if (strncmp($url, 'http://', 7) != 0 && strncmp($url, 'https://', 8) != 0) {
			$url = $this->getUrl($url);
		}
		return $url;
	}

	public static function _correctParams($params, $ajaxParameters = []) {
		if (JString::isNull($params)) {
			return "";
		}
		if (\preg_match("@^\{.*?\}$@", $params)) {
			if (! isset($ajaxParameters['contentType']) || ! JString::contains($ajaxParameters['contentType'], 'json')) {
				return '$.param(' . $params . ')';
			} else {
				return 'JSON.stringify(' . $params . ')';
			}
		}
		return $params;
	}

	public static function _implodeParams($parameters) {
		$allParameters = [];
		foreach ($parameters as $params) {
			if (isset($params))
				$allParameters[] = self::_correctParams($params);
		}
		return \implode("+'&'+", $allParameters);
	}

	protected function addLoading(&$retour, $responseElement, $ajaxLoader = null) {
		if (! isset($ajaxLoader)) {
			$ajaxLoader = $this->ajaxLoader;
		}
		$loading_notifier = '<div class="ajax-loader ui active inverted dimmer">' . $ajaxLoader . '</div>';
		$retour .= "\t\t{$responseElement}.append('{$loading_notifier}');\n";
	}

	protected function addResponseLoading(&$retour, $responseElement, $ajaxLoader = null) {
		if (! isset($ajaxLoader)) {
			$ajaxLoader = $this->ajaxLoader;
		}
		$loading_notifier = '<div class="ajax-loader">' . $ajaxLoader . '</div>';
		$retour .= "{$responseElement}.empty();\n";
		$retour .= "\t\t{$responseElement}.prepend('{$loading_notifier}');\n";
	}

	protected function setAjaxDataCall($params) {
		$result = null;
		if (! \is_callable($params)) {
			$result = function ($responseElement, $jqueryDone = 'html') use ($params) {
				return AjaxTransition::{$params}($responseElement, $jqueryDone);
			};
		}
		return $result;
	}

	protected function setDefaultParameters(&$parameters, $default) {
		foreach ($default as $k => $v) {
			if (! isset($parameters[$k]))
				$parameters[$k] = $v;
		}
	}

	public function setAjaxLoader($loader) {
		$this->ajaxLoader = $loader;
	}

	/**
	 * Performs an ajax GET request
	 *
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 */
	private function _get($url, $responseElement = '', $parameters = []) {
		return $this->_ajax('get', $url, $responseElement, $parameters);
	}

	/**
	 * Performs an ajax GET request
	 *
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 */
	public function get($url, $responseElement = '', $parameters = []) {
		$parameters['immediatly'] = true;
		return $this->_get($url, $responseElement, $parameters);
	}

	/**
	 * Performs an ajax request
	 *
	 * @param string $method
	 *        	The http method (get, post, delete, put, head)
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 */
	public function ajax($method, $url, $responseElement = '', $parameters = []) {
		$parameters['immediatly'] = true;
		return $this->_ajax($method, $url, $responseElement, $parameters);
	}

	/**
	 * Executes an ajax query at regular intervals
	 *
	 * @param string $method
	 *        	The http method (post, get...)
	 * @param string $url
	 *        	The url of the request
	 * @param int $interval
	 *        	The interval in milliseconds
	 * @param string $globalName
	 *        	The interval name, for clear it
	 * @param string $responseElement
	 * @param array $parameters
	 *        	The ajax parameters, default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 * @param
	 *        	$immediatly
	 * @return string
	 */
	public function ajaxInterval($method, $url, $interval, $globalName = null, $responseElement = '', $parameters = [], $immediatly = true) {
		return $this->interval($this->ajaxDeferred($method, $url, $responseElement, $parameters), $interval, $globalName, $immediatly);
	}

	/**
	 * Performs a deferred ajax request
	 *
	 * @param string $method
	 *        	The http method (get, post, delete, put, head)
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 */
	public function ajaxDeferred($method, $url, $responseElement = '', $parameters = []) {
		$parameters['immediatly'] = false;
		return $this->_ajax($method, $url, $responseElement, $parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON data types by assigning DOM elements with the same name
	 *
	 * @param string $url
	 *        	the request url
	 * @param string $method
	 *        	Method used
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>"document","jsCondition"=>NULL,"headers"=>null,"immediatly"=>false,"before"=>null)
	 */
	private function _json($url, $method = "get", $parameters = []) {
		$parameters = \array_merge($parameters, [
			"hasLoader" => false
		]);
		$jsCallback = isset($parameters['jsCallback']) ? $parameters['jsCallback'] : "";
		$context = isset($parameters['context']) ? $parameters['context'] : "document";
		$retour = "\tdata=($.isPlainObject(data))?data:JSON.parse(data);\t" . $jsCallback . ";" . "\n\tfor(var key in data){" . "if($('#'+key," . $context . ").length){ if($('#'+key," . $context . ").is('[value]')) { $('#'+key," . $context . ").val(data[key]);} else { $('#'+key," . $context . ").html(data[key]); }}};\n";
		$retour .= "\t$(document).trigger('jsonReady',[data]);\n";
		$parameters["jsCallback"] = $retour;
		return $this->_ajax($method, $url, null, $parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON data types by assigning DOM elements with the same name
	 *
	 * @param string $url
	 *        	the request url
	 * @param string $method
	 *        	Method used
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>"document","jsCondition"=>NULL,"headers"=>null,"immediatly"=>false,"before"=>null)
	 */
	public function json($url, $method = "get", $parameters = []) {
		return $this->_json($url, $method, $parameters);
	}

	/**
	 * Makes an ajax request and receives the JSON data types by assigning DOM elements with the same name when $event fired on $element
	 *
	 * @param string $element
	 * @param string $event
	 * @param string $url
	 *        	the request address
	 * @param string $method
	 *        	default get
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","immediatly"=>true,"before"=>null,"listenerOn"=>false)
	 */
	public function jsonOn($event, $element, $url, $method = 'get', $parameters = array()) {
		$this->setDefaultParameters($parameters, [
			'preventDefault' => true,
			'stopPropagation' => true,
			'immediatly' => true,
			'listenerOn' => false
		]);
		return $this->_add_event($element, $this->jsonDeferred($url, $method, $parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"], $parameters["immediatly"], $parameters['listenerOn']);
	}

	/**
	 * Prepares an ajax request delayed and receives the JSON data types by assigning DOM elements with the same name
	 *
	 * @param string $url
	 *        	the request url
	 * @param string $method
	 *        	Method used
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>"document","jsCondition"=>NULL,"headers"=>null,"immediatly"=>false,"before"=>null)
	 */
	public function jsonDeferred($url, $method = 'get', $parameters = []) {
		$parameters['immediatly'] = false;
		return $this->_json($url, $method, $parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name
	 *
	 * @param string $maskSelector
	 * @param string $url
	 *        	the request url
	 * @param string $method
	 *        	Method used, default : get
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>null,"jsCondition"=>NULL,"headers"=>null,"immediatly"=>false,"rowClass"=>"_json","before"=>null)
	 */
	private function _jsonArray($maskSelector, $url, $method = 'get', $parameters = []) {
		$parameters = \array_merge($parameters, [
			"hasLoader" => false
		]);
		$rowClass = isset($parameters['rowClass']) ? $parameters['rowClass'] : "_json";
		$jsCallback = isset($parameters['jsCallback']) ? $parameters['jsCallback'] : "";
		$context = isset($parameters['context']) ? $parameters['context'] : null;
		if ($context === null) {
			$parent = "$('" . $maskSelector . "').parent()";
			$newElm = "$('#'+newId)";
		} else {
			$parent = $context;
			$newElm = $context . ".find('#'+newId)";
		}
		$appendTo = "\t\tnewElm.appendTo(" . $parent . ");\n";
		$retour = $parent . ".find('.{$rowClass}').remove();";
		$retour .= "\tdata=($.isPlainObject(data)||$.isArray(data))?data:JSON.parse(data);\n$.each(data, function(index, value) {\n" . "\tvar created=false;var maskElm=$('" . $maskSelector . "').first();maskElm.hide();" . "\tvar newId=(maskElm.attr('id') || 'mask')+'-'+index;" . "\tvar newElm=" . $newElm . ";\n" . "\tif(!newElm.length){\n" . "\t\tnewElm=maskElm.clone();
		newElm.attr('id',newId);\n;newElm.addClass('{$rowClass}').removeClass('_jsonArrayModel');\nnewElm.find('[id]').each(function(){ var newId=$(this).attr('id')+'-'+index;$(this).attr('id',newId).removeClass('_jsonArrayChecked');});\n";
		$retour .= $appendTo;
		$retour .= "\t}\n" . "\tfor(var key in value){\n" . "\t\t\tvar html = $('<div />').append($(newElm).clone()).html();\n" . "\t\t\tif(html.indexOf('__'+key+'__')>-1){\n" . "\t\t\t\tcontent=$(html.split('__'+key+'__').join(value[key]));\n" . "\t\t\t\t$(newElm).replaceWith(content);newElm=content;\n" . "\t\t\t}\n" . "\t\tvar sel='[data-id=\"'+key+'\"]';if($(sel,newElm).length){\n" . "\t\t\tvar selElm=$(sel,newElm);\n" . "\t\t\t if(selElm.is('[value]')) { selElm.attr('value',value[key]);selElm.val(value[key]);} else { selElm.html(value[key]); }\n" . "\t\t}\n" . "}\n" . "\t$(newElm).show(true);" . "\n" . "\t$(newElm).removeClass('hide');" . "});\n";
		$retour .= "\t$(document).trigger('jsonReady',[data]);\n";
		$retour .= "\t" . $jsCallback;
		$parameters["jsCallback"] = $retour;
		return $this->_ajax($method, $url, null, $parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name
	 *
	 * @param string $maskSelector
	 * @param string $url
	 *        	the request url
	 * @param string $method
	 *        	Method used, default : get
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>null,"jsCondition"=>NULL,"headers"=>null,"immediatly"=>false,"rowClass"=>"_json","before"=>null)
	 */
	public function jsonArray($maskSelector, $url, $method = 'get', $parameters = []) {
		return $this->_jsonArray($maskSelector, $url, $method, $parameters);
	}

	/**
	 * Peforms an ajax request delayed and receives a JSON array data types by copying and assigning them to the DOM elements with the same name
	 *
	 * @param string $maskSelector
	 * @param string $url
	 *        	the request url
	 * @param string $method
	 *        	Method used, default : get
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","context"=>null,"jsCondition"=>NULL,"headers"=>null,"rowClass"=>"_json","before"=>null)
	 */
	public function jsonArrayDeferred($maskSelector, $url, $method = 'get', $parameters = []) {
		$parameters['immediatly'] = false;
		return $this->jsonArray($maskSelector, $url, $method, $parameters);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name when $event fired on $element
	 *
	 * @param string $element
	 * @param string $event
	 * @param string $url
	 *        	the request url
	 * @param string $method
	 *        	Method used, default : get
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","rowClass"=>"_json","immediatly"=>true,"before"=>null,"listenerOn"=>false)
	 */
	public function jsonArrayOn($event, $element, $maskSelector, $url, $method = 'get', $parameters = array()) {
		$this->setDefaultParameters($parameters, [
			'preventDefault' => true,
			'stopPropagation' => true,
			'immediatly' => true,
			'listenerOn' => false
		]);
		return $this->_add_event($element, $this->jsonArrayDeferred($maskSelector, $url, $method, $parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"], $parameters["immediatly"], $parameters['listenerOn']);
	}

	/**
	 * Prepares a Get ajax request
	 * for using on an event
	 *
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 */
	public function getDeferred($url, $responseElement = "", $parameters = []) {
		$parameters['immediatly'] = false;
		return $this->_get($url, $responseElement, $parameters);
	}

	/**
	 * Performs a get to $url on the event $event on $element
	 * and display it in $responseElement
	 *
	 * @param string $event
	 *        	the event
	 * @param string $element
	 *        	the element on which event is observed
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	The selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false,"before"=>null,"listenerOn"=>false)
	 */
	public function getOn($event, $element, $url, $responseElement = "", $parameters = array()) {
		$parameters['method'] = 'get';
		return $this->ajaxOn($event, $element, $url, $responseElement, $parameters);
	}

	/**
	 * Performs an ajax request to $url on the event $event on $element
	 * and display it in $responseElement
	 *
	 * @param string $event
	 *        	the event observed
	 * @param string $element
	 *        	the element on which event is observed
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	The selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("method"=>"get","preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null,"listenerOn"=>false)
	 */
	public function ajaxOn($event, $element, $url, $responseElement = '', $parameters = array()) {
		$this->setDefaultParameters($parameters, [
			'preventDefault' => true,
			'stopPropagation' => true,
			'immediatly' => true,
			'method' => 'get',
			'listenerOn' => false
		]);
		return $this->_add_event($element, $this->ajaxDeferred($parameters['method'], $url, $responseElement, $parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"], $parameters["immediatly"], $parameters['listenerOn']);
	}

	/**
	 * Performs a get to $url on the click event on $element
	 * and display it in $responseElement
	 *
	 * @param string $element
	 *        	the element on which event is observed
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	The selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("method"=>"get","preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null,"listenerOn"=>false)
	 */
	public function ajaxOnClick($element, $url, $responseElement = '', $parameters = array()) {
		return $this->ajaxOn('click', $element, $url, $responseElement, $parameters);
	}

	/**
	 * Performs a get to $url on the click event on $element
	 * and display it in $responseElement
	 *
	 * @param string $element
	 *        	the element on which click is observed
	 * @param string $url
	 *        	The url of the request
	 * @param string $responseElement
	 *        	The selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null,"listenerOn"=>false)
	 */
	public function getOnClick($element, $url, $responseElement = '', $parameters = array()) {
		return $this->getOn('click', $element, $url, $responseElement, $parameters);
	}

	/**
	 * Uses an hyperlink to make an ajax get request
	 *
	 * @param string $element
	 *        	an hyperlink selector
	 * @param string $responseElement
	 *        	the target of the ajax request (data-target attribute of the element is used if responseElement is omited)
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"href","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null,"historize"=>true,"before"=>null,"listenerOn"=>false)
	 * @return $this
	 */
	public function getHref($element, $responseElement = "", $parameters = array()) {
		$parameters['attr'] = 'href';
		if (JString::isNull($responseElement)) {
			$responseElement = '%$(self).attr("data-target")%';
		} else {
			$responseElement = '%$(self).attr("data-target") || "' . $responseElement . '"%';
		}
		if (! isset($parameters['historize'])) {
			$parameters['historize'] = true;
		}
		if (! isset($parameters['jsCallback'])) {
			$parameters['jsCallback'] = 'var event = jQuery.Event( "getHref" );event.url = url;$(self).trigger(event);';
		}
		return $this->getOnClick($element, "", $responseElement, $parameters);
	}

	/**
	 * Uses an hyperlink to make an ajax get request
	 *
	 * @param string $element
	 *        	an hyperlink selector
	 * @param string $responseElement
	 *        	the target of the ajax request (data-target attribute of the element is used if responseElement is omited)
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"href","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null,"historize"=>true,"before"=>null,"listenerOn"=>false)
	 * @return $this
	 */
	public function postHref($element, $responseElement = "", $parameters = array()) {
		$parameters['attr'] = 'href';
		if (JString::isNull($responseElement)) {
			$responseElement = '%$(this).attr("data-target")%';
		} else {
			$responseElement = '%$(self).attr("data-target") || "' . $responseElement . '"%';
		}
		if (! isset($parameters['historize'])) {
			$parameters['historize'] = true;
		}
		return $this->postOnClick($element, '', '{}', $responseElement, $parameters);
	}

	/**
	 * Uses a form action to make an ajax post request
	 *
	 * @param string $element
	 *        	a form selector
	 * @param string $responseElement
	 *        	the target of the ajax request (data-target attribute of the element is used if responseElement is omited)
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"href","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","jsCondition"=>NULL,"headers"=>null,"historize"=>true,"before"=>null,"listenerOn"=>false)
	 * @return $this
	 */
	public function postFormAction($element, $responseElement = "", $parameters = array()) {
		$parameters['attr'] = 'action';
		if (JString::isNull($responseElement)) {
			$responseElement = '%$(self).attr("data-target")%';
		} else {
			$responseElement = '%$(self).attr("data-target") || "' . $responseElement . '"%';
		}
		$formId = '%$(this).attr("id")%';
		if (! isset($parameters['historize'])) {
			$parameters['historize'] = true;
		}
		$parameters['preventDefault'] = true;
		if (! isset($parameters['hasLoader'])) {
			$parameters['hasLoader'] = '$(self).find("button, input[type=submit], input[type=button]")';
		}
		if (! isset($parameters['jsCallback'])) {
			$parameters['jsCallback'] = 'var event = jQuery.Event( "postFormAction" );event.params = Object.fromEntries(new URLSearchParams(params));$(self).trigger(event);';
		}
		return $this->postFormOn('submit', $element, '', $formId, $responseElement, $parameters);
	}

	private function _post($url, $params = '{}', $responseElement = '', $parameters = []) {
		$parameters['params'] = $params;
		return $this->_ajax('POST', $url, $responseElement, $parameters);
	}

	/**
	 * Makes an ajax post
	 *
	 * @param string $url
	 *        	the request url
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param string $params
	 *        	JSON parameters
	 * @param array $parameters
	 *        	default : array("jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 */
	public function post($url, $params = "{}", $responseElement = "", $parameters = []) {
		$parameters['immediatly'] = true;
		return $this->_post($url, $params, $responseElement, $parameters);
	}

	/**
	 * Prepares a delayed ajax POST
	 * to use on an event
	 *
	 * @param string $url
	 *        	the request url
	 * @param string $params
	 *        	JSON parameters
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 */
	public function postDeferred($url, $params = "{}", $responseElement = "", $parameters = []) {
		$parameters['immediatly'] = false;
		return $this->_post($url, $params, $responseElement, $parameters);
	}

	/**
	 * Performs a post to $url on the event $event fired on $element and pass the parameters $params
	 * Display the result in $responseElement
	 *
	 * @param string $event
	 * @param string $element
	 * @param string $url
	 *        	The url of the request
	 * @param string $params
	 *        	The parameters to send
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null,"listenerOn"=>false)
	 */
	public function postOn($event, $element, $url, $params = "{}", $responseElement = "", $parameters = array()) {
		$parameters['method'] = 'post';
		$parameters['params'] = $params;
		return $this->ajaxOn($event, $element, $url, $responseElement, $parameters);
	}

	/**
	 * Performs a post to $url on the click event fired on $element and pass the parameters $params
	 * Display the result in $responseElement
	 *
	 * @param string $element
	 * @param string $url
	 *        	The url of the request
	 * @param string $params
	 *        	The parameters to send
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null,"before"=>null,"listenerOn"=>false)
	 */
	public function postOnClick($element, $url, $params = '{}', $responseElement = '', $parameters = array()) {
		return $this->postOn('click', $element, $url, $params, $responseElement, $parameters);
	}

	private function _postForm($url, $form, $responseElement, $parameters = []) {
		if (isset($this->params['ajax'])) {
			extract($this->params['ajax']);
		}
		$params = '{}';
		$validation = false;
		\extract($parameters);
		$async = ($async) ? 'true' : 'false';
		$jsCallback = isset($jsCallback) ? $jsCallback : "";
		$retour = $this->_getAjaxUrl($url, $attr);
		$form = $this->_getFormElement($form);
		$retour .= "\n$('#'+" . $form . ").trigger('ajaxSubmit');";
		if (! isset($contentType) || $contentType != 'false') {
			$retour .= "\nvar params=$('#'+" . $form . ").serialize();\n";
			if (isset($params)) {
				$retour .= "params+='&'+" . self::_correctParams($params) . ";\n";
			}
		} else {
			$retour .= "\nvar params=new FormData($('#'+" . $form . ")[0]);\n";
		}
		$responseElement = $this->_getResponseElement($responseElement);
		$retour .= "var self=this;\n";
		$before = isset($before) ? $before : "";
		$retour .= $before;
		if ($hasLoader === true) {
			$this->addLoading($retour, $responseElement, $ajaxLoader);
		} elseif ($hasLoader === 'response') {
			$this->addResponseLoading($retour, $responseElement, $ajaxLoader);
		} elseif ($hasLoader === 'internal-x') {
			$this->addLoading($retour, '$(this).closest(".item, .step")', $ajaxLoader);
		} elseif ($hasLoader === 'internal') {
			$retour .= "\n$(this).addClass('loading');";
		} elseif (\is_string($hasLoader)) {
			$retour .= "\n$hasLoader.addClass('loading');";
		}
		$ajaxParameters = [
			"url" => "url",
			"method" => "'POST'",
			"data" => "params",
			"async" => $async
		];
		if (isset($headers)) {
			$ajaxParameters["headers"] = $headers;
		}
		if (isset($partial)) {
			$ajaxParameters["xhr"] = "xhrProvider";
			$retour .= "var xhr = $.ajaxSettings.xhr();function xhrProvider() {return xhr;};xhr.onreadystatechange = function (e) { if (3==e.target.readyState){let response=e.target.responseText;" . $partial . ";}; };";
		}
		$this->createAjaxParameters($ajaxParameters, $parameters);
		$retour .= "$.ajax({" . $this->implodeAjaxParameters($ajaxParameters) . "}).done(function( data ) {\n";
		$retour .= $this->_getOnAjaxDone($responseElement, $jqueryDone, $ajaxTransition, $jsCallback) . "})";
		if (isset($error)) {
			$retour .= '.fail(function( jqXHR, textStatus, errorThrown ){' . $error . '})';
		}
		$retour .= '.always(function( dataOrjqXHR, textStatus, jqXHROrerrorThrown ) {' . ($always ?? '') . $this->removeLoader($hasLoader) . '})';
		$retour .= ";\n";
		if ($validation) {
			$retour = "$('#'+" . $form . ").validate({submitHandler: function(form) {
			" . $retour . "
			}});\n";
			$retour .= "$('#'+" . $form . ").submit();\n";
		}
		$retour = $this->_addJsCondition($jsCondition, $retour);
		if ($immediatly)
			$this->jquery_code_for_compile[] = $retour;
		return $retour;
	}

	/**
	 * Performs a post form with ajax
	 *
	 * @param string $url
	 *        	The url of the request
	 * @param string $form
	 *        	The form HTML id
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 */
	public function postForm($url, $form, $responseElement, $parameters = []) {
		$parameters['immediatly'] = true;
		return $this->_postForm($url, $form, $responseElement, $parameters);
	}

	/**
	 * Performs a delayed post form with ajax
	 * For use on an event
	 *
	 * @param string $url
	 *        	The url of the request
	 * @param string $form
	 *        	The form HTML id
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null,"historize"=>false,"before"=>null)
	 */
	public function postFormDeferred($url, $form, $responseElement, $parameters = []) {
		$parameters['immediatly'] = false;
		return $this->_postForm($url, $form, $responseElement, $parameters);
	}

	/**
	 * Performs a post form with ajax in response to an event $event on $element
	 * display the result in $responseElement
	 *
	 * @param string $event
	 * @param string $element
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false,"before"=>null,"listenerOn"=>false)
	 */
	public function postFormOn($event, $element, $url, $form, $responseElement = "", $parameters = array()) {
		$this->setDefaultParameters($parameters, [
			'preventDefault' => true,
			'stopPropagation' => true,
			'immediatly' => true,
			'listenerOn' => false
		]);
		return $this->_add_event($element, $this->postFormDeferred($url, $form, $responseElement, $parameters), $event, $parameters["preventDefault"], $parameters["stopPropagation"], $parameters["immediatly"], $parameters['listenerOn']);
	}

	/**
	 * Performs a post form with ajax in response to the click event on $element
	 * display the result in $responseElement
	 *
	 * @param string $element
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement
	 *        	selector of the HTML element displaying the answer
	 * @param array $parameters
	 *        	default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"ajaxLoader"=>null,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>null,"headers"=>null,"historize"=>false,"before"=>null,"listenerOn"=>false)
	 */
	public function postFormOnClick($element, $url, $form, $responseElement = "", $parameters = array()) {
		return $this->postFormOn("click", $element, $url, $form, $responseElement, $parameters);
	}

	public function addCsrf($name = 'csrf-token') {
		return "
		$.ajaxSetup({
			beforeSend: function(xhr, settings) {
				let csrfSafeMethod=function(method) { return (/^(GET|HEAD|OPTIONS)$/.test(method));};
				if (!csrfSafeMethod(settings.type) && !this.crossDomain) {
					xhr.setRequestHeader('{$name}', $('meta[name=\"{$name}\"]').attr('content'));
				}
			}
		});";
	}
}
