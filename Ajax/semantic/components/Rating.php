<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

class Rating extends SimpleSemExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="rating";
	}
}
