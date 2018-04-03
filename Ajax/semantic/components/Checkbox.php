<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

class Checkbox extends SimpleSemExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="checkbox";
	}

	public function setOnChecked($value) {
		$this->params["onChecked"]="%function(){".$value."}%";
	}

	public function setOnUnchecked($value) {
		$this->params["onUnchecked"]="%function(){".$value."}%";
	}

	public function setOnChange($value){
		$this->params["onChange"]="%function(){".$value."}%";
	}
}
