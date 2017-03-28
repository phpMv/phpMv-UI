<?php

namespace Ajax\semantic\html\content\view;


/** Semantic html item use in Items component
 * @author jc
 * @since 2.2.2
 */
class HtmlItem extends HtmlViewItem {

	public function __construct($identifier) {
		parent::__construct($identifier, "item", []);
	}
}
