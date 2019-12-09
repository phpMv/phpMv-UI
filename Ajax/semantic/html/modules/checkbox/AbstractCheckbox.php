<?php
namespace Ajax\semantic\html\modules\checkbox;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\CheckboxType;
use Ajax\JsUtils;

abstract class AbstractCheckbox extends HtmlSemDoubleElement {

	protected $_params = [];

	public function __construct($identifier, $name = NULL, $label = NULL, $value = NULL, $inputType = "checkbox", $type = "checkbox") {
		parent::__construct("ck-" . $identifier, "div", "ui " . $type);
		$this->_identifier = $identifier;
		$field = new \Ajax\common\html\html5\HtmlInput($identifier, $inputType, $value);
		$field->setProperty("name", $name);
		$this->setField($field);
		if (isset($label))
			$this->setLabel($label, $value);
		$this->setLibraryId($identifier);
	}

	public function setChecked($value = true) {
		if ($value === true) {
			$this->getField()->setProperty("checked", "checked");
		} else {
			$this->getField()->removeProperty("checked");
		}
		return $this;
	}

	public function forceValue($value = 'true') {
		$this->getField()->forceValue($value);
		return $this;
	}

	public function setType($checkboxType) {
		return $this->addToPropertyCtrl("class", $checkboxType, CheckboxType::getConstants());
	}

	public function setLabel($label, $value = null) {
		$labelO = $label;
		if (\is_string($label)) {
			$labelO = new HtmlSemDoubleElement("", "label", "");
			$labelO->setContent($label);
			$labelO->setProperty("for", $this->getField()
				->getIdentifier());
			if (isset($value))
				$labelO->setProperty("data-value", $value);
		}
		$this->content["label"] = $labelO;
	}

	public function setField($field) {
		$this->content["field"] = $field;
	}

	/**
	 * Returns the label or null
	 *
	 * @return mixed
	 */
	public function getLabel() {
		if (\array_key_exists("label", $this->content))
			return $this->content["label"];
	}

	/**
	 * Return the field
	 *
	 * @return mixed
	 */
	public function getField() {
		return $this->content["field"];
	}

	/**
	 * puts the label before or behind
	 */
	public function swapLabel() {
		$label = $this->getLabel();
		unset($this->content["label"]);
		$this->content["label"] = $label;
	}

	public function setReadonly() {
		$this->getField()->setProperty("disabled", "disabled");
		return $this->addToProperty("class", "read-only");
	}

	/**
	 * Attach $this to $selector and fire $action
	 *
	 * @param string $selector
	 *        	jquery selector of the associated element
	 * @param string $action
	 *        	action to execute : check, uncheck or NULL for toggle
	 * @return AbstractCheckbox
	 */
	public function attachEvent($selector, $action = NULL) {
		if (isset($action) || \is_numeric($action) === true) {
			$js = '$("#%identifier%").checkbox("attach events", "' . $selector . '", "' . $action . '");';
		} else {
			$js = '$("#%identifier%").checkbox("attach events", "' . $selector . '");';
		}
		$js = \str_replace("%identifier%", $this->identifier, $js);
		return $this->executeOnRun($js);
	}

	/**
	 * Attach $this to an array of $action=>$selector
	 *
	 * @param array $events
	 *        	associative array of events to attach ex : ["#bt-toggle","check"=>"#bt-check","uncheck"=>"#bt-uncheck"]
	 * @return AbstractCheckbox
	 */
	public function attachEvents($events = array()) {
		if (\is_array($events)) {
			foreach ($events as $action => $selector) {
				$this->attachEvent($selector, $action);
			}
		}
		return $this;
	}

	public function setFitted() {
		return $this->addToProperty("class", "fitted");
	}

	public function setOnChecked($jsCode) {
		$this->_params["onChecked"] = $jsCode;
		return $this;
	}

	public function setOnUnchecked($jsCode) {
		$this->_params["onUnchecked"] = $jsCode;
		return $this;
	}

	public function setOnChange($jsCode) {
		$this->_params["onChange"] = $jsCode;
		return $this;
	}

	public function run(JsUtils $js) {
		if (! isset($this->_bsComponent))
			$this->_bsComponent = $js->semantic()->checkbox("#" . $this->identifier, $this->_params);
		return parent::run($js);
	}
}
