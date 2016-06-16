<?php

namespace Ajax\semantic\html\collections\form\traits;

use Ajax\semantic\html\base\constants\CheckboxType;

trait CheckboxTrait {

	public abstract function addToPropertyCtrl($name, $value, $typeCtrl);

	public function setType($checkboxType) {
		return $this->addToPropertyCtrl("class", $checkboxType, CheckboxType::getConstants());
	}

	/**
	 * Attach $this to $selector and fire $action
	 * @param string $selector jquery selector of the associated element
	 * @param string $action action to execute : check, uncheck or NULL for toggle
	 * @return \Ajax\semantic\html\collections\form\AbstractHtmlFormRadioCheckbox
	 */
	public function attachEvent($selector, $action=NULL) {
		return $this->getField()->attachEvent($selector, $action);
	}

	/**
	 * Attach $this to an array of $action=>$selector
	 * @param array $events associative array of events to attach ex : ["#bt-toggle","check"=>"#bt-check","uncheck"=>"#bt-uncheck"]
	 * @return \Ajax\semantic\html\collections\form\AbstractHtmlFormRadioCheckbox
	 */
	public function attachEvents($events=array()) {
		return $this->getField()->attachEvents($events);
	}
}