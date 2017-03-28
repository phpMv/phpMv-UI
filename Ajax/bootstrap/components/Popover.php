<?php

namespace Ajax\bootstrap\components;

use Ajax\bootstrap\components\Tooltip;
use Ajax\JsUtils;

/**
 * Composant Twitter Bootstrap Popover
 * @author jc
 * @version 1.001
 */
class Popover extends Tooltip {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="popover";
	}
}
