<?php

namespace Ajax\bootstrap\components\js;

use Ajax\common\JsCode;

class Draggable extends JsCode {

	public function __construct() {
		$this->mask="$('%identifier%').draggable({ handle: '.modal-header' });";
	}
}
