<?php
namespace Ajax\ui\components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleComponent;

/**
 * Composant JQuery UI Menu
 *
 * @author jc
 * @version 1.001
 */
class Buttonset extends SimpleComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName = "buttonset";
	}

	/**
	 * Disables the buttonSet if set to true.
	 *
	 * @param Boolean $value
	 *        	default : false
	 * @return $this
	 */
	public function setDisabled($value) {
		return $this->setParamCtrl("disabled", $value, "is_bool");
	}

	/**
	 * Which descendant elements to convert manage as buttons.
	 * default : "button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(ui-button)"
	 *
	 * @param String $value
	 * @return $this
	 */
	public function setItems($value) {
		return $this->setParam("items", $value);
	}
}
