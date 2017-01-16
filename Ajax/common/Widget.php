<?php

namespace Ajax\common;

use Ajax\common\html\HtmlDoubleElement;

abstract class Widget extends HtmlDoubleElement {

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

	public function getInstanceViewer() {
		return $this->_instanceViewer;
	}

	public function setInstanceViewer($_instanceViewer) {
		$this->_instanceViewer=$_instanceViewer;
		return $this;
	}

	public abstract function getHtmlComponent();

	public function setColor($color){
		return $this->getHtmlComponent()->setColor($color);
	}
}