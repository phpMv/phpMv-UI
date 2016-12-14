<?php
namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\semantic\html\base\traits\IconTrait;
use Ajax\semantic\html\collections\form\traits\TextFieldsTrait;
use Ajax\semantic\html\collections\form\traits\FieldTrait;

class HtmlInput extends HtmlSemDoubleElement {
	use IconTrait,TextFieldsTrait,FieldTrait;

	public function __construct($identifier, $type="text", $value="", $placeholder="") {
		parent::__construct("div-" . $identifier, "div", "ui input");
		$this->content=[ "field" => new \Ajax\common\html\html5\HtmlInput($identifier, $type, $value, $placeholder) ];
		$this->_states=[ State::DISABLED,State::FOCUS,State::ERROR ];
		$this->_variations=[ Variation::TRANSPARENT ];
	}

	public function getField() {
		return $this->content["field"];
	}

	public function getDataField() {
		return $this->getField();
	}

	public static function outline($identifier, $icon, $value="", $placeholder="") {
		$result=new HtmlInput($identifier, "text", $value, $placeholder);
		$result->addToProperty("class", "transparent");
		$result->addIcon($icon)->setOutline();
		return $result;
	}
}