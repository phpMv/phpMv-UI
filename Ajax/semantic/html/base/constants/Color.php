<?php

namespace Ajax\semantic\html\base\constants;

use Ajax\common\BaseEnum;
use Ajax\semantic\html\base\HtmlSemDoubleElement;

abstract class Color extends BaseEnum {
	const STANDARD="", RED="red", ORANGE="orange", YELLOW="yellow", OLIVE="olive", GREEN="green", TEAL="teal", BLUE="blue", VIOLET="violet", PURPLE="purple", PINK="pink", BROWN="brown", GREY="grey", BLACK="black";

	public static function setRed(HtmlSemDoubleElement $e) {
		return $e->addToProperty("class", self::RED);
	}

	public static function setGreen(HtmlSemDoubleElement $e) {
		return $e->addToProperty("class", self::GREEN);
	}
}
