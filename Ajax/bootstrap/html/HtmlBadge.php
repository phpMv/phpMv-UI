<?php

namespace Ajax\bootstrap\html;

/**
 * Twitter Bootstrap Badge component
 * @see http://getbootstrap.com/components/#badges
 * @author jc
 * @version 1.001
 */
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;

class HtmlBadge extends HtmlBsDoubleElement {

	public function __construct($identifier, $caption="") {
		parent::__construct($identifier, "span");
		$this->content=$caption;
		$this->setProperty("class", "badge");
	}
}
