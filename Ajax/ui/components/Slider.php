<?php

namespace Ajax\ui\Components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleComponent;

/**
 * Composant JQuery UI Slider
 * @author jc
 * @version 1.001
 */
class Slider extends SimpleComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="slider";
		$this->setParam("value", 0);
	}

	public function onChange($jsCode) {
		return $this->addEvent("change", $jsCode);
	}

	public function onSlide($jsCode) {
		return $this->addEvent("slide", $jsCode);
	}
}
