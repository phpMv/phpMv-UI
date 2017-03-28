<?php

namespace Ajax\ui\Components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleComponent;

/**
 * Composant JQuery UI Selectmenu
 * @author jc
 * @version 1.001
 */
class Selectmenu extends SimpleComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="selectmenu";
	}
}
