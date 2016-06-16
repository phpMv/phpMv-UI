<?php

namespace Ajax\semantic\html\collections\form\traits;

trait TextFieldsTrait {

	public abstract function getField();
	public function setPlaceholder($value){
		return $this->getField()->setPlaceholder($value);
	}

	public function setValue($value){
		return $this->getField()->setValue($value);
	}
}