<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;

/**
 *
 * @author jc
 *
 */
class HtmlRail extends HtmlSemDoubleElement {

	public function __construct($identifier, $content=NULL) {
		parent::__construct($identifier, "div", "ui rail", $content);
	}
}
