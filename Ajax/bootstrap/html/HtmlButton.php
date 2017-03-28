<?php

namespace Ajax\bootstrap\html;

use Ajax\JsUtils;
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\base\CssRef;

/**
 * Twitter Bootstrap Button component
 * @see http://getbootstrap.com/css/#buttons
 * @author jc
 * @version 1.001
 */
class HtmlButton extends HtmlBsDoubleElement {

	/**
	 * Constructs an HTML Bootstrap button
	 * @param string $identifier HTML id
	 * @param string $value value of the Button
	 * @param string $cssStyle btn-default, btn-primary...
	 * @param string $onClick JS Code for click event
	 */
	public function __construct($identifier, $value="", $cssStyle=null, $onClick=null) {
		parent::__construct($identifier, "button");
		$this->setProperty("class", "btn btn-default");
		$this->setProperty("role", "button");
		$this->content=$value;
		if (isset($cssStyle)) {
			$this->setStyle($cssStyle);
		}
		if (isset($onClick)) {
			$this->onClick($onClick);
		}
	}

	/**
	 * Set the button value
	 * @param string $value
	 * @return \Ajax\bootstrap\html\HtmlButton
	 */
	public function setValue($value) {
		$this->content=$value;
		return $this;
	}

	/**
	 * define the button style
	 * avaible values : "btn-default","btn-primary","btn-success","btn-info","btn-warning","btn-danger"
	 * @param string|int $cssStyle
	 * @return \Ajax\bootstrap\html\HtmlButton default : "btn-default"
	 */
	public function setStyle($cssStyle) {
		return $this->addToPropertyCtrl("class", CssRef::getStyle($cssStyle, "btn"), CssRef::Styles("btn"));
	}

	/**
	 * define the button size
	 * available values : "btn-lg","","btn-sm","btn-xs"
	 * @param string|int $size
	 * @return \Ajax\bootstrap\html\HtmlButton default : ""
	 */
	public function setSize($size) {
		if (is_int($size)) {
			return $this->addToPropertyUnique("class", CssRef::sizes()[$size], CssRef::sizes());
		}
		return $this->addToPropertyCtrl("class", $size, CssRef::sizes());
	}

	/**
	 * Sets the "active" css class to the button
	 * @return \Ajax\bootstrap\html\HtmlButton
	 */
	public function setActive() {
		return $this->addToPropertyCtrl("class", "active", array (
				"active"
		));
	}

	/**
	 * Disables the button
	 * @return \Ajax\bootstrap\html\HtmlButton
	 */
	public function setDisabled() {
		return $this->addToPropertyCtrl("class", "disabled", array (
				"disabled"
		));
	}

	public function setToggled() {
		return $this->setProperty("data-toggle", "button");
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		$this->_bsComponent=$js->bootstrap()->generic("#".$this->identifier);
		if (array_key_exists("data-toggle", $this->properties)) {
			$this->_bsComponent->addCode("$.fn.toggled=function(){return this.hasClass('active');};");
		}
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}



	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\BaseHtml::fromArray()
	 */
	public function fromArray($array) {
		$array=parent::fromArray($array);
		foreach ( $array as $key => $value ) {
			$this->setProperty($key, $value);
		}
		return $array;
	}
}
