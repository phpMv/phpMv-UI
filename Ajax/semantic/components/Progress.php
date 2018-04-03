<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

class Progress extends SimpleSemExtComponent {

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
}
