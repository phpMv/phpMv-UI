<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

class Shape extends SimpleSemExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="shape";
	}
}
