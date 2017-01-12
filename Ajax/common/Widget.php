<?php

namespace Ajax\common;

use Ajax\common\html\HtmlDoubleElement;

class Widget extends HtmlDoubleElement {

	protected $_model;
	protected $_modelInstance;
	protected $_instanceViewer;

	public function __construct($identifier,$model,$modelInstance=NULL) {
		parent::__construct($identifier);
		$this->_template="%wrapContentBefore%%content%%wrapContentAfter%";
		$this->setModel($model);
		if(isset($modelInstance));
			$this->show($modelInstance);
	}

	public function show($modelInstance){
		$this->_modelInstance=$modelInstance;
	}

	public function getModel() {
		return $this->_model;
	}

	public function setModel($_model) {
		$this->_model=$_model;
		return $this;
	}

}