<?php
namespace Ajax\semantic\components\validation;

use Ajax\JsUtils;
use Ajax\service\AjaxCall;

/**
 * Ajax\semantic\components\validation$AjaxRule
 * This class is part of phpmv-ui
 *
 * @author jc
 * @version 1.0.0
 *
 */
class AjaxRule extends CustomRule {

	private $ajaxCall;

	public function __construct($type, $url, $params, $jsCallback = null, $method = 'post', $parameters = [], $prompt = NULL, $value = NULL) {
		parent::__construct($type, $prompt, $value);
		$parameters = \array_merge([
			'async' => false,
			'url' => $url,
			'params' => $params,
			'hasLoader' => false,
			'jsCallback' => $jsCallback,
			'dataType' => 'json',
			'stopPropagation' => false,
			'preventDefault' => false,
			'responseElement' => null
		], $parameters);
		$this->ajaxCall = new AjaxCall($method, $parameters);
	}

	public function compile(JsUtils $js) {
		$js->exec(Rule::custom($this->getType(), "function(value,ruleValue){var result=true;" . $this->ajaxCall->compile($js) . "return result;}"), true);
	}
}