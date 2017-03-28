<?php

namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;
use Ajax\service\JArray;

use Ajax\semantic\html\base\constants\State;

class HtmlProgress extends HtmlSemDoubleElement {

	public function __construct($identifier, $value=NULL, $label=NULL, $attributes=array()) {
		parent::__construct($identifier, "div", "ui progress");
		if (isset($value))
			$this->setProperty("data-percent", $value);
		$this->createBar();
		if (isset($label))
			$this->setLabel($label);
		$this->_states=[ State::SUCCESS,State::WARNING,State::ERROR,State::ACTIVE,State::DISABLED ];
		$this->addToProperty("class", $attributes);
	}

	public function setLabel($label) {
		$this->content["label"]=new HtmlSemDoubleElement("lbl-" . $this->identifier, "div", "label", $label);
		return $this;
	}

	private function createBar() {
		$bar=new HtmlSemDoubleElement("bar-" . $this->identifier, "div", "bar", new HtmlSemDoubleElement("progress-" . $this->identifier, "div", "progress"));
		$this->content["bar"]=$bar;
		return $this;
	}

	public function setTotal($value) {
		return $this->setProperty("data-total", $value);
	}

	public function setValue($value) {
		return $this->setProperty("data-value", $value);
	}

	public function setPercent($value) {
		return $this->setProperty("data-percent", $value);
	}

	public function setIndicating() {
		return $this->addToProperty("class", "indicating");
	}

	public function setWarning() {
		return $this->addToProperty("class", "warning");
	}

	public function setError() {
		return $this->addToProperty("class", "error");
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->content=JArray::sortAssociative($this->content, [ "bar","label" ]);
		return parent::compile($js, $view);
	}

	public function jsSetValue($value) {
		return '$("#' . $this->identifier . '").progress({value:' . $value . '});';
	}

	public function jsIncValue() {
		return '$("#' . $this->identifier . '").progress("increment");';
	}

	public function jsDecValue() {
		return '$("#' . $this->identifier . '").progress("decrement");';
	}

	/**
	 *
	 * @param mixed $active
	 * @param mixed $error
	 * @param mixed $success
	 * @param mixed $warning
	 * @param mixed $percent
	 * @param mixed $ratio
	 * @return HtmlProgress
	 */
	public function setTextValues($active=false, $error=false, $success=false, $warning=false, $percent="{percent}%", $ratio="{value} of {total}") {
		if (\is_array($active)) {
			$array=$active;
			$active=JArray::getDefaultValue($array, "active", false);
			$success=JArray::getDefaultValue($array, "success", $success);
			$warning=JArray::getDefaultValue($array, "warning", $warning);
			$percent=JArray::getDefaultValue($array, "percent", $percent);
			$ratio=JArray::getDefaultValue($array, "ratio", $ratio);
		}
		$this->_params["text"]="%{active  : " . \var_export($active, true) . ",error: " . \var_export($error, true) . ",success : " . \var_export($success, true) . ",warning : " . \var_export($warning, true) . ",percent : " . \var_export($percent, true) . ",ratio   : " . \var_export($ratio, true) . "}%";
		return $this;
	}

	public function onChange($jsCode) {
		$this->addBehavior($this->_params, "onChange", $jsCode,"%function(percent, value, total){","}%");
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if (isset($this->_bsComponent) === false)
			$this->_bsComponent=$js->semantic()->progress("#" . $this->identifier, $this->_params);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public static function create($identifier, $value=NULL, $label=NULL, $attributes=array()) {
		return new HtmlProgress($identifier, $value, $label, $attributes);
	}
}
