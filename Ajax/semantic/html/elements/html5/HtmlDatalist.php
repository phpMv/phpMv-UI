<?php
namespace Ajax\semantic\html\elements\html5;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\common\html\HtmlCollection;

class HtmlDatalist extends HtmlCollection {

	public function __construct($identifier) {
		parent::__construct($identifier, "datalist");
	}

	protected function createItem($value) {
		$elm = new HtmlDoubleElement("", "option");
		$elm->setProperty("value", $value);
		$elm->setContent($value);
		return $elm;
	}

	protected function createCondition($value) {
		return true;
	}
}

