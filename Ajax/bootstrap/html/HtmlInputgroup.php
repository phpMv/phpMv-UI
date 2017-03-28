<?php

namespace Ajax\bootstrap\html;

use Ajax\JsUtils;
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\base\CssRef;

/**
 * Twitter Bootstrap HTML Inputgroup component
 * @author jc
 * @version 1.001
 */
class HtmlInputgroup extends HtmlInput {
	protected $addonLeft=null;
	protected $addonRight=null;
	protected $mClass="input-group";

	public function __construct($identifier) {
		parent::__construct($identifier);
		$this->_template=include 'templates/tplInputgroup.php';
	}

	public function createSpan($text, $position="left") {
		$id=$position."-".$this->identifier;
		$span=new HtmlBsDoubleElement($id);
		$span->setTagName("span");
		$this->setProperty("aria-describedby", $id);
		$span->setContent($text);
		$span->setClass("input-group-addon");
		if (strtolower($position)==="left")
			$this->addonLeft=$span;
		else
			$this->addonRight=$span;
		return $span;
	}

	protected function addInput_($input, $label="", $position="left") {
		$span=$this->createSpan("", $position);
		$span->setClass("input-group-addon");
		$input->setProperty("aria-label", $label);
		$span->setContent($input);
		return $span;
	}

	protected function addButton_($button, $value="", $position="left") {
		$span=$this->createSpan("", $position);
		$span->setClass("input-group-btn");
		$span->setTagName("div");
		$button->setValue($value);
		$span->setContent($button);
		return $span;
	}

	public function createRadio($identifier, $label="", $position="left") {
		return $this->addInput_(new HtmlInputRadio($identifier), $label, $position);
	}

	public function createCheckbox($identifier, $label="", $position="left") {
		return $this->addInput_(new HtmlInputCheckbox($identifier), $label, $position);
	}

	public function createButton($identifier, $value="", $position="left") {
		return $this->addButton_(new HtmlButton($identifier), $value, $position);
	}

	public function createButtons($items, $position="left") {
		$span=$this->createSpan("", $position);
		$span->setClass("input-group-btn");
		$span->setTagName("div");
		$buttons=array();
		$i=1;
		foreach ($items as $item){
			$bt=NULL;
			if(is_string($item)){
				$bt=new HtmlButton($this->identifier."-bt-".$i++,$item);
			}elseif ($item instanceof HtmlButton){
				$bt=$item;
			}
			if(isset($bt)){
				$buttons[]=$bt;
			}
		}
		$span->setContent($buttons);
		return $span;
	}
	protected function addDropdown_(HtmlDropdown $dropdown, $caption="", $position="left", $items=array()) {
		$dropdown->setBtnCaption($caption);
		$dropdown->fromArray($items);

		if (strtolower($position)==="left")
			$this->addonLeft=$dropdown;
		else
			$this->addonRight=$dropdown;
		return $dropdown;
	}

	public function createDropdown($identifier, $caption="", $position="left", $items=array()) {
		$dropdown=new HtmlDropdown($identifier);
		$dropdown->setMTagName("div");
		$dropdown->setTagName("button");
		$dropdown->setMClass("input-group-btn");
		return $this->addDropdown_($dropdown, $caption, $position, $items);
	}

	public function createSplitButton($identifier, $caption="", $position="left", $items=array()) {
		$dropdown=new HtmlSplitbutton($identifier);
		$dropdown->setTagName("button");
		$dropdown->setMClass("input-group-btn");
		$dropdown->setMTagName("div");
		return $this->addDropdown_($dropdown, $caption, $position, $items);
	}

	/**
	 * define the elements size
	 * available values : "input-group-lg","","input-group-sm","input-group-xs"
	 * @param string|int $size
	 * @return \Ajax\bootstrap\html\HtmlInputgroup default : ""
	 */
	public function setSize($size) {
		if (is_int($size)) {
			return $this->addToMemberCtrl($this->mClass, CssRef::sizes("input-group")[$size],CssRef::sizes("input-group"));
		}
		return $this->addToMemberCtrl($this->mClass, $size, CssRef::sizes("input-group"));
	}

	public function run(JsUtils $js) {
		parent::run($js);
		if (isset($this->addonLeft))
			$this->addonLeft->run($js);
		if (isset($this->addonRight))
			$this->addonRight->run($js);
	}

}
