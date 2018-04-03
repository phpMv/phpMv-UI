<?php

namespace Ajax\bootstrap\html\content;

use Ajax\bootstrap\html\base\CssRef;
use Ajax\bootstrap\html\base\HtmlElementAsContent;
use Ajax\service\JArray;
use Ajax\JsUtils;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\service\JString;

/**
 * Inner element for Twitter Bootstrap HTML Listgroup component
 * @see http://getbootstrap.com/components/#list-group
 * @author jc
 * @version 1.001
 */
class HtmlListgroupItem extends HtmlElementAsContent {

	public function __construct($element) {
		parent::__construct($element);
		$this->element->setProperty("class", "list-group-item");
	}

	public function setActive() {
		$this->element->addToProperty("class", "active");
		return $this->element;
	}

	public function setDisabled() {
		$this->element->addToProperty("class", "disabled");
		return $this->element;
	}

	/**
	 * define the Panel style
	 * avaible values : "list-group-item-default","list-group-item-primary","list-group-item-success","list-group-item-info","list-group-item-warning","list-group-item-danger"
	 * @param string|int $cssStyle
	 * @return HtmlBsDoubleElement default : "list-group-item-default"
	 */
	public function setStyle($cssStyle) {
		if (!JString::startsWith($cssStyle, "list-group-item"))
			$cssStyle="list-group-item".$cssStyle;
		$this->element->addToPropertyCtrl("class", $cssStyle, CssRef::Styles("list-group-item"));
		return $this->element;
	}

	public function __toString() {
		return $this->element->compile();
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		return $this->element->compile($js, $view);
	}

	public function setHeadingAndContent($title, $content="", $niveau="1") {
		if (\is_array($title)) {
			$array=$title;
			$title=JArray::getValue($array, "title", 0);
			$content=JArray::getValue($array, "content", 1);
			$niveau=JArray::getValue($array, "niveau", 2);
		}
		$elementHeader=new HtmlBsDoubleElement("", "h".$niveau);
		$elementHeader->setContent($title);
		$elementHeader->setClass("list-group-item-heading");
		$element=new HtmlBsDoubleElement("", "p");
		$element->setContent($content);
		$element->setClass("list-group-item-text");
		$this->element->setContent(array (
				$elementHeader,
				$element
		));
		return $this->element;
	}
}
