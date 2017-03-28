<?php

namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;

class HtmlSticky extends HtmlSemDoubleElement {

	public function __construct($identifier,$context=NULL,$content=NULL) {
		parent::__construct($identifier, "div", "ui sticky", $content);
		if(isset($content))
			$this->setContext($context);
	}

	public function setContext($context){
		$this->_params["context"]=$context;
		return $this;
	}

	public function setFixed($value=NULL){
		$fixed="fixed";
		if(isset($value))
			$fixed.=" ".$value;
		return $this->addToProperty("class",$fixed);
	}

	public function setBound($value=NULL){
		$bound="bound";
		if(isset($value))
			$bound.=" ".$value;
			return $this->addToProperty("class",$bound);
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::run()
	 */
	public function run(JsUtils $js){
		parent::run($js);
		return $js->semantic()->sticky("#".$this->identifier,$this->_params);
	}

	public function setOffset($offset=0){
		$this->_params["offset"]=$offset;
		return $this;
	}



	public function setDebug($verbose=NULL){
		$this->_params["debug"]=true;
		if(isset($verbose))
			$this->_params["verbose"]=true;
		return $this;
	}
}
