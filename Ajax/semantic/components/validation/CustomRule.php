<?php
namespace Ajax\semantic\components\validation;

use Ajax\JsUtils;

/**
 * Ajax\semantic\components\validation$CustomRule
 * This class is part of phpmv-ui
 *
 * @author jc
 * @version 1.0.0
 *
 */
class CustomRule extends Rule {

	protected $jsFunction;

	public function __construct($type, $jsFunction, $prompt = NULL, $value = NULL) {
		parent::__construct($type, $prompt, $value);
		$this->jsFunction = $jsFunction;
	}

	public function compile(JsUtils $js) {
		$js->exec(Rule::custom($this->getType(), $this->jsFunction), true);
	}
}