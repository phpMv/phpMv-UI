<?php

namespace Ajax\bootstrap\html;

/**
 * Twitter Bootstrap Button component with a Glyph icon
 * @author jc
 * @version 1.001
 */
class HtmlGlyphButton extends HtmlButton {
	protected $glyph;

	public function __construct($identifier, $glyph=0, $value="", $cssStyle=null, $onClick=null) {
		parent::__construct($identifier, $value, $cssStyle, $onClick);
		$this->_template="<%tagName% id='%identifier%' %properties%>%glyph% %content%</%tagName%>";
		$this->tagName="button";
		$this->setGlyph($glyph);
	}

	public function setGlyph($glyph) {
		$this->glyph=new HtmlGlyphicon($this->identifier."-glyph");
		$this->glyph->setGlyphicon($glyph);
		return $this;
	}
}