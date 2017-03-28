<?php
namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\semantic\html\base\traits\IconTrait;
use Ajax\semantic\html\collections\form\traits\TextFieldsTrait;
use Ajax\semantic\html\collections\form\traits\FieldTrait;
use Ajax\JsUtils;

class HtmlInput extends HtmlSemDoubleElement {
	use IconTrait,TextFieldsTrait,FieldTrait;

	public function __construct($identifier, $type="text", $value="", $placeholder="") {
		parent::__construct("div-" . $identifier, "div", "ui input");
		$this->_identifier=$identifier;
		$this->content=[ "field" => new \Ajax\common\html\html5\HtmlInput($identifier, $type, $value, $placeholder) ];
		$this->_states=[ State::DISABLED,State::FOCUS,State::ERROR ];
		$this->_variations=[ Variation::TRANSPARENT ];
	}

	public function getField() {
		return $this;
	}

	public function getDataField() {
		return $this->content["field"];
	}

	public static function outline($identifier, $icon, $value="", $placeholder="") {
		$result=new HtmlInput($identifier, "text", $value, $placeholder);
		$result->addToProperty("class", "transparent");
		$result->addIcon($icon)->setOutline();
		return $result;
	}

	public function run(JsUtils $js) {
		$result=parent::run($js);
		$result->attach("#" . $this->getDataField()->getIdentifier());
		return $result;
	}

	public function setTransparent(){
		return $this->addToProperty("class", "transparent");
	}
}
