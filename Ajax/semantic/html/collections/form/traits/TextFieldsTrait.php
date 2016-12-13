<?php

namespace Ajax\semantic\html\collections\form\traits;

trait TextFieldsTrait {

	public abstract function getField();
	public function setPlaceholder($value){
		$this->getField()->setPlaceholder($value);
		return $this;
	}

	public function setValue($value){
		$this->getField()->setValue($value);
		return $this;
	}
}