<?php

namespace Ajax\bootstrap\html;

use Ajax\common\html\HtmlSingleElement;
use Ajax\bootstrap\html\base\CssGlyphicon;
use Ajax\service\JString;

/**
 * Composant Twitter Bootstrap Glyphicon
 * @author jc
 * @version 1.001
 */
class HtmlGlyphicon extends HtmlSingleElement {
	protected $glyphicon;

	public function __construct($identifier) {
		parent::__construct($identifier, "span");
		$this->_template='<span class="glyphicon %glyphicon%" aria-hidden="true"></span>';
	}

	/**
	 * Defines the glyphicon with his name or his index
	 * @param string|int $glyphicon
	 * @return \Ajax\bootstrap\html\HtmlGlyphicon
	 */
	public function setGlyphicon($glyphicon) {
		if (is_int($glyphicon)) {
			$glyphs=CssGlyphicon::getConstants();
			if ($glyphicon<sizeof($glyphs)) {
				$glyphicon=array_values($glyphs)[$glyphicon];
			}
		} else {
			$glyphicon=strtolower($glyphicon);
			if (JString::startsWith($glyphicon, "glyphicon-")===false) {
				$glyphicon="glyphicon-".$glyphicon;
			}
		}
		$this->glyphicon=$glyphicon;
	}

	/**
	 * return an instance of GlyphButton with a glyph defined by string or index
	 * @param string|int $glyph
	 * @return \Ajax\bootstrap\html\HtmlGlyphicon
	 */
	public static function getGlyphicon($glyph) {
		$result=new HtmlGlyphicon("");
		if (is_int($glyph)) {
			$glyphs=CssGlyphicon::getConstants();
			if ($glyph<sizeof($glyphs)) {
				$glyph=array_values($glyphs)[$glyph];
			}
		}
		$result->setGlyphicon($glyph);
		return $result;
	}
}
