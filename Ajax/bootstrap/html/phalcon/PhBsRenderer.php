<?php

namespace Ajax\bootstrap\html\phalcon;

use Phalcon\Forms\Element;
use Ajax\JsUtils;


class PhBsRenderer {
	/**
	 *
	 * @var HtmlSingleElement
	 */
	protected $htmlElement;
	
	/**
	 *
	 * @var Element
	 */
	protected $element;

	public function __construct(Element $element, $htmlElement) {
		$this->element=$element;
		$this->htmlElement=$htmlElement;
	}

	public function setLabel($label) {
		$this->element->setLabel($label);
		$this->htmlElement->setLabel($label);
		return $this;
	}

	public function setName($name) {
		$this->element->setName($name);
		$this->htmlElement->setIdentifier($name);
		$this->htmlElement->setProperty("name", $name);
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		return $this->htmlElement->compile($js, $view);
	}

	public function run(JsUtils $js) {
		return $this->htmlElement->run($js);
	}

	public function setAttribute($attribute, $value) {
		$this->element->setAttribute($attribute, $value);
		return $this->htmlElement->setProperty($attribute, $value);
	}

	public function render($attributes=null) {
		$attrs=$this->element->getAttributes();
		foreach ( $attrs as $key => $value ) {
			$this->htmlElement->setProperty($key, $value);
		}
		if (isset($attributes))
			$this->htmlElement->addProperties($attributes);
		$this->htmlElement->setIdentifier($this->element->getName());
		$this->htmlElement->setValue($this->element->getValue());
		
		return $this->htmlElement->compile();
	}

	public function getHtmlElement() {
		return $this->htmlElement;
	}

	public function setHtmlElement($htmlElement) {
		$this->htmlElement=$htmlElement;
		return $this;
	}

	public function getElement() {
		return $this->element;
	}

	public function setElement(Element $element) {
		$this->element=$element;
		return $this;
	}
}