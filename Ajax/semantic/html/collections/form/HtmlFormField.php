<?php

namespace Ajax\semantic\html\collections\form;

use Ajax\JsUtils;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\Wide;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\components\validation\FieldValidation;
use Ajax\semantic\html\collections\form\traits\FieldTrait;
use Ajax\semantic\html\base\constants\Size;

class HtmlFormField extends HtmlSemDoubleElement {
	use FieldTrait;
	protected $_container;
	protected $_validation;
	public function __construct($identifier, $field,$label=NULL) {
		parent::__construct($identifier, "div","field");
		$this->content=array();
		$this->_states=[State::ERROR,State::DISABLED];
		if(isset($label) && $label!=="")
			$this->setLabel($label);
		$this->setField($field);
		$this->_validation=NULL;
	}

	public function addPointingLabel($label,$pointing=Direction::NONE){
		$labelO=new HtmlLabel("",$label);
		$labelO->setPointing($pointing);
		$this->addContent($labelO,$pointing==="below" || $pointing==="right");
		return $labelO;
	}

	public function setLabel($label){
		$labelO=$label;
		if(\is_string($label)){
			$labelO=new HtmlSemDoubleElement("","label","");
			$labelO->setContent($label);
			$labelO->setProperty("for", \str_replace("field-", "",$this->identifier));
		}
		$this->content["label"]=$labelO;
	}

	public function setField($field){
		$this->content["field"]=$field;
	}

	/**
	 * Returns the label or null
	 * @return mixed
	 */
	public function getLabel(){
		if(\array_key_exists("label", $this->content))
			return $this->content["label"];
	}

	/**
	 * Return the field
	 * @return mixed
	 */
	public function getField(){
		return $this->content["field"];
	}

	/**
	 * Return the field with data
	 * @return mixed
	 */
	public function getDataField(){
		return $this->content["field"];
	}

	/**
	 * puts the label before or behind
	 */
	public function swapLabel(){
		$label=$this->getLabel();
		unset($this->content["label"]);
		$this->content["label"]=$label;
	}

	/**
	 * Defines the field width
	 * @param int $width
	 * @return \Ajax\semantic\html\collections\form\HtmlFormField
	 */
	public function setWidth($width){
		if(\is_int($width)){
			$width=Wide::getConstants()["W".$width];
		}
		$this->addToPropertyCtrl("class", $width, Wide::getConstants());
		if(isset($this->_container)){
			$this->_container->setEqualWidth(false);
		}
		return $this->addToPropertyCtrl("class", "wide",array("wide"));
	}

	/**
	 * Field displays an error state
	 * @return \Ajax\semantic\html\collections\form\HtmlFormField
	 */
	public function setError(){
		return $this->addToProperty("class", "error");
	}

	public function setInline(){
		return $this->addToProperty("class", "inline");
	}

	public function jsState($state){
		return $this->jsDoJquery("addClass",$state);
	}

	public function setContainer($_container) {
		$this->_container=$_container;
		return $this;
	}

	public function setReadonly(){
		$this->getDataField()->setProperty("readonly", "");
	}

	public function addRule($type,$prompt=NULL,$value=NULL){
		$field=$this->getDataField();
		if(isset($field)){
			if(!isset($this->_validation)){
				$this->_validation=new FieldValidation($field->getIdentifier());
			}
			if($type==="empty"){
				$this->addToProperty("class","required");
			}
			$this->_validation->addRule($type,$prompt,$value);
		}
		return $this;
	}
	
	public function setOptional($optional=true){
		$field=$this->getDataField();
		if(isset($field)){
			if(!isset($this->_validation)){
				$this->_validation=new FieldValidation($field->getIdentifier());
			}
			$this->_validation->setOptional($optional);
		}
	}

	public function addRules(array $rules){
		foreach ($rules as $rule){
			$this->addRule($rule);
		}
		return $this;
	}

	public function setRules(array $rules){
		$this->_validation=null;
		return $this->addRules($rules);
	}

	public function addIcon($icon,$direction=Direction::LEFT){
		$field=$this->getField();
		return $field->addIcon($icon,$direction);
	}

	public function getValidation() {
		return $this->_validation;
	}
	
	public function setSize($size) {
		return $this->getField()->addToPropertyCtrl("class", $size, Size::getConstants());
	}

	public function run(JsUtils $js) {
		if(isset($this->_validation)){
			$this->_validation->compile($js);
		}
		return parent::run($js);
	}

}
