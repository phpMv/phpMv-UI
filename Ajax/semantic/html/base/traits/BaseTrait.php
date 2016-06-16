<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\base\constants\Size;
use Ajax\semantic\html\base\constants\Color;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\elements\HtmlIcon;

trait BaseTrait {
	protected $_variations=[ ];
	protected $_states=[ ];
	protected $_baseClass;

	protected abstract function setPropertyCtrl($name, $value, $typeCtrl);

	protected abstract function addToPropertyCtrl($name, $value, $typeCtrl);

	protected abstract function addToPropertyCtrlCheck($name, $value, $typeCtrl);

	public abstract function addToProperty($name, $value, $separator=" ");

	public function addVariation($variation) {
		return $this->addToPropertyCtrlCheck("class", $variation, $this->_variations);
	}

	public function addState($state) {
		return $this->addToPropertyCtrlCheck("class", $state, $this->_states);
	}

	public function setVariation($variation) {
		$this->setPropertyCtrl("class", $variation, $this->_variations);
		return $this->addToProperty("class", $this->_baseClass);
	}

	public function setVariations($variations) {
		$this->setProperty("class", $this->_baseClass);
		if (\is_string($variations))
			$variations=\explode(" ", $variations);
		foreach ( $variations as $variation ) {
			$this->addVariation($variation);
		}
		return $this;
	}

	public function setState($state) {
		$this->setPropertyCtrl("class", $state, $this->_states);
		return $this->addToProperty("class", $this->_baseClass);
	}

	public function addVariations($variations=array()) {
		if (\is_string($variations))
			$variations=\explode(" ", $variations);
		foreach ( $variations as $variation ) {
			$this->addVariation($variation);
		}
		return $this;
	}

	public function addStates($states=array()) {
		if (\is_string($states))
			$states=\explode(" ", $states);
		foreach ( $states as $state ) {
			$this->addState($state);
		}
		return $this;
	}

	public function setStates($states) {
		$this->setProperty("class", $this->_baseClass);
		if (\is_string($states))
			$states=\explode(" ", $states);
		foreach ( $states as $state ) {
			$this->addState($state);
		}
		return $this;
	}

	public function addIcon($icon, $before=true) {
		return $this->addContent(new HtmlIcon("icon-" . $this->identifier, $icon), $before);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\HtmlSingleElement::setSize()
	 */
	public function setSize($size) {
		return $this->addToPropertyCtrl("class", $size, Size::getConstants());
	}

	/**
	 * show it is currently unable to be interacted with
	 * @return \Ajax\semantic\html\elements\HtmlSemDoubleElement
	 */
	public function setDisabled() {
		return $this->addToProperty("class", "disabled");
	}

	/**
	 *
	 * @param string $color
	 * @return \Ajax\semantic\html\base\HtmlSemDoubleElement
	 */
	public function setColor($color) {
		return $this->addToPropertyCtrl("class", $color, Color::getConstants());
	}

	/**
	 *
	 * @return \Ajax\semantic\html\base\HtmlSemDoubleElement
	 */
	public function setFluid() {
		return $this->addToProperty("class", "fluid");
	}

	/**
	 * can be formatted to appear on dark backgrounds
	 */
	public function setInverted() {
		return $this->addToProperty("class", "inverted");
	}

	public function setCircular() {
		return $this->addToProperty("class", "circular");
	}

	public function setFloated($direction="right") {
		return $this->addToPropertyCtrl("class", $direction . " floated", Direction::getConstantValues("floated"));
	}

	public function floatRight() {
		return $this->setFloated();
	}

	public function floatLeft() {
		return $this->setFloated("left");
	}

	public function getBaseClass() {
		return $this->_baseClass;
	}
}