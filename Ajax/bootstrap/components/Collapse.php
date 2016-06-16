<?php

namespace Ajax\bootstrap\components;

use Ajax\JsUtils;
use Ajax\common\JsCode;
use Ajax\common\components\SimpleExtComponent;

/**
 * Composant Twitter Bootstrap Collapse
 * @author jc
 * @version 1.001
 */
class Collapse extends SimpleExtComponent {
	protected $collapsed;

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="collapse";
	}

	public function attach($identifier) {
		parent::attach($identifier);
		$this->js->attr($identifier, "data-toggle", "collapse", true);
	}

	public function show() {
		$this->jsCodes []=new JsCode(' $(function () {$("%identifier%").click();});');
	}

	/**
	 * This event fires immediately when the show instance method is called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShow($jsCode) {
		return $this->addEvent("show.bs.collapse", $jsCode);
	}

	/**
	 * This event is fired when a collapse element has been made visible to the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShown($jsCode) {
		return $this->addEvent("shown.bs.collapse", $jsCode);
	}

	/**
	 * This event is fired immediately when the hide method has been called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHide($jsCode) {
		return $this->addEvent("hide.bs.collapse", $jsCode);
	}

	/**
	 * This event is fired when a collapse element has been hidden from the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHidden($jsCode) {
		return $this->addEvent("hidden.bs.collapse", $jsCode);
	}

	protected function compileEvents() {
		foreach ( $this->events as $event => $jsCode ) {
			$this->jquery_code_for_compile []="$( \"".$this->collapsed."\" ).on(\"".$event."\" , function (e) {".$jsCode."});";
		}
	}

	public function setCollapsed($collapsed) {
		$this->collapsed=$collapsed;
		return $this;
	}
}
