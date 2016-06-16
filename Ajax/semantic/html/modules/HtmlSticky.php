<?php

namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;

class HtmlSticky extends HtmlSemDoubleElement {
	private $_params=array();

	public function __construct($identifier,$context=NULL,$content=NULL) {
		parent::__construct($identifier, "div", "ui sticky", $content);
		if(isset($content))
			$this->setContext($context);
	}

	public function setContext($context){
		$this->_params["context"]=$context;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::run()
	 */
	public function run(JsUtils $js){
		parent::run($js);
		return $js->semantic()->sticky("#".$this->identifier,$this->_params);
	}
}