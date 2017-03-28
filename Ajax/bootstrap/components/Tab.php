<?php

namespace Ajax\bootstrap\components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleExtComponent;

/**
 * Composant Twitter Bootstrap Tab
 * @author jc
 * @version 1.001
 */
class Tab extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="tab";
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\components\SimpleBsComponent::getScript()
	 */
	public function getScript() {
		$jsCode="$('".$this->attachTo." a').click(function (event) { event.preventDefault();$(this).tab('show');});";
		$this->jquery_code_for_compile []=$jsCode;
		$this->compileEvents();
		return $this->compileJQueryCode();
	}

	public function show() {
		$this->jquery_code_for_compile []=' $(function () {$("'.$this->attachTo.' a").tab("show");});';
	}

	/**
	 * This event fires on tab show, but before the new tab has been shown.
	 * Use event.target and event.relatedTarget to target the active tab and the previous active tab (if available) respectively.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShow($jsCode) {
		return $this->addEvent("show.bs.tab", $jsCode);
	}

	/**
	 * This event fires on tab show after a tab has been shown.
	 * Use event.target and event.relatedTarget to target the active tab and the previous active tab (if available) respectively.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShown($jsCode) {
		return $this->addEvent("shown.bs.tab", $jsCode);
	}

	/**
	 * This event fires when a new tab is to be shown (and thus the previous active tab is to be hidden).
	 * Use event.target and event.relatedTarget to target the current active tab and the new soon-to-be-active tab, respectively.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHide($jsCode) {
		return $this->addEvent("hide.bs.tab", $jsCode);
	}

	/**
	 * This event fires after a new tab is shown (and thus the previous active tab is hidden).
	 * Use event.target and event.relatedTarget to target the previous active tab and the new active tab, respectively.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHidden($jsCode) {
		return $this->addEvent("hidden.bs.tab", $jsCode);
	}
}
