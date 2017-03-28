<?php

namespace Ajax\ui\Components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleComponent;

/**
 * Composant JQuery UI Menu
 * @author jc
 * @version 1.001
 */
class Menu extends SimpleComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="menu";
	}
}
