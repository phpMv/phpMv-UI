<?php

namespace Ajax\semantic\html\elements;

use Ajax\common\html\HtmlSingleElement;
use Ajax\semantic\html\base\constants\Country;

/**
 * Semantic Flag component
 * @see http://phpmv-ui.kobject.net/index/direct/main/64
 * @see http://semantic-ui.com/elements/flag.html
 * @author jc
 * @version 1.001
 */
class HtmlFlag extends HtmlSingleElement {
	protected $flag;

	public function __construct($identifier, $flag) {
		parent::__construct($identifier, "i");
		$this->_template='<i class="%flag% flag"></i>';
		$this->flag=$flag;
	}

	public function setFlag($flag) {
		$this->flag=$flag;
	}

	public static function France() {
		return new HtmlFlag("", Country::FRANCE);
	}

	public static function byNum($num) {
		return new HtmlFlag("", Country::getConstantValues()[$num]);
	}
}
