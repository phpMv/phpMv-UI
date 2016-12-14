<?php

namespace Ajax\semantic\html\collections\form\traits;

use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\base\constants\State;

trait FieldTrait {

	public abstract function addToProperty($name, $value, $separator=" ");
	public abstract function addLabel($caption, $style="label-default", $leftSeparator="&nbsp;");
	public abstract function addContent($content,$before=false);
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

	public function setTransparent() {
		return $this->addToProperty("class", "transparent");
	}

	public function setReadonly(){
		$this->getDataField()->setProperty("readonly", "");
	}

}