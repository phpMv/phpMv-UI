<?php

namespace Ajax\common;

use Ajax\common\html\HtmlDoubleElement;

class Widget extends HtmlDoubleElement {

	protected $_modelInstance;

	public function __construct($identifier,$modelInstance=NULL) {
		parent::__construct($identifier);
		$this->_template="%wrapContentBefore%%content%%wrapContentAfter%";
		if(isset($modelInstance));
			$this->show($modelInstance);
	}

	public function show($modelInstance){
		$this->_modelInstance=$modelInstance;
	}

	public function getInstanceClassName(){
		if(\is_array($this->_modelInstance)){
			if(\sizeof($this->_modelInstance)>0){
				return \get_class($this->_modelInstance[0]);
			}
		}else{
			return \get_class($this->_modelInstance);
		}
		return false;
	}
}