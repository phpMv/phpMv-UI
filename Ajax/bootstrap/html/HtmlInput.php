<?php

namespace Ajax\bootstrap\html;

use Ajax\common\html\HtmlSingleElement;
use Ajax\JsUtils;

/**
 * Twitter Bootstrap simple Input component
 * @author jc
 * @version 1.001
 */
class HtmlInput extends HtmlSingleElement {

	public function __construct($identifier) {
		parent::__construct($identifier, "input");
		$this->setProperty("name", $identifier);
		$this->setProperty("class", "form-control");
		$this->setProperty("role", "input");
		$this->setProperty("value", "");
		$this->setProperty("type", "text");
	}

	public function setValue($value) {
		$this->setProperty("value", $value);
	}

	public function setInputType($value) {
		$this->setProperty("type", $value);
	}

	public function setLabel($label, $before=true) {
		if ($before===true) {
			$this->wrap("<label for='".$this->identifier."'>".$label."</label>", "");
		} else {
			$this->wrap("", "<label for='".$this->identifier."'>&nbsp;".$label."</label>");
		}
	}

	public function setPlaceHolder($value){
		$this->setProperty("placeholder", $value);
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\HtmlSingleElement::run()
	 */
	public function run(JsUtils $js) {
		$this->_bsComponent=$js->bootstrap()->generic("#".$this->identifier);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function onChange($jsCode) {
		return $this->addEvent("change", $jsCode);
	}

	public function onKeypress($jsCode) {
		return $this->addEvent("keypress", $jsCode);
	}
}
