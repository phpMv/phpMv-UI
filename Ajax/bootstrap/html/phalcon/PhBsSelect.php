<?php

namespace Ajax\bootstrap\html\phalcon;

use Ajax\bootstrap\html\phalcon\PhBsElement;
use Phalcon\Forms\Element\Select;
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\html5\HtmlSelect;

class PhBsSelect extends PhBsElement {

	public function __construct($name, $options=array(), $attributes=NULL) {
		parent::__construct($name, $attributes);
		$list=new HtmlSelect($name);
		$list->setTagName("select");
		$this->renderer=new PhBsRenderer(new Select($name, array (), $attributes), $list);
		$this->setOptions($options);
	}

	/**
	 * Set the choice’s options
	 * @param mixed $options array of options to add
	 */
	public function setOptions($options) {
		$this->renderer->getElement()->setOptions($options);
		$this->renderer->getHtmlElement()->setOptions($options);
	}

	/**
	 * Adds an option to the current options
	 * @param mixed $option option to add
	 */
	public function addOption($option) {
		$this->renderer->getElement()->addOption($option);
		$this->renderer->getHtmlElement()->addOption($option);
	}
}