<?php

namespace Ajax\semantic\html\content;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\HtmlDoubleElement;
use Ajax\common\html\html5\HtmlImg;
use Ajax\common\html\html5\HtmlInput;
use Ajax\service\JArray;
use Ajax\semantic\html\base\traits\IconTrait;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\elements\HtmlIcon;

class HtmlDropdownItem extends HtmlSemDoubleElement {
	use IconTrait;
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
	}

	public function asOption(){
		$this->tagName="option";
		if($this->getProperty("data-value")!==null)
			$this->setProperty("value", $this->getProperty("data-value"));
	}

	/**
	 * @param string $image the image src
	 * @return \Ajax\common\html\html5\HtmlImg
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
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public function asIcon($caption,$icon){
		$this->setContent($caption);
		$this->addContent(new HtmlIcon("", $icon),true);
		return $this;
	}

	/**
	 * Adds a circular label to the item
	 * @param string $color
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public function asCircularLabel($caption,$color){
		$this->setContent($caption);
		$lbl=new HtmlLabel("");
		$lbl->setCircular()->setColor($color)->setEmpty();
		$this->addContent($lbl,true);
		return $this;
	}

	public function asSearchInput($placeholder=NULL,$icon=NULL){
		$this->setClass("ui icon search input");
		$input=new HtmlInput("search-".$this->identifier);
		if(isset($placeholder))
			$input->setProperty("placeholder", $placeholder);
		$this->content=$input;
		if(isset($icon))
			$this->addIcon($icon);
		return $this;
	}

	public function setContent($content){
		if($content==="-"){
			$this->asDivider();
		}elseif($content==="-search-"){
			$values=\explode(",",$content,-1);
			$this->asSearchInput(JArray::getDefaultValue($values, 0, "Search..."),JArray::getDefaultValue($values, 1, "search"));
		}else
			parent::setContent($content);
		return $this;
	}

	/**
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public function asDivider(){
		$this->content=NULL;
		$this->setClass("divider");
		return $this;
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public function asHeader($caption=NULL,$icon=NULL){
		$this->setClass("header");
		$this->content=$caption;
		if(isset($icon))
			$this->addIcon($icon,true);
		return $this;
	}

	/**
	 * @param string $placeholder
	 * @param string $icon
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public static function searchInput($placeholder=NULL,$icon=NULL){
		return (new HtmlDropdownItem(""))->asSearchInput($placeholder,$icon);
	}

	/**
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public static function divider(){
		return (new HtmlDropdownItem(""))->asDivider();
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public static function header($caption=NULL,$icon=NULL){
		return (new HtmlDropdownItem(""))->asHeader($caption,$icon);
	}

	/**
	 * @param string $caption
	 * @param string $color
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public static function circular($caption,$color){
		return (new HtmlDropdownItem(""))->asCircularLabel($caption,$color);
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public static function icon($caption,$icon){
		return (new HtmlDropdownItem(""))->asIcon($caption,$icon);
	}

	/**
	 * @param unknown $caption
	 * @param unknown $image
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public static function avatar($caption,$image){
		return (new HtmlDropdownItem("",$caption))->asMiniAvatar($image);
	}
}