<?php

namespace Ajax\semantic\html\collections\form\traits;

use Ajax\semantic\html\base\constants\CheckboxType;
use Ajax\semantic\html\modules\checkbox\AbstractCheckbox;

/**
 * @author jc
 * @property mixed $content
 */
trait CheckboxTrait {

	abstract public function addToPropertyCtrl($name, $value, $typeCtrl);

	public function setType($checkboxType) {
		return $this->getHtmlCk()->addToPropertyCtrl("class", $checkboxType, CheckboxType::getConstants());
	}


	/**
	 * Attach $this to $selector and fire $action
	 * @param string $selector jquery selector of the associated element
	 * @param string $action action to execute : check, uncheck or NULL for toggle
	 * @return \Ajax\semantic\html\collections\form\AbstractHtmlFormRadioCheckbox
	 */
	public function attachEvent($selector, $action=NULL) {
		return $this->getHtmlCk()->attachEvent($selector, $action);
	}

	/**
	 * Attach $this to an array of $action=>$selector
	 * @param array $events associative array of events to attach ex : ["#bt-toggle","check"=>"#bt-check","uncheck"=>"#bt-uncheck"]
	 * @return \Ajax\semantic\html\collections\form\AbstractHtmlFormRadioCheckbox
	 */
	public function attachEvents($events=array()) {
		return $this->getHtmlCk()->attachEvents($events);
	}

	public function getField(){
		return $this->content["field"]->getField();
	}

	public function getHtmlCk(){
		return $this->content["field"];
	}

	public function getDataField(){
		$field= $this->getField();
		if($field instanceof AbstractCheckbox)
			$field=$field->getField();
		return $field;
	}

	/**
	 * Check the checkbox
	 * @param boolean $value
	 * @return \Ajax\semantic\html\collections\form\traits\CheckboxTrait
	 */
	public function setChecked($value=true){
		if($value===true){
			$this->getDataField()->setProperty("checked", "checked");
		}else{
			$this->getDataField()->removeProperty("checked");
		}
		return $this;
	}

}