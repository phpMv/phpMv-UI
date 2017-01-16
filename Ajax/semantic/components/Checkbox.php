<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class Checkbox extends SimpleExtComponent {

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
	//TODO other events implementation
}