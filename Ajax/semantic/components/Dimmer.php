<?php
namespace Ajax\semantic\components;

use Ajax\JsUtils;

class Dimmer extends SimpleSemExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName = "dimmer";
	}

	public function setOn($value = false) {
		$this->params["on"] = $value;
	}

	public function setOpacity($value) {
		$this->params["opacity"] = $value;
	}

	public function setClosable($value) {
		$this->params["closable"] = $value;
	}
}
