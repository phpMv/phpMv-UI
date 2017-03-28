<?php

namespace Ajax\bootstrap\html;

/**
 * Twitter Bootstrap HTML Input radio component
 * @author jc
 * @version 1.001
 */
class HtmlInputRadio extends HtmlInput {

	public function __construct($identifier, $label=NULL) {
		parent::__construct($identifier);
		$this->setProperty("type", "radio");
		$this->setProperty("class", "");
		if (isset($label)) {
			$this->setLabel($label, false);
		}
	}
}
