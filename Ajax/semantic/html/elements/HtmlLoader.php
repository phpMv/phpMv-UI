<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;

/**
 *
 * @author jc
 *
 */
class HtmlLoader extends HtmlSemDoubleElement {

	public function __construct($identifier, $content=NULL) {
		parent::__construct($identifier, "div", "ui loader", $content);
	}
}
