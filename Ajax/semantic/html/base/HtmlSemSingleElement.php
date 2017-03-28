<?php

namespace Ajax\semantic\html\base;

use Ajax\common\html\HtmlSingleElement;
use Ajax\semantic\html\base\traits\BaseTrait;

/**
 * Base class for Semantic single elements
 * @author jc
 * @version 1.001
 */

class HtmlSemSingleElement extends HtmlSingleElement {
	use BaseTrait;
	public function __construct($identifier, $tagName="br",$baseClass="ui") {
		parent::__construct($identifier, $tagName);
		$this->_baseClass=$baseClass;
		$this->setClass($baseClass);
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\base\traits\BaseTrait::addContent()
	 */
	public function addContent($content, $before=false) {}

}
