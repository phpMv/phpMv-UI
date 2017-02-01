<?php

namespace Ajax\common;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\widgets\datatable\PositionInTable;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\widgets\base\FieldAsTrait;
use Ajax\semantic\html\elements\HtmlButtonGroups;
use Ajax\semantic\widgets\base\InstanceViewer;
use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\service\JArray;
use Ajax\service\Javascript;

abstract class Widget extends HtmlDoubleElement {
	use FieldAsTrait;

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
	 * @var HtmlMenu
	 */
	protected $_toolbar;
	/**
	 * @var PositionInTable
	 */
	protected $_toolbarPosition;

	protected $_edition;


	public function __construct($identifier,$model,$modelInstance=NULL) {
		parent::__construct($identifier);
		$this->_template="%wrapContentBefore%%content%%wrapContentAfter%";
		$this->setModel($model);
		if(isset($modelInstance));
			$this->show($modelInstance);
	}

	protected function _init($instanceViewer,$contentKey,$content,$edition){
		$this->_instanceViewer=$instanceViewer;
		$this->content=[$contentKey=>$content];
		$this->_toolbarPosition=PositionInTable::BEFORETABLE;
		$this->_edition=$edition;
	}

	protected function _getFieldIdentifier($prefix){
		return $this->identifier."-{$prefix}-".$this->_instanceViewer->getIdentifier();
	}

	abstract protected  function _setToolbarPosition($table,$captions=NULL);

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

	abstract public function getHtmlComponent();

	public function setColor($color){
		return $this->getHtmlComponent()->setColor($color);
	}


	public function setCaptions($captions){
		$this->_instanceViewer->setCaptions($captions);
		return $this;
	}

	public function setFields($fields){
		$this->_instanceViewer->setVisibleProperties($fields);
		return $this;
	}

	public function addField($field){
		$this->_instanceViewer->addField($field);
		return $this;
	}

	public function insertField($index,$field){
		$this->_instanceViewer->insertField($index, $field);
		return $this;
	}

	public function insertInField($index,$field){
		$this->_instanceViewer->insertInField($index, $field);
		return $this;
	}

	public function setValueFunction($index,$callback){
		$this->_instanceViewer->setValueFunction($index, $callback);
		return $this;
	}

	public function setIdentifierFunction($callback){
		$this->_instanceViewer->setIdentifierFunction($callback);
		return $this;
	}

	/**
	 * @return \Ajax\semantic\html\collections\menus\HtmlMenu
	 */
	public function getToolbar(){
		if(isset($this->_toolbar)===false){
			$this->_toolbar=new HtmlMenu("toolbar-".$this->identifier);
		}
		return $this->_toolbar;
	}

	/**
	 * Adds a new element in toolbar
	 * @param mixed $element
	 * @param callable $callback function to call on $element
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addInToolbar($element,$callback=NULL){
		$tb=$this->getToolbar();
		if(isset($callback)){
			if(\is_callable($callback)){
				$callback($element);
			}
		}
		return $tb->addItem($element);
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @param callable $callback function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addItemInToolbar($caption,$icon=NULL,$callback=NULL){
		$result=$this->addInToolbar($caption,$callback);
		if(isset($icon))
			$result->addIcon($icon);
		return $result;
	}

	/**
	 * @param array $items
	 * @param callable $callback function($element)
	 * @return \Ajax\common\Widget
	 */
	public function addItemsInToolbar(array $items,$callback=NULL){
		if(JArray::isAssociative($items)){
			foreach ($items as $icon=>$item){
				$this->addItemInToolbar($item,$icon,$callback);
			}
		}else{
			foreach ($items as $item){
				$this->addItemInToolbar($item,null,$callback);
			}
		}
		return $this;
	}

	/**
	 * @param string $value
	 * @param array $items
	 * @param callable $callback function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addDropdownInToolbar($value,$items,$callback=NULL){
		$dd=$value;
		if (\is_string($value)) {
			$dd=new HtmlDropdown("dropdown-". $this->identifier."-".$value, $value, $items);
		}
		return $this->addInToolbar($dd,$callback);
	}

	/**
	 * @param unknown $caption
	 * @param callable $callback function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addButtonInToolbar($caption,$callback=NULL){
		$bt=new HtmlButton("",$caption);
		return $this->addInToolbar($bt,$callback);
	}

	/**
	 * @param array $captions
	 * @param boolean $asIcon
	 * @param callable $callback function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addButtonsInToolbar(array $captions,$asIcon=false,$callback=NULL){
		$bts=new HtmlButtonGroups("",$captions,$asIcon);
		return $this->addInToolbar($bts,$callback);
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @param boolean $before
	 * @param boolean $labeled
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addLabelledIconButtonInToolbar($caption,$icon,$before=true,$labeled=false){
		$bt=new HtmlButton("",$caption);
		$bt->addIcon($icon,$before,$labeled);
		return $this->addInToolbar($bt);
	}

	/**
	 * Defines a callback function to call for modifying captions
	 * function parameters are $captions: the captions to modify and $instance: the active model instance
	 * @param callable $captionCallback
	 * @return Widget
	 */
	public function setCaptionCallback($captionCallback) {
		$this->_instanceViewer->setCaptionCallback($captionCallback);
		return $this;
	}

	/**
	 * Makes the input fields editable
	 * @param boolean $_edition
	 * @return \Ajax\common\Widget
	 */
	public function setEdition($_edition=true) {
		$this->_edition=$_edition;
		return $this;
	}

	/**
	 * Defines the default function which displays fields value
	 * @param callable $defaultValueFunction
	 * @return \Ajax\common\Widget
	 */
	public function setDefaultValueFunction($defaultValueFunction){
		$this->_instanceViewer->setDefaultValueFunction($defaultValueFunction);
		return $this;
	}

	/**
	 * @param string|boolean $disable
	 * @return string
	 */
	public function jsDisabled($disable=true){
		return "$('#".$this->identifier." .ui.input,#".$this->identifier." .ui.dropdown,#".$this->identifier." .ui.checkbox').toggleClass('disabled',".$disable.");";
	}

	/**
	 * @param string $caption
	 * @param callable $callback function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addEditButtonInToolbar($caption,$callback=NULL){
		$bt=new HtmlButton($this->identifier."-editBtn",$caption);
		$bt->setToggle();
		$bt->setActive($this->_edition);
		$bt->onClick($this->jsDisabled(Javascript::prep_value("!$(event.target).hasClass('active')")));
		return $this->addInToolbar($bt,$callback);
	}

	public function setToolbar(HtmlMenu $_toolbar) {
		$this->_toolbar=$_toolbar;
		return $this;
	}

	public function setToolbarPosition($_toolbarPosition) {
		$this->_toolbarPosition=$_toolbarPosition;
		return $this;
	}

}