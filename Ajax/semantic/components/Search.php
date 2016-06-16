<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class Search extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="search";
	}

	public function setSource($value) {
		$this->params["source"]=$value;
		return $this;
	}

	public function setType($type) {
		$this->params["type"]=$type;
		return $this;
	}

	public function setSearchFields($fields) {
		$this->params["searchFields"]=$fields;
		return $this;
	}

	public function setApiSettings($value) {
		$this->params["apiSettings"]=$value;
		return $this;
	}

	public function setOnSelect($jsCode) {
		$this->params["onSelect"]=$jsCode;
		return $this;
	}

	// TODO other events implementation
}