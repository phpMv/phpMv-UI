<?php

namespace Ajax\bootstrap\html;

/**
 * Twitter Bootstrap Badge component
 * @see http://getbootstrap.com/components/#labels
 * @author jc
 * @version 1.001
 */
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\base\CssRef;

class HtmlLabel extends HtmlBsDoubleElement {

	public function __construct($identifier, $caption, $style="label-default") {
		parent::__construct($identifier, "span");
		$this->content=$caption;
		$this->setProperty("class", "label");
		$this->setStyle($style);
	}

	/**
	 * define the label style
	 * avaible values : "label-default","label-primary","label-success","label-info","label-warning","label-danger"
	 * @param string|int $cssStyle
	 * @return \Ajax\bootstrap\html\HtmlLabel default : "label-default"
	 */
	public function setStyle($cssStyle) {
		return $this->addToPropertyCtrl("class", CssRef::getStyle($cssStyle, "label"), CssRef::Styles("label"));
	}
}
