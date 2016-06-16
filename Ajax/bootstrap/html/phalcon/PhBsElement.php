<?php

namespace Ajax\bootstrap\html\phalcon;

use Phalcon\Forms\Element;
use Ajax\JsUtils;


abstract class PhBsElement extends Element {
	/**
	 *
	 * @var PhBsRenderer
	 */
	protected $renderer;
	/**
	 *
	 * @var JsUtils
	 */
	protected $js;

	public function __construct($name, array $attributes=null) {
		parent::__construct($name, $attributes);
	}

	public function setName($name) {
		$this->renderer->setName($name);
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		return $this->renderer->compile($js, $view);
	}

	public function run(JsUtils $js) {
		return $this->renderer->run($js);
	}

	public function setLabel($label) {
		return $this->renderer->setLabel($label);
	}

	/*
	 * (non-PHPdoc)
	 * @see \Phalcon\Forms\Element::setAttribute()
	 */
	public function setAttribute($attribute, $value) {
		return $this->renderer->setAttribute($attribute, $value);
	}

	public function getRenderer() {
		return $this->renderer;
	}

	public function setRenderer(PhBsRenderer $renderer) {
		$this->renderer=$renderer;
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Phalcon\Forms\ElementInterface::render()
	 */
	public function render($attributes=null) {
		$this->renderer->getElement()->setDefault($this->getValue());
		return $this->renderer->render($attributes);
	}

	public function getHtmlElement() {
		return $this->renderer->getHtmlElement();
	}

	public function setHtmlElement($htmlElement) {
		$this->renderer->setHtmlElement($htmlElement);
		return $this;
	}
}