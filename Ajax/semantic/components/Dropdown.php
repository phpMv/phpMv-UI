<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class Dropdown extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="dropdown";
	}

	/**
	 * Sets a default action to occur
	 * @param string $action one of "select","auto","activate","combo","nothing","hide"
	 * @return \Ajax\semantic\components\Dropdown
	 */
	public function setAction($action){
		return $this->setParamCtrl("action", $action,array("select","auto","activate","combo","nothing","hide"));
	}

	/**
	 * Define the event which trigger dropdown
	 * @param string $event Event used to trigger dropdown (Hover, Click, Custom Event)
	 * @return \Ajax\semantic\components\Dropdown
	 */
	public function setOn($event){
		return $this->setParam("on", $event);
	}

	public function setFullTextSearch($value){
		return $this->setParam("fullTextSearch", $value);
	}

	public function setShowOnFocus($value){
		return $this->setParam("showOnFocus", $value);
	}
}
