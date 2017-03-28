<?php

namespace Ajax\semantic\html\collections\form\traits;

trait TextFieldsTrait {

	abstract public function getDataField();
	abstract public function addToProperty($name, $value, $separator=" ");
	public function setPlaceholder($value){
		$this->getDataField()->setPlaceholder($value);
		return $this;
	}

	public function setValue($value){
		$this->getDataField()->setValue($value);
		return $this;
	}

	public function setInputType($type){
		if($type==="hidden")
			$this->addToProperty("style","display:none;");
		$this->getDataField()->setInputType($type);
		return $this;
	}
}
