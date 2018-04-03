<?php

namespace Ajax\semantic\html\content;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\HtmlDoubleElement;
use Ajax\common\html\html5\HtmlImg;
use Ajax\semantic\html\base\traits\IconTrait;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\html\base\traits\MenuItemTrait;

class HtmlDropdownItem extends HtmlSemDoubleElement {
	use IconTrait,MenuItemTrait;
	public function __construct($identifier, $content="",$value=NULL,$image=NULL,$description=NULL) {
		parent::__construct($identifier, "a");
		$this->setClass("item");
		$this->setContent($content);
		if($value!==NULL)
			$this->setData($value);
		if($image!==NULL)
			$this->asMiniAvatar($image);
		if($description!==NULL)
			$this->setDescription($description);
	}

	public function setDescription($description){
		$descO=new HtmlDoubleElement("desc-".$this->identifier,"span");
		$descO->setClass("description");
		$descO->setContent($description);
		return $this->addContent($descO,true);
	}

	public function setData($value){
		$this->setProperty("data-value", $value);
		return $this;
	}

	public function asOption(){
		$this->tagName="option";
		if($this->getProperty("data-value")!==null)
			$this->setProperty("value", $this->getProperty("data-value"));
	}

	/**
	 * @param string $image the image src
	 * @return $this
	 */
	public function asMiniAvatar($image){
		$this->tagName="div";
		$img=new HtmlImg("image-".$this->identifier,$image);
		$img->setClass("ui mini avatar image");
		$this->addContent($img,true);
		return $this;
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @return HtmlDropdownItem
	 */
	public function asIcon($caption,$icon){
		$this->setContent($caption);
		$this->addContent(new HtmlIcon("", $icon),true);
		return $this;
	}

	/**
	 * Adds a circular label to the item
	 * @param string $color
	 * @return HtmlDropdownItem
	 */
	public function asCircularLabel($caption,$color){
		$this->setContent($caption);
		$lbl=new HtmlLabel("");
		$lbl->setCircular()->setColor($color)->setEmpty();
		$this->addContent($lbl,true);
		return $this;
	}



	public function addMenuItem($items) {
		$menu=new HtmlMenu("menu-" . $this->identifier, $items);
		$content=$this->content;
		$this->setTagName("div");
		$this->setProperty("class", "item");
		$icon=new HtmlIcon("", "dropdown");
		$this->content=[$icon,new HtmlSemDoubleElement("","span","text",$content),$menu];
		return $menu;
	}

	/**
	 * @param string $placeholder
	 * @param string $icon
	 * @return HtmlDropdownItem
	 */
	public static function searchInput($placeholder=NULL,$icon=NULL){
		return (new HtmlDropdownItem(""))->asSearchInput($placeholder,$icon);
	}

	/**
	 * @return HtmlDropdownItem
	 */
	public static function divider(){
		return (new HtmlDropdownItem(""))->asDivider();
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @return HtmlDropdownItem
	 */
	public static function header($caption=NULL,$icon=NULL){
		return (new HtmlDropdownItem(""))->asHeader($caption,$icon);
	}

	/**
	 * @param string $caption
	 * @param string $color
	 * @return HtmlDropdownItem
	 */
	public static function circular($caption,$color){
		return (new HtmlDropdownItem(""))->asCircularLabel($caption,$color);
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @return HtmlDropdownItem
	 */
	public static function icon($caption,$icon){
		return (new HtmlDropdownItem(""))->asIcon($caption,$icon);
	}

	/**
	 * @param string $caption
	 * @param string $image
	 * @return HtmlDropdownItem
	 */
	public static function avatar($caption,$image){
		$dd=new HtmlDropdownItem("",$caption);
		$dd->asMiniAvatar($image);
		return $dd;
	}
}
