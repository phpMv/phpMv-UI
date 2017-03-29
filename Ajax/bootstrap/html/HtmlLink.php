<?php

namespace Ajax\bootstrap\html;

use Ajax\JsUtils;
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\common\html\html5\HtmlLinkTrait;

class HtmlLink extends HtmlBsDoubleElement {
	use HtmlLinkTrait;

	public function __construct($identifier, $href="#", $content="Link") {
		parent::__construct($identifier, "a");
		$this->setHref($href);
		$this->content=$content;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\HtmlSingleElement::run()
	 */
	public function run(JsUtils $js) {
		$this->_bsComponent=$js->bootstrap()->generic("#".$this->identifier);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}
}
