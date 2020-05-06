<?php
namespace Ajax\ui\components;

use Ajax\common\components\SimpleComponent;
use Ajax\JsUtils;
use Ajax\service\JString;

/**
 * Composant JQuery UI Button
 *
 * @author jc
 * @version 1.001
 */
class Button extends SimpleComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName = "button";
	}

	/**
	 * Disables the button if set to true.
	 *
	 * @param Boolean $value
	 *        	default : false
	 * @return $this
	 */
	public function setDisabled($value) {
		return $this->setParamCtrl("disabled", $value, "is_bool");
	}

	/**
	 * Icons to display, with or without text (see text option).
	 * By default, the primary icon is displayed on the left of the label text and the secondary is displayed on the right.
	 * The positioning can be controlled via CSS.
	 * The value for the primary and secondary properties must match an icon class name, e.g., "ui-icon-gear".
	 * For using only one icon: icons: { primary: "ui-icon-locked" }. For using two icons: icons: { primary: "ui-icon-gear", secondary: "ui-icon-triangle-1-s" }.
	 *
	 * @param String $value
	 *        	default : { primary: null, secondary: null }
	 * @return $this
	 */
	public function setIcons($value) {
		if (is_string($value)) {
			if (JString::startsWith($value, "{"));
			$value = "%" . $value . "%";
		}
		return $this->setParam("icons", $value);
	}

	/**
	 * Whether to show the label.
	 * When set to false no text will be displayed, but the icons option must be enabled, otherwise the text option will be ignored.
	 *
	 * @param Boolean $value
	 *        	default : false
	 * @return $this
	 */
	public function setText($value) {
		return $this->setParamCtrl("text", $value, "is_bool");
	}

	/**
	 * Text to show in the button.
	 * When not specified (null), the element's HTML content is used, or its value attribute if the element is an input element of type submit or reset,
	 * or the HTML content of the associated label element if the element is an input of type radio or checkbox.
	 *
	 * @param string $value
	 *        	default : null
	 * @return $this
	 */
	public function setLabel($value) {
		return $this->setParam("label", $value);
	}
}
