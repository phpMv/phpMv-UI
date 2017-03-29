<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class Sticky extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="sticky";
	}

	/**
	 *
	 * @param string $value default : ""
	 * @return $this
	 */
	public function setContext($value="") {
		return $this->setParam("context", $value);
	}

	public function setOffset($offset=0){
		return $this->setParam("offset", $offset);
	}
}
