<?php

namespace Ajax\bootstrap\html;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\JsUtils;

use Ajax\common\html\html5\HtmlSelect;
use Ajax\service\JString;

class HtmlForm extends HtmlBsDoubleElement {
	protected $formElementsPrefix;
	protected $futureElements;
	protected $formGroups;

	public function __construct($identifier) {
		parent::__construct($identifier, "form");
		$this->_template='<form id="%identifier%" name="%identifier%" %properties%>%content%</form>';
		$this->futureElements=array ();
		$this->formGroups=array ();
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if (isset($js)) {
			$this->formElementsPrefix=$js->config()->getVar("formElementsPrefix");
			foreach ( $this->futureElements as $futureElement ) {
				$futureElementValue=$this->getPrefix($futureElement);
				$futureElementValues=explode("_", $futureElementValue);
				switch($futureElementValues [0]) {
					case "input":
						$control=new HtmlInput($futureElement);
						$control->setClass("form-control");
						$control->setLabel($this->getPart($futureElement));
						break;
					case "checkbox":
						$control=new HtmlInputCheckbox($futureElement);
						$control->setLabel($this->getPart($futureElement), false);
						break;
					case "radio":
						$name=$this->getPart($futureElement);
						$label=$this->getPart($futureElement, 2);
						$control=new HtmlInputRadio($futureElement);
						$control->setProperty("name", strtolower($name));
						$control->setLabel($label, false);
						break;
					case "select":
						$control=new HtmlSelect($futureElement,"");
						$control->setProperty("size", $futureElementValues [1]);
						$control->setClass("form-control");
						break;
					default:
						$control=new HtmlInput($futureElement);
						$control->setClass("form-control");
						$control->setLabel($this->getPart($futureElement));
						break;
				}
				$this->addElement($control);
			}
		}
		foreach ( $this->formGroups as $group ) {
			$this->addContent($group);
		}
		return parent::compile($js, $view);
	}

	private function getPart($str, $part=1) {
		$result=preg_split('/(?=[A-Z])/', $str);
		if (sizeof($result)>$part) {
			$result=$result [$part];
		} else {
			$result=$str;
		}
		return $result;
	}

	protected function getId($str) {
		$result=preg_split('/(?=[A-Z])/', $str);
		if (sizeof($result)>2) {
			$result=$result [2];
		} else {
			$result=$str;
		}
		return $result;
	}

	protected function getPrefix($element) {
		$result="input_text";
		foreach ( $this->formElementsPrefix as $k => $v ) {
			if (JString::startswith($element, $k)) {
				$result=$v;
				break;
			}
		}
		return $result;
	}

	public function addGroup($identifier="") {
		if ($identifier==="")
			$identifier="form-".$this->identifier;
		$group=new HtmlBsDoubleElement($identifier);
		$group->setTagName("div");
		$group->setClass("form-group");
		$this->formGroups []=$group;
		return $group;
	}

	public function addElement($element) {
		if (sizeof($this->formGroups)===0) {
			$this->addGroup();
		}
		$group=$this->formGroups [sizeof($this->formGroups)-1];
		$group->addContent($element);
		return $group;
	}

	public function getElement($name) {
		$element=null;
		foreach ( $this->formGroups as $group ) {
		}
		return $element;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\HtmlSingleElement::fromArray()
	 */
	public function fromArray($array) {
		foreach ( $array as $value ) {
			if (is_string($value)) {
				$this->futureElements []=$value;
			}
		}
	}
}
