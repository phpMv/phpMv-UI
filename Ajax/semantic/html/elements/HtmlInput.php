<?php
namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\semantic\html\base\traits\IconTrait;
use Ajax\semantic\html\collections\form\traits\TextFieldsTrait;
use Ajax\semantic\html\collections\form\traits\FieldTrait;
use Ajax\JsUtils;
use Ajax\common\html\html5\HtmlInput as HtmlInput5;
use Ajax\service\Javascript;
use Ajax\semantic\html\elements\html5\HtmlDatalist;

class HtmlInput extends HtmlSemDoubleElement {
	use IconTrait,TextFieldsTrait,FieldTrait;

	public function __construct($identifier, $type = "text", $value = "", $placeholder = "") {
		parent::__construct("div-" . $identifier, "div", "ui input");
		$this->_identifier = $identifier;
		$this->_libraryId = $identifier;
		$this->content = [
			"field" => new HtmlInput5($identifier, $type, $value, $placeholder)
		];
		$this->_states = [
			State::DISABLED,
			State::FOCUS,
			State::ERROR
		];
		$this->_variations = [
			Variation::TRANSPARENT
		];
	}

	public function getField() {
		return $this;
	}

	public function getDataField() {
		return $this->content["field"];
	}

	public static function outline($identifier, $icon, $value = "", $placeholder = "") {
		$result = new HtmlInput($identifier, "text", $value, $placeholder);
		$result->addToProperty("class", "transparent");
		$result->addIcon($icon)->setOutline();
		return $result;
	}

	public function run(JsUtils $js) {
		$result = parent::run($js);
		$result->attach("#" . $this->getDataField()
			->getIdentifier());
		return $result;
	}

	public function setTransparent() {
		return $this->addToProperty("class", "transparent");
	}

	public function compile_once(\Ajax\JsUtils $js = NULL, &$view = NULL) {
		parent::compile_once($js, $view);
		if (isset($this->content['file'])) {
			$this->onCreate(Javascript::fileUploadBehavior($this->identifier));
		}
	}

	public function addDataList($items) {
		$dl = new HtmlDatalist('list-' . $this->identifier);
		$dl->addItems($items);
		$this->getDataField()->setProperty('list', $dl->getIdentifier());
		$this->getDataField()->wrap($dl);
	}
}
