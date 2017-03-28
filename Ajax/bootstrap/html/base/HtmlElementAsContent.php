<?php

namespace Ajax\bootstrap\html\base;

use Ajax\JsUtils;
use Ajax\common\html\BaseHtml;
use Ajax\common\html\HtmlSingleElement;

class HtmlElementAsContent extends BaseHtml {

	/**
	 * @var HtmlBsDoubleElement
	 */
	protected $element;

	public function __construct($element) {
		if ($element instanceof HtmlSingleElement) {
			$this->element=$element;
		} elseif (is_string($element)) {
			$this->element=new HtmlBsDoubleElement($element);
		}
		$this->identifier=$element->getIdentifier();
	}

	public function getElement() {
		return $this->element;
	}

	public function setElement($element) {
		$this->element=$element;
		return $this;
	}

	public function addBadge($caption, $leftSeparator="&nbsp;") {
		return $this->element->addBadge($caption,$leftSeparator);
	}

	public function addLabel($caption, $style="label-default", $leftSeparator="&nbsp;") {
		return $this->element->addLabel($caption,$style,$leftSeparator);
	}

	public function run(JsUtils $js) {
		$this->element->run($js);
	}
}
