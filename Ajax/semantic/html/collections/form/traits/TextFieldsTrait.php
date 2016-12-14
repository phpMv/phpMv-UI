<?php

namespace Ajax\semantic\html\collections\form\traits;

trait TextFieldsTrait {

	public abstract function getDataField();
	public function setPlaceholder($value){
		$this->getDataField()->setPlaceholder($value);
		return $this;
	}

	public function setValue($value){
		$this->getDataField()->setValue($value);
		return $this;
	}
}