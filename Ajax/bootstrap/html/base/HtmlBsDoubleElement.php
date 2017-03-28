<?php

namespace Ajax\bootstrap\html\base;

use Ajax\bootstrap\html\HtmlBadge;
use Ajax\bootstrap\html\HtmlLabel;
use Ajax\bootstrap\html\HtmlGlyphicon;
use Ajax\common\html\HtmlDoubleElement;

class HtmlBsDoubleElement extends HtmlDoubleElement {

	public function addBadge($caption, $leftSeparator="&nbsp;") {
		$badge=new HtmlBadge("badge-".$this->identifier, $caption);
		$badge->wrap($leftSeparator);
		$this->addContent($badge);
		return $this;
	}

	public function addLabel($caption, $style="label-default", $leftSeparator="&nbsp;") {
		$label=new HtmlLabel("label-".$this->identifier, $caption, $style);
		$label->wrap($leftSeparator);
		$this->addContent($label);
		return $this;
	}

	public function addGlyph($glyphicon,$before=true){
		$glyph=new HtmlGlyphicon("");
		$glyph->setGlyphicon($glyphicon);
		$this->addContent($glyph,$before);
		return $this;
	}

	public function wrapContentWithGlyph($glyphBefore,$glyphAfter=""){
		$before=HtmlGlyphicon::getGlyphicon($glyphBefore)."&nbsp;";
		$after="";
		if($glyphAfter!==""){
			$after="&nbsp;".HtmlGlyphicon::getGlyphicon($glyphAfter);
		}
		return $this->wrapContent($before,$after);
	}
}
