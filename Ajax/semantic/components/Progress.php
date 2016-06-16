<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class Progress extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="progress";
	}

	public function setOnChange($jsCode) {
		return $this->params["onChange"]=$jsCode;
	}

	public function setText($values) {
		return $this->params["text"]=$values;
	}
	
	// TODO other events implementation
}