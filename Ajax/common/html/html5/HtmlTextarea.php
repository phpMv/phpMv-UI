<?php

namespace Ajax\common\html\html5;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\service\JString;

class HtmlTextarea extends HtmlDoubleElement {

	public function __construct($identifier,$value=NULL,$placeholder=NULL,$rows=NULL) {
		parent::__construct($identifier, "textarea");
		$this->setProperty("name", $identifier);
		$this->setValue($value);
		$this->setPlaceholder($placeholder);
		if(isset($rows))
			$this->setRows($rows);
	}
	public function setValue($value) {
		if(isset($value))
			$this->setContent($value);
		return $this;
	}

	public function setPlaceholder($value){
		if(JString::isNotNull($value))
			$this->setProperty("placeholder", $value);
		return $this;
	}

	public function setRows($count){
		$this->setProperty("rows", $count);
	}
}
