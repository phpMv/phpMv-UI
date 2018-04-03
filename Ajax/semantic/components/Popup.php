<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

class Popup extends SimpleSemExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="popup";
	}

	/**
	 *
	 * @param string $value default : click
	 * @return $this
	 */
	public function setOn($value="click") {
		return $this->setParam("on", $value);
	}

	/**
	 * This event fires immediately when the show instance method is called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function setOnShow($jsCode) {
		$jsCode=str_ireplace("\"","%quote%", $jsCode);
		return $this->setParam("onShow", "%function(){".$jsCode."}%");
	}

	public function setExclusive($value){
		return $this->setParam("exclusive", $value);
	}

	/**
	 * Defines the css selector of the displayed popup
	 * @param string $popup the css selector of the popup
	 * @return \Ajax\semantic\components\Popup
	 */
	public function setPopup($popup){
		return $this->setParam("popup", $popup);
	}

	public function setInline($value){
		return $this->setParam("inline", $value);
	}

	public function setPosition($value){
		return $this->setParam("position", $value);
	}

	public function setSetFluidWidth($value){
		return $this->setParam("setFluidWidth", $value);
	}
}
