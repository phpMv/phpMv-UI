<?php
namespace Ajax\semantic\html\base\traits;

use Ajax\service\JArray;
use Ajax\service\JString;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\content\HtmlDropdownItem;
use Ajax\semantic\html\content\HtmlMenuItem;

/**
 * @author jc
 * @property string $identifier
 * @property mixed $content
 * @property string $tagName
 */
trait MenuItemTrait {
	abstract public function addToProperty($name, $value, $separator=" ");
	abstract public function setClass($classNames);
	abstract public function addIcon($icon, $before=true);

	public function setContent($content){
		if($content==="-"){
			$this->asDivider();
		}elseif($content==="-search-"){
			$values=\explode(",",$content,-1);
			$this->asSearchInput(JArray::getDefaultValue($values, 0, "Search..."),JArray::getDefaultValue($values, 1, "search"));
		}elseif(JString::startswith($content, "-")){
			$content=\ltrim($content,"-");
			$this->asHeader($content);
		}else
			parent::setContent($content);
		return $this;
	}

	/**
	 * @param string $placeholder
	 * @param string $icon
	 * @return HtmlDropdownItem|HtmlMenuItem
	 */
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

	/**
	 * @return HtmlDropdownItem|HtmlMenuItem
	 */
	public function asDivider(){
		$this->content=NULL;
		$this->tagName="div";
		$this->setClass("divider");
		return $this;
	}

	/**
	 * @param string $caption
	 * @param string $icon
	 * @return HtmlDropdownItem|HtmlMenuItem
	 */
	public function asHeader($caption=NULL,$icon=NULL){
		$this->setClass("header");
		$this->tagName="div";
		$this->content=$caption;
		if(isset($icon))
			$this->addIcon($icon,Direction::LEFT);
			return $this;
	}

	public function setPosition($direction){
		$this->addToProperty("class",$direction);
	}
}
