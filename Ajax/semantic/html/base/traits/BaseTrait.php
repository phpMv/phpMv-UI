<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\base\constants\Size;
use Ajax\semantic\html\base\constants\Color;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\service\JString;
use Ajax\semantic\html\base\HtmlSemDoubleElement;

/**
 * @author jc
 * @property string $identifier
 * @property HtmlSemDoubleElement $_self
 */
trait BaseTrait {
	protected $_variations=[ ];
	protected $_states=[ ];
	protected $_baseClass;

	abstract protected function setPropertyCtrl($name, $value, $typeCtrl);

	abstract protected function addToPropertyCtrl($name, $value, $typeCtrl);

	abstract protected function addToPropertyCtrlCheck($name, $value, $typeCtrl);

	abstract public function addToProperty($name, $value, $separator=" ");

	abstract public function setProperty($name, $value);

	abstract public function addContent($content,$before=false);

	abstract public function onCreate($jsCode);

	public function addVariation($variation) {
		return $this->_self->addToPropertyCtrlCheck("class", $variation, $this->_self->getVariations());
	}

	public function addState($state) {
		return $this->_self->addToPropertyCtrlCheck("class", $state, $this->_self->getStates());
	}

	public function setVariation($variation) {
		$this->_self->setPropertyCtrl("class", $variation, $this->_self->getVariations());
		return $this->_self->addToProperty("class", $this->_self->getBaseClass());
	}

	public function setVariations($variations) {
		$this->_self->setProperty("class", $this->_self->getBaseClass());
		if (\is_string($variations))
			$variations=\explode(" ", $variations);
		foreach ( $variations as $variation ) {
			$this->_self->addVariation($variation);
		}
		return $this;
	}

	public function setState($state) {
		$this->_self->setPropertyCtrl("class", $state, $this->_self->getStates());
		return $this->_self->addToProperty("class", $this->_self->getBaseClass());
	}

	public function addVariations($variations=array()) {
		if (\is_string($variations))
			$variations=\explode(" ", $variations);
		foreach ( $variations as $variation ) {
			$this->_self->addVariation($variation);
		}
		return $this;
	}

	public function addStates($states=array()) {
		if (\is_string($states))
			$states=\explode(" ", $states);
		foreach ( $states as $state ) {
			$this->_self->addState($state);
		}
		return $this;
	}

	public function setStates($states) {
		$this->_self->setProperty("class", $this->_self->getBaseClass());
		if (\is_string($states))
			$states=\explode(" ", $states);
		foreach ( $states as $state ) {
			$this->_self->addState($state);
		}
		return $this;
	}

	public function addIcon($icon, $before=true) {
		return $this->_self->addContent(new HtmlIcon("icon-" . $this->_self->getIdentifier(), $icon), $before);
	}

	public function addSticky($context="body"){
		$this->_self->onCreate("$('#".$this->_self->getIdentifier()."').sticky({ context: '".$context."'});");
		return $this;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\HtmlSingleElement::setSize()
	 */
	public function setSize($size) {
		return $this->_self->addToPropertyCtrl("class", $size, Size::getConstants());
	}

	/**
	 * show it is currently unable to be interacted with
	 * @param boolean $disable
	 * @return HtmlSemDoubleElement
	 */
	public function setDisabled($disable=true) {
		if($disable)
			$this->_self->addToProperty("class", "disabled");
		return $this;
	}

	/**
	 *
	 * @param string $color
	 * @return HtmlSemDoubleElement
	 */
	public function setColor($color) {
		return $this->_self->addToPropertyCtrl("class", $color, Color::getConstants());
	}

	/**
	 *
	 * @return HtmlSemDoubleElement
	 */
	public function setFluid() {
		return $this->_self->addToProperty("class", "fluid");
	}

	/**
	 *
	 * @return HtmlSemDoubleElement
	 */
	public function asHeader(){
		return $this->_self->addToProperty("class", "header");
	}

	/**
	 * show it is currently the active user selection
	 * @return HtmlSemDoubleElement
	 */
	public function setActive($value=true){
		if($value)
			$this->_self->addToProperty("class", "active");
		return $this;
	}

	public function setAttached($value=true){
		if($value)
			$this->_self->addToPropertyCtrl("class", "attached", array ("attached" ));
		return $this;
	}

	/**
	 * can be formatted to appear on dark backgrounds
	 */
	public function setInverted($recursive=true) {
		if($recursive===true){
			$content=$this->_self->getContent();
			if($content instanceof HtmlSemDoubleElement)
				$content->setInverted($recursive);
			elseif(\is_array($content) || $content instanceof \Traversable){
				foreach ($content as $elm){
					if($elm instanceof  HtmlSemDoubleElement){
						$elm->setInverted($recursive);
					}
				}
			}
		}
		return $this->_self->addToProperty("class", "inverted");
	}

	public function setCircular() {
		return $this->_self->addToProperty("class", "circular");
	}

	public function setFloated($direction="right") {
		return $this->_self->addToPropertyCtrl("class", $direction . " floated", Direction::getConstantValues("floated"));
	}

	public function floatRight() {
		return $this->_self->setFloated();
	}

	public function floatLeft() {
		return $this->_self->setFloated("left");
	}

	public function getBaseClass() {
		return $this->_baseClass;
	}

	protected function addBehavior(&$array,$key,$value,$before="",$after=""){
		if(\is_string($value)){
			if(isset($array[$key])){
				$p=JString::replaceAtFirstAndLast($array[$key], $before, "", $after, "");
				$array[$key]=$before.$p.$value.$after;
			}else
				$array[$key]=$before.$value.$after;
		}else{
			$array[$key]=$value;
		}
		return $this;
	}

	public function getVariations() {
		return $this->_variations;
	}

	public function getStates() {
		return $this->_states;
	}

}
