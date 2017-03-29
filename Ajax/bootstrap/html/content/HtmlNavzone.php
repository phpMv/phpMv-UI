<?php

namespace Ajax\bootstrap\html\content;

use Ajax\JsUtils;
use Ajax\bootstrap\html\base\CssRef;
use Ajax\bootstrap\html\content\HtmlDropdownItem;
use Ajax\bootstrap\html\HtmlDropdown;
use Ajax\bootstrap\html\HtmlLink;
use Ajax\common\html\BaseHtml;
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;

/**
 * Inner element for Twitter Bootstrap HTML Navbar component
 * @author jc
 * @version 1.001
 */
class HtmlNavzone extends BaseHtml {
	protected $class="navbar-nav";
	protected $elements;

	/**
	 *
	 * @param string $identifier the id
	 */
	public function __construct($identifier) {
		parent::__construct($identifier);
		$this->tagName="ul";
		$this->_template='<%tagName% id="%identifier%" class="nav navbar-nav %class%">%elements%</%tagName%>';
		$this->elements=array ();
	}

	public function setClass($value) {
		$this->setMemberCtrl($this->class, $value, CssRef::navbarZoneClasses());
	}

	public function asForm() {
		$this->addToMember($this->class, "navbar-form");
	}

	public function addElement($element) {
		if($element instanceof HtmlLink){
			$this->addLink($element);
		} else if (is_object($element)) {
			$this->elements []=$element;
		} else if (\is_array($element)) {
			$this->addLink(array_pop($element), array_pop($element));
		} else {
			$this->addLink($element);
		}
	}

	public function setValues($class, $tagName, $elements=array()) {
		$this->class=$class;
		$this->tagName=$tagName;
		$this->elements=$elements;
		return $this;
	}

	public function addElements($elements) {
		if (\is_array($elements)) {
			foreach ( $elements as $key => $element ) {
				$iid=$this->getElementsCount()+1;
				if ($element instanceof HtmlDropdownItem)
					$this->elements []=$element;
				else if (\is_array($element)) {
					if (is_string($key)===true) {
						$dropdown=new HtmlDropdown($this->identifier."-dropdown-".$iid);
						$dropdown->addItems($element);
						$dropdown->setBtnCaption($key);
						$dropdown->setMTagName("li");
						$this->addElement($dropdown);
					} else {
						$this->addLink(array_pop($element), array_pop($element));
					}
				} else if (is_object($element)) {
					$this->addElement($element);
				} else if (is_string($element)) {
					$this->addLink($element);
				}
			}
		}
		return $this;
	}

	public function addLink($caption, $href="#") {
		$iid=$this->getElementsCount()+1;
		$li=new HtmlBsDoubleElement($this->identifier."-li-".$iid, "li");
		if($caption instanceof HtmlLink){
			$link=$caption;
		}else{
			$link=new HtmlLink($this->identifier."-link-".$iid, $href, $caption);
		}
		$li->setContent($link);
		$this->addElement($li);
	}

	public static function form($identifier, $elements=array()) {
		$result=new HtmlNavzone($identifier);
		return $result->setValues("navbar-form navbar-left", "form", $elements);
	}

	public static function left($identifier, $elements=array()) {
		$result=new HtmlNavzone($identifier);
		return $result->setValues("navbar-left", "ul", $elements);
	}

	public static function right($identifier, $elements=array()) {
		$result=new HtmlNavzone($identifier);
		return $result->setValues("navbar-right", "ul", $elements);
	}

	public static function formRight($identifier, $elements=array()) {
		$result=new HtmlNavzone($identifier);
		return $result->setValues("navbar-right navbar-form", "ul", $elements);
	}

	public static function nav($identifier, $elements=array()) {
		$result=new HtmlNavzone($identifier);
		return $result->setValues("navbar-nav", "ul", $elements);
	}

	public function run(JsUtils $js) {
		foreach ( $this->elements as $element ) {
			$element->run($js);
		}
	}

	public function getElementsCount() {
		return sizeof($this->elements);
	}

	public function fromArray($array) {
		return $this->addElements($array);
	}

	public function __toString() {
		return $this->compile();
	}

	/**
	 * @param int $index
	 * @return BaseHtml
	 */
	public function getElement($index){
		return $this->elements[$index];
	}
}
