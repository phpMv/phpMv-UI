<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class Accordion extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="accordion";
	}

	/**
	 *
	 * @param string $value default : click
	 * @return $this
	 */
	public function setOn($value="click") {
		return $this->setParam("on", $value);
	}

	public function setExclusive($value=true) {
		return $this->setParam("exclusive", $value);
	}

	//TODO other events implementation
}