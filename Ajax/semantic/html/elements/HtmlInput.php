<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\semantic\html\base\traits\IconTrait;
use Ajax\semantic\html\modules\HtmlDropdown;

class HtmlInput extends HtmlSemDoubleElement {
	use IconTrait;

	public function __construct($identifier, $type="text", $value="", $placeholder="") {
		parent::__construct("div-" . $identifier, "div", "ui input");
		$this->content=[ "field" => new \Ajax\common\html\html5\HtmlInput($identifier, $type, $value, $placeholder) ];
		$this->_states=[ State::DISABLED,State::FOCUS,State::ERROR ];
		$this->_variations=[ Variation::TRANSPARENT ];
	}

	public function setFocus() {
		$this->addToProperty("class", State::FOCUS);
	}

	public function addLoading() {
		if ($this->_hasIcon === false) {
			throw new \Exception("Input must have an icon for showing a loader, use addIcon before");
		}
		return $this->addToProperty("class", State::LOADING);
	}

	public function labeled($label, $direction=Direction::LEFT, $icon=NULL) {
		$labelO=$this->addLabel($label,$direction===Direction::LEFT,$icon);
		$this->addToProperty("class", $direction . " labeled");
		return $labelO;
	}

	public function labeledToCorner($icon, $direction=Direction::LEFT) {
		return $this->labeled("", $direction . " corner", $icon)->toCorner($direction);
	}

	public function addAction($action, $direction=Direction::RIGHT, $icon=NULL, $labeled=false) {
		$actionO=$action;
		if (\is_object($action) === false) {
			$actionO=new HtmlButton("action-" . $this->identifier, $action);
			if (isset($icon))
				$actionO->addIcon($icon, true, $labeled);
		}
		$this->addToProperty("class", $direction . " action");
		$this->addContent($actionO, \strstr($direction, Direction::LEFT) !== false);
		return $actionO;
	}

	public function addDropdown($label="", $items=array(),$direction=Direction::RIGHT){
		$labelO=new HtmlDropdown("dd-".$this->identifier,$label,$items);
		$labelO->asSelect("select-".$this->identifier,false,true);
		return $this->labeled($labelO,$direction);
	}

	public function getField() {
		return $this->content["field"];
	}

	public function setPlaceholder($value) {
		$this->getField()->setPlaceholder($value);
		return $this;
	}

	public function setTransparent() {
		return $this->addToProperty("class", "transparent");
	}

	public static function outline($identifier, $icon, $value="", $placeholder="") {
		$result=new HtmlInput($identifier, "text", $value, $placeholder);
		$result->addToProperty("class", "transparent");
		$result->addIcon($icon)->setOutline();
		return $result;
	}
}