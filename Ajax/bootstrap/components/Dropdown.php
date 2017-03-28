<?php

namespace Ajax\bootstrap\components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleExtComponent;

/**
 * Composant Twitter Bootstrap Dropdown
 * @author jc
 * @version 1.001
 */
class Dropdown extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="dropdown";
	}

	public function attach($identifier) {
		parent::attach($identifier);
	}

	/**
	 * This event fires immediately when the show instance method is called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShow($jsCode) {
		return $this->addEvent("show.bs.dropdown", $jsCode);
	}

	/**
	 * This event is fired when a dropdown element has been made visible to the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShown($jsCode) {
		return $this->addEvent("shown.bs.dropdown", $jsCode);
	}

	/**
	 * This event is fired immediately when the hide method has been called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHide($jsCode) {
		return $this->addEvent("hide.bs.dropdown", $jsCode);
	}

	/**
	 * This event is fired when a dropdown element has been hidden from the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHidden($jsCode) {
		return $this->addEvent("hidden.bs.dropdown", $jsCode);
	}
}
