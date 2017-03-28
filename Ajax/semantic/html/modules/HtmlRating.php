<?php

namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;

class HtmlRating extends HtmlSemDoubleElement {
	protected $_params=array();
	/**
	 * @param string $identifier
	 * @param int $value
	 * @param int $max
	 * @param string $icon star or heart
	 */
	public function __construct($identifier, $value,$max=5,$icon="") {
		parent::__construct($identifier, "div", "ui {$icon} rating");
		$this->setValue($value);
		$this->setMax($max);
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\html\HtmlDoubleElement::setValue()
	 */
	public function setValue($value){
		$this->setProperty("data-rating", $value);
	}

	public function setMax($max){
		$this->setProperty("data-max-rating", $max);
	}

	/**
	 * {@inheritDoc}
	 * @see HtmlSemDoubleElement::run()
	 */
	public function run(JsUtils $js){
		parent::run($js);
		return $js->semantic()->rating("#".$this->identifier,$this->_params);
	}

	public function asStar(){
		return $this->setIcon();
	}

	public function asHeart(){
		return $this->setIcon("heart");
	}

	public function setIcon($icon="star"){
		return $this->addToPropertyCtrl("class", $icon, ["star","heart",""]);
	}

}
