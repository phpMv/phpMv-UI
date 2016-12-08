<?php
namespace Ajax\semantic\html\content;

use Ajax\semantic\html\base\HtmlSemDoubleElement;

class HtmlShapeItem extends HtmlSemDoubleElement {
	public function __construct($identifier, $content) {
		parent::__construct($identifier,"div","side",$content);
	}

	public function setActive($value=true){
		if($value){
			$this->addToPropertyCtrl("class", "active", ["active"]);
		}else{
			$this->removePropertyValue("class", "active");
		}
		return $this;
	}
}
