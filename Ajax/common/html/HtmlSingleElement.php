<?php

namespace Ajax\common\html;

use Ajax\JsUtils;

class HtmlSingleElement extends BaseHtml {

	public function __construct($identifier, $tagName="br") {
		parent::__construct($identifier);
		$this->tagName=$tagName;
		$this->_template='<%tagName% id="%identifier%" %properties%/>';
	}

	public function setClass($classNames) {
		if(\is_array($classNames)){
			$classNames=implode(" ", $classNames);
		}
		$this->setProperty("class", $classNames);
		return $this;
	}

	public function addClass($classNames) {
		if(\is_array($classNames)){
			$classNames=implode(" ", $classNames);
		}
		$this->addToProperty("class", $classNames);
		return $this;
	}

	public function setRole($value) {
		$this->setProperty("role", $value);
		return $this;
	}

	public function setTitle($value) {
		$this->setProperty("title", $value);
		return $this;
	}
	
	public function setStyle($value){
		$this->setProperty("style", $value);
		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\html\BaseHtml::run()
	 */
	public function run(JsUtils $js) {

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

	public function setSize($size) {
	}
}
