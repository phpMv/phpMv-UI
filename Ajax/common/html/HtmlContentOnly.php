<?php

namespace Ajax\common\html;

class HtmlContentOnly extends HtmlDoubleElement{
	public function __construct($content,$identifier=""){
		parent::__construct($identifier);
		$this->_template='%wrapContentBefore%%content%%wrapContentAfter%';
		$this->setContent($content);
	}
}

