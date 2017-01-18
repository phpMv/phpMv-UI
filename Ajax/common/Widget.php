<?php

namespace Ajax\common;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\widgets\datatable\PositionInTable;
use Ajax\semantic\html\collections\menus\HtmlMenu;

abstract class Widget extends HtmlDoubleElement {

	/**
	 * @var string classname
	 */
	protected $_model;
	protected $_modelInstance;
	/**
	 * @var InstanceViewer
	 */
	protected $_instanceViewer;
	/**
	 * @var boolean
	 */
	protected $_toolbar;
	/**
	 * @var PositionInTable
	 */
	protected $_toolbarPosition;


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

	/**
	 * @return \Ajax\semantic\html\collections\menus\HtmlMenu
	 */
	public function getToolbar(){
		if(isset($this->_toolbar)===false){
			$this->_toolbar=new HtmlMenu("toolbar-".$this->identifier);
			$this->_toolbar->setSecondary();
		}
		return $this->_toolbar;
	}

	/**
	 * Adds a new element in toolbar
	 * @param mixed $element
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addInToolbar($element){
		$tb=$this->getToolbar();
		return $tb->addItem($element);
	}

	public function addItemInToolbar($caption,$icon=NULL){
		$result=$this->addInToolbar($caption);
		$result->addIcon($icon);
		return $result;
	}

	public function addButtonInToolbar($caption){
		$bt=new HtmlButton("",$caption);
		return $this->addInToolbar($bt);
	}

	public function addLabelledIconButtonInToolbar($caption,$icon,$before=true,$labeled=false){
		$bt=new HtmlButton("",$caption);
		$bt->addIcon($icon,$before,$labeled);
		return $this->addInToolbar($bt);
	}
}