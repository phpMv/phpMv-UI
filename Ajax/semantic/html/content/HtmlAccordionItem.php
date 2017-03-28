<?php

namespace Ajax\semantic\html\content;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\JsUtils;



class HtmlAccordionItem extends HtmlSemDoubleElement {
	protected $titleElement;
	protected $_icon="dropdown";
	protected $_title;
	protected $_active;

	public function __construct($identifier, $title, $content=NULL) {
		parent::__construct($identifier, "div", "content", $content);
		$this->_template="%titleElement%".$this->_template;
		$this->_title=$title;
	}

	public function setTitle($title){
		$this->_title=$title;
	}

	public function setIcon($icon){
		$this->_icon=$icon;
	}

	protected function createTitleElement(){
		$element=new HtmlSemDoubleElement("title-".$this->identifier,"div","title");
		$element->setContent(array(new HtmlIcon("", $this->_icon),$this->_title));
		if($this->_active===true)
			$element->addToProperty("class", "active");
		return $element;
	}

	public function compile(JsUtils $js=NULL, &$view=NULL){
		$this->titleElement=$this->createTitleElement();
		return parent::compile($js,$view);
	}

	public function setActive($value=true){
		$this->_active=$value;
		if($value===true)
			$this->addToPropertyCtrl("class", "active", array("active"));
		else
			$this->removePropertyValue("class", "active");
		return $this;
	}
}
