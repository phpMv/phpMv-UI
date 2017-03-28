<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class Shape extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="shape";
	}
}
