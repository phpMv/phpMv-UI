<?php

namespace Ajax\semantic\html\modules\checkbox;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\CheckboxType;

abstract class AbstractCheckbox extends HtmlSemDoubleElement {
	protected $_params=array ();

	public function __construct($identifier, $name=NULL, $label=NULL, $value=NULL, $inputType="checkbox", $type="checkbox") {
		parent::__construct("ck-".$identifier, "div", "ui ".$type);
		$field=new \Ajax\common\html\html5\HtmlInput($identifier, $inputType, $value);
		$field->setProperty("name", $name);
		$this->setField($field);
		if (isset($label))
			$this->setLabel($label);
	}

	public function setType($checkboxType) {
		return $this->addToPropertyCtrl("class", $checkboxType, CheckboxType::getConstants());
	}

	public function setLabel($label) {
		$labelO=$label;
		if (\is_string($label)) {
			$labelO=new HtmlSemDoubleElement("", "label", "");
			$labelO->setContent($label);
			$labelO->setProperty("for", $this->getField()->getIdentifier());
		}
		$this->content["label"]=$labelO;
	}

	public function setField($field) {
		$this->content["field"]=$field;
	}

	/**
	 * Returns the label or null
	 * @return mixed
	 */
	public function getLabel() {
		if (\array_key_exists("label", $this->content))
			return $this->content["label"];
	}

	/**
	 * Return the field
	 * @return mixed
	 */
	public function getField() {
		return $this->content["field"];
	}

	/**
	 * puts the label before or behind
	 */
	public function swapLabel() {
		$label=$this->getLabel();
		unset($this->content["label"]);
		$this->content["label"]=$label;
	}

	public function setReadonly() {
		$this->getField()->setProperty("disabled", "disabled");
		return $this->addToProperty("class", "read-only");
	}

	/**
	 * Attach $this to $selector and fire $action
	 * @param string $selector jquery selector of the associated element
	 * @param string $action action to execute : check, uncheck or NULL for toggle
	 * @return \Ajax\semantic\html\collections\form\AbstractHtmlFormRadioCheckbox
	 */
	public function attachEvent($selector, $action=NULL) {
		if (isset($action)!==false||\is_numeric($action)===true) {
			$js='$("#%identifier%").checkbox("attach events", "'.$selector.'", "'.$action.'");';
		} else {
			$js='$("#%identifier%").checkbox("attach events", "'.$selector.'");';
		}
		$js=\str_replace("%identifier%", $this->identifier, $js);
		return $this->executeOnRun($js);
	}

	/**
	 * Attach $this to an array of $action=>$selector
	 * @param array $events associative array of events to attach ex : ["#bt-toggle","check"=>"#bt-check","uncheck"=>"#bt-uncheck"]
	 * @return \Ajax\semantic\html\collections\form\AbstractHtmlFormRadioCheckbox
	 */
	public function attachEvents($events=array()) {
		if (\is_array($events)) {
			foreach ( $events as $action => $selector ) {
				$this->attachEvent($selector, $action);
			}
		}
		return $this;
	}

	public function setFitted() {
		return $this->addToProperty("class", "fitted");
	}
}