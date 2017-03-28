<?php

namespace Ajax\bootstrap\components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleExtComponent;

/**
 * Composant Twitter Bootstrap Scrollspy
 * @author jc
 * @version 1.001
 */
class Scrollspy extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="scrollspy";
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\common\components\SimpleComponent::attach()
	 */
	public function attach($identifier) {
		parent::attach($identifier);
	}

	public function setTarget($target) {
		$this->setParam("target", $target);
	}

	public function onActivate($jsCode) {
		$this->addEvent("activate.bs.scrollspy", $jsCode);
	}
}
