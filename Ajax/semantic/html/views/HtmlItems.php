<?php

namespace Ajax\semantic\html\views;


use Ajax\semantic\html\content\view\HtmlViewGroups;
use Ajax\semantic\html\content\view\HtmlItem;

/**
 * Semantic html Items component
 * @author jc
 * @since 2.2.2
 */
class HtmlItems extends HtmlViewGroups {

	public function __construct($identifier, $items=[]) {
		parent::__construct($identifier, "ui items",$items);
	}

	protected function createElement(){
		return new HtmlItem("item-" . $this->count());
	}

	public function newItem($identifier) {
		return new HtmlItem($identifier);
	}
}
