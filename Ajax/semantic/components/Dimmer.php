<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class Dimmer extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="dimmer";
	}

	public function setOn($value=false) {
		$this->params["on"]=$value;
	}

	public function setOpacity($value) {
		$this->params["opacity"]=$value;
	}
}
