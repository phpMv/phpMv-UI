<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\Size;
use Ajax\semantic\html\elements\html5\HtmlImg;

/**
 * Semantic Image component
 * @see http://phpmv-ui.kobject.net/index/direct/main/55
 * @see http://semantic-ui.com/elements/icon.html#/definition
 * @author jc
 * @version 1.001
 */
class HtmlImage extends HtmlSemDoubleElement {

	public function __construct($identifier, $src="", $alt="", $size=NULL) {
		$image=new HtmlImg("img-", $src, $alt);
		$image->setClass("");
		parent::__construct($identifier, "div", "ui image", $image);
		if (isset($size))
			$this->setSize($size);
	}

	public function setCircular() {
		return $this->addToProperty("class", "circular");
	}

	public function asAvatar($caption=NULL) {
		if (isset($caption))
			$this->wrap("", $caption);
		return $this->addToProperty("class", "avatar");
	}

	public static function small($identifier, $src="", $alt="") {
		return new HtmlImage($identifier, $src, $alt, Size::SMALL);
	}

	public static function avatar($identifier, $src="", $caption=NULL) {
		$img=new HtmlImage($identifier, $src);
		return $img->asAvatar($caption);
	}
}
