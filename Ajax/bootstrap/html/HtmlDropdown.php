<?php

namespace Ajax\bootstrap\html;

use Ajax\JsUtils;
use Ajax\bootstrap\html\content\HtmlDropdownItem;
use Ajax\bootstrap\html\base\CssRef;
use Ajax\service\JString;
use Ajax\common\html\BaseHtml;

/**
 * Twitter Bootstrap HTML Dropdown component
 * @author jc
 * @version 1.001
 */
class HtmlDropdown extends HtmlButton {
	protected $btnCaption="Dropdown button";
	protected $class="dropdown-toggle";
	protected $mClass="dropdown";
	protected $mTagName="div";
	protected $items=array ();

	/**
	 *
	 * @param string $identifier the id
	 */
	public function __construct($identifier, $value="", $items=array(), $cssStyle=null, $onClick=null) {
		parent::__construct($identifier, "", $cssStyle, $onClick);
		$this->_template=include 'templates/tplDropdown.php';
		$this->btnCaption=$value;
		$this->tagName="a";
		$this->fromArray($items);
		if ($cssStyle!==NULL) {
			$this->asButton($cssStyle);
		}
	}

	/**
	 * Define the tagName of the main element
	 * @param string $value default : div
	 */
	public function setMTagName($value) {
		$this->mTagName=$value;
	}

	/**
	 * define the button style
	 * avaible values : "btn-default","btn-primary","btn-success","btn-info","btn-warning","btn-danger"
	 * @param string|int $cssStyle
	 * @return \Ajax\bootstrap\html\HtmlDropdown default : "btn-default"
	 */
	public function setStyle($cssStyle) {
		if (is_int($cssStyle)) {
			return $this->addToMember($this->class, CssRef::buttonStyles()[$cssStyle]);
		}
		if (JString::startsWith($cssStyle, "btn-")===false) {
			$cssStyle="btn".$cssStyle;
		}
		return $this->addToMemberCtrl($this->class, $cssStyle, CssRef::buttonStyles());
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\HtmlButton::setValue()
	 */
	public function setValue($value) {
		$this->btnCaption=$value;
	}

	/**
	 * define the buttons size
	 * available values : "btn-group-lg","","btn-group-sm","btn-group-xs"
	 * @param string|int $size
	 * @return HtmlDropdown
	 * default : ""
	 */
	public function setSize($size) {
		if (is_int($size)) {
			return $this->addToProperty("class", CssRef::sizes("btn-group")[$size]);
		}
		return $this->addToPropertyCtrl("class", $size, CssRef::sizes("btn-group"));
	}

	/**
	 * add an HtmlDropdownItem
	 * @param string $caption
	 * @param string $href
	 * @return HtmlDropdownItem
	 */
	public function addItem($caption, $href="#") {
		if($caption instanceof HtmlDropdownItem){
			$item=$caption;
		}else{
			$iid=$this->getItemsCount()+1;
			$item=new HtmlDropdownItem($this->identifier."-dropdown-item-".$iid);
			$item->setCaption($caption)->setHref($href);
		}
		$this->items []=$item;
		return $item;
	}

	public function addDivider() {
		return $this->addItem("-");
	}

	public function addHeader($caption) {
		return $this->addItem("-".$caption);
	}

	public function addItems($items) {
		$iid=$this->getItemsCount()+1;
		if (\is_array($items)) {
			foreach ( $items as $item ) {
				if (is_string($item)) {
					$this->addItem($item);
				} else if (\is_array($item)) {
					$dropDownItem=new HtmlDropdownItem($this->identifier."-dropdown-item-".$iid);
					$dropDownItem->fromArray($item);
					$this->items []=$dropDownItem;
				} else if ($item instanceof HtmlDropdownItem) {
					$this->items []=$item;
				}
			}
		}
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::fromArray()
	 */
	public function fromArray($array) {
		if (array_keys($array)!==range(0, count($array)-1))
			return parent::fromArray($array);
		else
			return $this->addItems($array);
	}

	public function setItems($items) {
		$this->items=array ();
		$this->addItems($items);
	}

	/**
	 * Return the item at $index
	 * @param int $index
	 * @return HtmlDropdownItem
	 */
	public function getItem($index) {
		return $this->items [$index];
	}

	public function setBtnClass($value) {
		$this->class=$value;
	}

	public function setMClass($value) {
		$this->mClass=$value;
	}

	public function addBtnClass($value) {
		$this->addToMember($this->class, $value);
	}

	public function addmClass($value) {
		$this->addToMember($this->mClass, $value);
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if ($this->getProperty("role")==="nav") {
			foreach ( $this->items as $dropdownItem ) {
				$dropdownItem->runNav($js);
			}
		}
		$this->_bsComponent=$js->bootstrap()->dropdown("#".$this->identifier);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	/**
	 * Sets the tagName's dropdown
	 * @see BaseHtml::setTagName()
	 */
	public function setTagName($tagName) {
		if ($tagName=="button")
			$this->class="btn dropdown-toggle";
		return parent::setTagName($tagName);
	}

	public function __toString() {
		return $this->compile();
	}

	public function setBtnCaption($btnCaption) {
		$this->btnCaption=$btnCaption;
		return $this;
	}

	public function getItemsCount() {
		return sizeof($this->items);
	}

	public function setAlignment($alignment) {
		if (is_int($alignment))
			$alignment="dropdown-menu-".CssRef::alignment()[$alignment];
		return $this->addToMemberCtrl($this->class, $alignment, CssRef::alignment());
	}

	public function dropup() {
		$this->addToMember($this->mClass, "dropup");
	}

	public function getItems() {
		return $this->items;
	}

	public function asButton($cssStyle="btn-primary") {
		$this->setTagName("button");
		$this->setBtnClass("btn dropdown-toggle");
		$this->setStyle($cssStyle);
	}

	/**
	 * This event fires immediately when the show instance method is called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShow($jsCode) {
		return $this->addEvent("show.bs.dropdown", $jsCode);
	}

	/**
	 * This event is fired when a dropdown element has been made visible to the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShown($jsCode) {
		return $this->addEvent("shown.bs.dropdown", $jsCode);
	}

	/**
	 * This event is fired immediately when the hide method has been called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHide($jsCode) {
		return $this->addEvent("hide.bs.dropdown", $jsCode);
	}

	/**
	 * This event is fired when a dropdown element has been hidden from the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHidden($jsCode) {
		return $this->addEvent("hidden.bs.dropdown", $jsCode);
	}


	/* (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::on()
	 */
	public function on($event, $jsCode, $stopPropagation = false, $preventDefault = false) {
		foreach ($this->items as $item){
			$item->on($event, $jsCode,$stopPropagation,$preventDefault);
		}
	}

	/* (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$this->addItem($function($object));
	}

}
