<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

class Tab extends SimpleSemExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="tab";
	}
	public function setDebug($value=true) {
		return $this->setParam("debug", $value);
	}
}
