<?php
namespace Ajax\ui\components;

use Ajax\JsUtils;
use Ajax\ui\properties\Position;
use Ajax\common\components\SimpleComponent;

/**
 * Composant JQuery UI Menu
 *
 * @author jc
 * @version 1.001
 */
class Tooltip extends SimpleComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName = "tooltip";
	}

	/**
	 * The content of the tooltip.
	 *
	 * @param string $value
	 *        	string or function
	 *        	default : function returning the title attribute
	 * @return $this
	 */
	public function setContent($value) {
		return $this->setParam("content", $value);
	}

	/**
	 * Disables the tooltip if set to true.
	 *
	 * @param mixed $value
	 *        	default : false
	 * @return $this
	 */
	public function setDisabled($value) {
		return $this->setParamCtrl("disabled", $value, "is_bool");
	}

	/**
	 * If and how to animate the hiding of the tooltip.
	 *
	 * @param mixed $value
	 *        	default : true
	 * @return $this
	 */
	public function setHide($value) {
		return $this->setParam("hide", $value);
	}

	/**
	 * A selector indicating which items should show tooltips.
	 * Customize if you're using something other then the title attribute for the tooltip content,
	 * or if you need a different selector for event delegation.
	 *
	 * @param string $value
	 *        	default : title
	 * @return $this
	 */
	public function setItems($value) {
		return $this->setParam("items", $value);
	}

	/**
	 * Identifies the position of the tooltip in relation to the associated target element.
	 * The of option defaults to the target element, but you can specify another element to position against.
	 * You can refer to the jQuery UI Position utility for more details about the various options.
	 *
	 * @param Position $position
	 *        	default : { my: "left top+15", at: "left bottom", collision: "flipfit" }
	 * @return $this
	 */
	public function setPosition(Position $position) {
		return $this->setParam("position", $position->getParams());
	}

	/**
	 * If and how to animate the showing of the tooltip.
	 *
	 * @param mixed $value
	 *        	default : true
	 * @return $this
	 */
	public function setShow($value) {
		return $this->setParam("show", $value);
	}

	/**
	 * A class to add to the widget, can be used to display various tooltip types, like warnings or errors.
	 * This may get replaced by the classes option.
	 *
	 * @param string $value
	 *        	default : null
	 * @return $this
	 */
	public function setTooltipClass($value) {
		return $this->setParam("tooltipclass", $value);
	}

	/**
	 * Whether the tooltip should track (follow) the mouse.
	 *
	 * @param Boolean $value
	 *        	default :false
	 * @return $this
	 */
	public function setTrack($value) {
		return $this->setParamCtrl("track", $value, "is_bool");
	}

	/**
	 * Triggered when a tooltip is closed, triggered on focusout or mouseleave.
	 *
	 * @param string $jsCode
	 * @return $this
	 */
	public function onClose($jsCode) {
		return $this->addEvent("close", $jsCode);
	}

	/**
	 * Triggered when a tooltip is shown, triggered on focusin or mouseover.
	 *
	 * @param string $jsCode
	 * @return $this
	 */
	public function onOpen($jsCode) {
		return $this->addEvent("open", $jsCode);
	}
}
