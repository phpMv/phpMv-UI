<?php

namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\base\constants\Wide;
use Ajax\JsUtils;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\collections\form\traits\FieldsTrait;

class HtmlFormFields extends HtmlSemCollection {

	use FieldsTrait;
	protected $_equalWidth;
	protected $_name;

	public function __construct($identifier, $fields=array(), $equalWidth=true) {
		parent::__construct($identifier, "div");
		$this->_equalWidth=$equalWidth;
		$this->addItems($fields);
	}

	public function addFields($fields=NULL, $label=NULL) {
		if (!$fields instanceof HtmlFormFields) {
			if (!\is_array($fields)) {
				$fields=\func_get_args();
				$end=\end($fields);
				if (\is_string($end)) {
					$label=$end;
					\array_pop($fields);
				} else
					$label=NULL;
			}
		}
		if (isset($label))
			$this->setLabel($label);
		foreach ( $fields as $field ) {
			$this->addItem($field);
		}
		return $this;
	}

	/**
	 *
	 * @param string|HtmlSemDoubleElement $label
	 * @return \Ajax\semantic\html\base\HtmlSemDoubleElement
	 */
	public function setLabel($label) {
		$labelO=$label;
		if (\is_string($label)) {
			$labelO=new HtmlSemDoubleElement("", "label", "", $label);
		}
		$this->insertItem($labelO, 0);
		return $labelO;
	}

	public function addItem($item) {
		$item=parent::addItem($item);
		if($item instanceof HtmlFormField)
			$item->setContainer($this);
		return $item;
	}
	
	/**
	 * @return HtmlFormField
	 */
	public function getItem($index){
		return parent::getItem($index);
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if ($this->_equalWidth) {
			$count=$this->count();
			$this->addToProperty("class", Wide::getConstants()["W".$count]." fields");
		} else
			$this->addToProperty("class", "fields");
		return parent::compile($js, $view);
	}

	public function setWidth($index, $width) {
		$this->_equalWidth=false;
		return $this->getItem($index)->setWidth($width);
	}

	public function setInline() {
		$this->_equalWidth=false;
		$this->addToProperty("class", "inline");
		return $this;
	}

	public function setGrouped() {
		$this->_equalWidth=false;
		$this->addToProperty("class", "grouped");
	}

	public function getName() {
		return $this->_name;
	}

	public function setName($_name) {
		$this->_name=$_name;
		return $this;
	}


	public static function radios($identifier,$name, $items=array(), $label=NULL, $value=null, $type=NULL) {
		$fields=array ();
		$i=0;
		foreach ( $items as $val => $caption ) {
			$itemO=new HtmlFormRadio($name."-".$i++, $name, $caption, $val, $type);
			if ($val===$value) {
				$itemO->getDataField()->setProperty("checked", "");
			}
			$fields[]=$itemO;
		}
		$radios=new HtmlFormFields($identifier, $fields);
		if (isset($label)){
			$lbl=$radios->setLabel($label);
			if($lbl instanceof HtmlSemDoubleElement){
				$lbl->setProperty("for", $name);
			}
		}
		return $radios;
	}

	public static function checkeds($identifier,$name, $items=array(), $label=NULL, $values=array(), $type=NULL) {
		$fields=array ();
		$i=0;
		foreach ( $items as $val => $caption ) {
			$itemO=new HtmlFormCheckbox($name."-".$i++, $caption, $val, $type);
			$itemO->setName($name);
			if (\array_search($val, $values)!==false) {
				$itemO->getDataField()->setProperty("checked", "");
			}
			$fields[]=$itemO;
		}
		$radios=new HtmlFormFields($identifier, $fields);
		if (isset($label))
			$radios->setLabel($label)->setProperty("for", $name);
		return $radios;
	}

	public function setEqualWidth($_equalWidth) {
		$this->_equalWidth=$_equalWidth;
		return $this;
	}
	
	public function run(JsUtils $js){
		return parent::run($js);
		//return $result->setItemSelector("[data-value]");
	}
}
