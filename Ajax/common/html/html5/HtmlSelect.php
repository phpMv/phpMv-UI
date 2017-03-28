<?php
namespace Ajax\common\html\html5;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\JsUtils;
use Ajax\service\JArray;
/**
 * HTML Select
 * @author jc
 * @version 1.002
 */

class HtmlSelect extends HtmlDoubleElement {
	private $default;
	private $options;

	public function __construct($identifier, $caption="",$default=NULL) {
		parent::__construct($identifier, "select");
		$this->setProperty("name", $identifier);
		$this->setProperty("class", "form-control");
		$this->setProperty("value", "");
		$this->default=$default;
		$this->options=array();
	}

	public function addOption($element,$value="",$selected=false){
		if($element instanceof HtmlOption){
			$option=$element;
		}else{
			$option=new HtmlOption($this->identifier."-".count($this->options), $element,$value);
		}
		if($selected || $option->getValue()==$this->getProperty("value")){
			$option->select();
		}
		$this->options[]=$option;
	}

	public function setValue($value) {
		foreach ( $this->options as $option ) {
			if (strcasecmp($option->getValue(),$value)===0) {
				$option->setProperty("selected", "");
			}
		}
		$this->setProperty("value", $value);
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->content=array();
		if(isset($this->default)){
			$default=new HtmlOption("", $this->default);
			$this->content[]=$default;
		}
		foreach ($this->options as $option){
			$this->content[]=$option;
		}
		return parent::compile($js, $view);
	}

	public function fromArray($array){
		if(JArray::isAssociative($array)){
			foreach ($array as $k=>$v){
				$this->addOption($v, $k);
			}
		}else{
			foreach ($array as $v){
				$this->addOption($v, $v);
			}
		}
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\HtmlSingleElement::run()
	 */
	public function run(JsUtils $js) {
		parent::run($js);
		$this->_bsComponent=$js->bootstrap()->generic("#".$this->identifier);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function onChange($jsCode) {
		return $this->on("change", $jsCode);
	}

	public function onKeypress($jsCode) {
		return $this->on("keypress", $jsCode);
	}

	/* (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$this->addOption($function($object));
	}

	public function setSize($size){
		return $this->setProperty("size", $size);
	}
}
