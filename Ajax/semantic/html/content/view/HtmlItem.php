<?php

namespace Ajax\semantic\html\content\view;


class HtmlItem extends HtmlViewItem {

	public function __construct($identifier) {
		parent::__construct($identifier, "item", []);
	}
}