<?php

namespace Ajax\semantic\html\elements\html5;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\html5\HtmlLinkTrait;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\JsUtils;

class HtmlLink extends HtmlSemDoubleElement {
	use HtmlLinkTrait;

	public function __construct($identifier, $href="#", $content="Link",$target=NULL) {
		parent::__construct($identifier, "a", "");
		$this->setHref($href);
		if(isset($target))
			$this->setTarget($target);
		$this->content=$content;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\HtmlSingleElement::run()
	 */
	public function run(JsUtils $js) {
		$this->_bsComponent=$js->semantic()->generic("#" . $this->identifier);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function addIcon($icon, $before=true) {
		return $this->addContent(new HtmlIcon("icon-" . $this->identifier, $icon), $before);
	}

	public static function icon($identifier, $icon, $href="#", $label=NULL) {
		$result=new HtmlLink($identifier, $href, $label);
		return $result->addIcon($icon);
	}
}
