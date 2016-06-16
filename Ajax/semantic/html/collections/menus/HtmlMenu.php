<?php

namespace Ajax\semantic\html\collections\menus;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\Wide;
use Ajax\common\html\html5\HtmlImg;
use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\modules\HtmlPopup;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\elements\html5\HtmlLink;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\semantic\html\elements\HtmlButton;

/**
 * Semantic Menu component
 * @see http://semantic-ui.com/collections/menu.html
 * @author jc
 * @version 1.001
 */
class HtmlMenu extends HtmlSemCollection {
	private $_itemHeader;

	public function __construct($identifier, $items=array()) {
		parent::__construct($identifier, "div", "ui menu");
		$this->addItems($items);
	}

	/**
	 * Sets the menu type
	 * @param string $type one of text,item
	 * @return \Ajax\semantic\html\collections\HtmlMenu
	 */
	public function setType($type="") {
		return $this->addToPropertyCtrl("class", $type, array ("","item","text" ));
	}

	public function setActiveItem($index) {
		$item=$this->getItem($index);
		if ($item !== null) {
			$item->addToProperty("class", "active");
		}
		return $this;
	}

	private function getItemToInsert($item) {
		if ($item instanceof HtmlInput || $item instanceof HtmlImg || $item instanceof HtmlIcon || $item instanceof HtmlButton || ($item instanceof HtmlDropdown && $this->propertyContains("class", "vertical")===false)) {
			$itemO=new HtmlSemDoubleElement("item-" . $this->identifier, "div", "");
			$itemO->setContent($item);
			$item=$itemO;
		}
		return $item;
	}

	private function afterInsert($item) {
		if (!$item instanceof HtmlMenu)
			$item->addToPropertyCtrl("class", "item", array ("item" ));
		else {
			$this->setSecondary();
		}
		return $item;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\html5\HtmlCollection::addItem()
	 */
	public function addItem($item) {
		$item=parent::addItem($this->getItemToInsert($item));
		return $this->afterInsert($item);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\HtmlCollection::insertItem()
	 */
	public function insertItem($item, $position=0) {
		$item=parent::insertItem($this->getItemToInsert($item), $position);
		return $this->afterInsert($item);
	}

	public function generateMenuAsItem($menu, $header=null) {
		$count=$this->count();
		$item=new HtmlSemDoubleElement("item-" . $this->identifier . "-" . $count, "div");
		if (isset($header)) {
			$headerItem=new HtmlSemDoubleElement("item-header-" . $this->identifier . "-" . $count, "div", "header");
			$headerItem->setContent($header);
			$item->addContent($headerItem);
			$this->_itemHeader=$headerItem;
		}
		$menu->setClass("menu");
		$item->addContent($menu);
		return $item;
	}

	public function addMenuAsItem($menu, $header=null) {
		return $this->addItem($this->generateMenuAsItem($menu, $header));
	}

	public function addPopupAsItem($value, $identifier, $content="") {
		$value=new HtmlSemDoubleElement($identifier, "a", "browse item", $value);
		$value->addContent(new HtmlIcon("", "dropdown"));
		$value=$this->addItem($value);
		$popup=new HtmlPopup($value, "popup-" . $this->identifier . "-" . $this->count(), $content);
		$popup->setFlowing()->setPosition("bottom left")->setOn("click");
		$this->wrap("", $popup);
		return $popup;
	}

	public function addDropdownAsItem($value, $items=NULL) {
		$dd=$value;
		if (\is_string($value)) {
			$dd=new HtmlDropdown("dropdown-" . $this->identifier . "-" . $this->count(), $value, $items);
		}
		return $this->addItem($dd);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\html5\HtmlCollection::createItem()
	 */
	protected function createItem($value) {
		$itemO=new HtmlLink("item-" . \sizeof($this->content), "", $value);
		return $itemO->setClass("item");
	}

	public function setInverted() {
		return $this->addToProperty("class", "inverted");
	}

	public function setSecondary() {
		return $this->addToProperty("class", "secondary");
	}

	public function setVertical() {
		return $this->addToPropertyCtrl("class", "vertical", array ("vertical" ));
	}

	public function setPosition($value="right") {
		return $this->addToPropertyCtrl("class", $value, array ("right","left" ));
	}

	public function setPointing($value=Direction::NONE) {
		return $this->addToPropertyCtrl("class", $value . " pointing", Direction::getConstantValues("pointing"));
	}

	public function asTab($vertical=false) {
		$this->apply(function (HtmlDoubleElement &$item) {
			$item->setTagName("a");
		});
		if ($vertical === true)
			$this->setVertical();
		return $this->addToProperty("class", "tabular");
	}

	public function asPagination() {
		$this->apply(function (HtmlDoubleElement &$item) {
			$item->setTagName("a");
		});
		return $this->addToProperty("class", "pagination");
	}

	public function setFixed() {
		return $this->addToProperty("class", "fixed");
	}

	public function setFluid() {
		return $this->addToProperty("class", "fluid");
	}

	public function setCompact() {
		return $this->addToProperty("class", "compact");
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$return=$function($object);
		if (\is_array($return))
			$this->addItems($return);
		else
			$this->addItem($return);
	}

	/**
	 * Defines the menu width
	 * @param int $width
	 * @return \Ajax\semantic\html\collections\menus\HtmlMenu
	 */
	public function setWidth($width) {
		if (\is_int($width)) {
			$width=Wide::getConstants()["W" . $width];
		}
		$this->addToPropertyCtrl("class", $width, Wide::getConstants());
		return $this->addToPropertyCtrl("class", "item", array ("item" ));
	}

	public function addImage($identifier, $src="", $alt="") {
		return $this->addItem(new HtmlImg($identifier, $src, $alt));
	}

	public static function vertical($identifier, $items=array()) {
		return (new HtmlMenu($identifier, $items))->setVertical();
	}

	public function getItemHeader() {
		return $this->_itemHeader;
	}
}