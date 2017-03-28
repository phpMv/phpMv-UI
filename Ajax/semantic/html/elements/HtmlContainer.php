<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\traits\TextAlignmentTrait;
/**
 * Semantic UI container component
 * @see http://phpmv-ui.kobject.net/index/direct/main/34
 * @see http://semantic-ui.com/elements/container.html#/definition
 * @author jc
 * @version 1.001
 */
class HtmlContainer extends HtmlSemDoubleElement {
	use TextAlignmentTrait;
	public function __construct($identifier, $content="") {
		parent::__construct($identifier, "div","ui container");
		$this->content=$content;
	}

	public function asText(){
		return $this->addToProperty("class", "text");
	}
}
