<?php

namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\common\html\html5\HtmlTextarea;
use Ajax\semantic\html\collections\form\traits\TextFieldsTrait;

class HtmlFormTextarea extends HtmlFormField {
	use TextFieldsTrait;

	public function __construct($identifier, $label=NULL,$value=NULL,$placeholder=NULL,$rows=NULL) {
		if(!isset($placeholder))
			$placeholder=$label;
		parent::__construct("field-".$identifier, new HtmlTextarea($identifier,$value,$placeholder,$rows), $label);
		$this->_identifier=$identifier;
	}

	/**
	 * Defines the textarea row count
	 * @param int $count
	 */
	public function setRows($count){
		$this->getField()->setRows($count);
	}

	public function getDataField() {
		return $this->content["field"];
	}

	public function setName($name){
		$this->getDataField()->setProperty("name",$name);
	}
}
