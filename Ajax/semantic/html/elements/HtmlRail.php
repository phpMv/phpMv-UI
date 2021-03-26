<?php
namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;

/**
 *
 * @author jc
 *
 */
class HtmlRail extends HtmlSemDoubleElement {

	public function __construct($identifier, $content = NULL) {
		parent::__construct($identifier, 'div', 'ui rail', $content);
	}

	private function updateType(string $type) {
		$this->addToProperty('class', $type);
		return $this;
	}

	public function setLeft() {
		return $this->updateType('left');
	}

	public function setRight() {
		return $this->updateType('right');
	}

	public function setInternal() {
		return $this->updateType('internal');
	}

	public function setDividing() {
		return $this->updateType('dividing');
	}

	public function setClose() {
		return $this->updateType('close');
	}
}
