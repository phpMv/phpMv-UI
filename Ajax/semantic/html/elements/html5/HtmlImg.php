<?php

namespace Ajax\semantic\html\elements\html5;

use Ajax\semantic\html\base\traits\BaseTrait;

class HtmlImg extends \Ajax\common\html\html5\HtmlImg {
	use BaseTrait;

	public function __construct($identifier, $src="", $alt="") {
		parent::__construct($identifier, $src, $alt);
		$this->_baseClass="ui image";
		$this->setClass($this->_baseClass);
	}

	public function asAvatar($caption=NULL) {
		if (isset($caption))
			$this->wrap("", $caption);
			return $this->addToProperty("class", "avatar");
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\base\traits\BaseTrait::addContent()
	 */
	public function addContent($content, $before=false) {}
}
