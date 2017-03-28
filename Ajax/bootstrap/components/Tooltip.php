<?php

namespace Ajax\bootstrap\components;

use Ajax\JsUtils;
use Ajax\bootstrap\html\base\CssRef;
use Ajax\common\components\SimpleExtComponent;

/**
 * Composant Twitter Bootstrap Tooltip
 * @author jc
 * @version 1.001
 */
class Tooltip extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="tooltip";
	}

	public function attach($identifier) {
		parent::attach($identifier);
		$this->js->attr($identifier, "data-toggle", $this->uiName, true);
	}

	/**
	 * Apply a CSS fade transition to the tooltip if set to true
	 * @param Boolean $value
	 * @return Tooltip default : true
	 */
	public function setAnimation($value) {
		return $this->setParamCtrl("animation", $value, "is_bool");
	}

	/**
	 * Delay showing and hiding the tooltip (ms) - does not apply to manual trigger type
	 * If a number is supplied, delay is applied to both hide/show
	 * Object structure is: delay: { "show": 500, "hide": 100 }
	 * @param mixed $value
	 * @return Tooltip default : 0
	 */
	public function setDelai($value) {
		return $this->setParam("delai", $value);
	}

	/**
	 * Insert HTML into the tooltip.
	 * If false, jQuery's text method will be used to insert content into the DOM.
	 * Use text if you're worried about XSS attacks.
	 * @param Boolean $value
	 * @return $this default : false
	 */
	public function setHtml($value) {
		return $this->setParamCtrl("html", $value, "is_bool");
	}

	/**
	 * How to position the tooltip - top | bottom | left | right | auto.
	 * When "auto" is specified, it will dynamically reorient the tooltip.
	 * For example, if placement is "auto left", the tooltip will display to the left when possible, otherwise it will display right.
	 * When a function is used to determine the placement,
	 * it is called with the tooltip DOM node as its first argument and the triggering element DOM node as its second.
	 * The this context is set to the tooltip instance.
	 * @param string $value
	 * @return $this default : top
	 */
	public function setPlacement($value) {
		return $this->setParamCtrl("placement", $value, CssRef::position());
	}

	/**
	 * If a selector is provided, tooltip objects will be delegated to the specified targets.
	 * In practice, this is used to enable dynamic HTML content to have tooltips added.
	 * @param string $value
	 * @return $this default : false
	 */
	public function setSelector($value) {
		return $this->setParam("selector", $value);
	}

	/**
	 * Base HTML to use when creating the tooltip.
	 * The tooltip's title will be injected into the .tooltip-inner.
	 * .tooltip-arrow will become the tooltip's arrow.
	 * The outermost wrapper element should have the .tooltip class.
	 * @param string $value
	 * @return $this
	 */
	public function setTemplate($value) {
		return $this->setParam("template", $value);
	}

	/**
	 * Default title value if title attribute isn't present.
	 * If a function is given, it will be called with its this reference set to the element that the tooltip is attached to.
	 * @param string $value
	 * @return $this default : ''
	 */
	public function setTitle($value) {
		return $this->setParam("title", $value);
	}

	/**
	 * How tooltip is triggered - click | hover | focus | manual.
	 * You may pass multiple triggers; separate them with a space.
	 * @param string $value
	 * @return Tooltip default : 'hover focus'
	 */
	public function setTrigger($value) {
		return $this->setParam("trigger", $value);
	}

	/**
	 * Keeps the tooltip within the bounds of this element.
	 * Example: viewport: '#viewport' or { "selector": "#viewport", "padding": 0 }
	 * @param mixed $value
	 * @return $this
	 */
	public function setViewport($value) {
		return $this->setParam("viewport", $value);
	}

	/**
	 * This event fires immediately when the show instance method is called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShow($jsCode) {
		return $this->addEvent("show.bs.".$this->uiName, $jsCode);
	}

	/**
	 * This event is fired when the tooltip has been made visible to the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShown($jsCode) {
		return $this->addEvent("shown.bs.".$this->uiName, $jsCode);
	}

	/**
	 * This event is fired immediately when the hide instance method has been called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHide($jsCode) {
		return $this->addEvent("hide.bs.".$this->uiName, $jsCode);
	}

	/**
	 * This event is fired when the tooltip has finished being hidden from the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHidden($jsCode) {
		return $this->addEvent("hidden.bs.".$this->uiName, $jsCode);
	}
}
