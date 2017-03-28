<?php

namespace Ajax\bootstrap\html\content;


use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
/**
 * Inner element for Twitter Bootstrap HTML Dropdown component
 * @author jc
 * @version 1.001
 */
class HtmlTabContentItem extends HtmlBsDoubleElement {

	public function __construct($identifier, $tagName="div") {
		parent::__construct($identifier, $tagName);
		$this->setProperty("role", "tabpanel");
		$this->setProperty("class", "tab-pane");
	}
}
