<?php

namespace Ajax\common\html\html5;

use Ajax\common\html\HtmlSingleElement;
use Ajax\service\JString;

class HtmlInput extends HtmlSingleElement {

	public function __construct($identifier,$type="text",$value=NULL,$placeholder=NULL) {
		parent::__construct($identifier, "input");
		$this->setProperty("name", $identifier);
		$this->setValue($value);
		$this->setPlaceholder($placeholder);
		$this->setProperty("type", $type);
	}

	public function setValue($value) {
		if(isset($value))
		$this->setProperty("value", $value);
		return $this;
	}

	public function setInputType($value) {
		return $this->setProperty("type", $value);
	}

	public function setPlaceholder($value){
		if(JString::isNull($value)){
			if(JString::isNotNull($this->identifier))
				$value=\ucfirst($this->identifier);
		}
		if(JString::isNotNull($value))
			$this->setProperty("placeholder", $value);
		return $this;
	}
}