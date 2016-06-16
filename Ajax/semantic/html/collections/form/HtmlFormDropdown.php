<?php

namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\semantic\html\modules\HtmlDropdown;

class HtmlFormDropdown extends HtmlFormField {

	public function __construct($identifier,$items=array(), $label=NULL,$value=NULL,$multiple=false) {
		parent::__construct("field-".$identifier, (new HtmlDropdown("dropdown-".$identifier,$value,$items))->asSelect($identifier,$multiple), $label);
	}

	public function setItems($items){
		return $this->getField()->setItems($items);
	}
	public function addItem($item,$value=NULL,$image=NULL){
		return $this->getField()->addItem($item,$value,$image);
	}
	public static function multipleDropdown($identifier,$items=array(), $label=NULL,$value=NULL){
		return new HtmlFormDropdown($identifier,$items,$label,$value,true);
	}
}