<?php

namespace Ajax\semantic\html\views;

use Ajax\semantic\html\content\view\HtmlViewItem;

class HtmlCard extends HtmlViewItem {

	public function __construct($identifier,$content=NULL) {
		parent::__construct($identifier, "ui card", $content);
	}
}
