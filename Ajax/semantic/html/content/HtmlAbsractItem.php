<?php

namespace Ajax\semantic\html\content;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\JsUtils;

use Ajax\service\JArray;
use Ajax\semantic\html\elements\html5\HtmlImg;

abstract class HtmlAbsractItem extends HtmlSemDoubleElement {

	public function __construct($identifier, $baseClass,$content=NULL) {
		parent::__construct($identifier, "div", $baseClass);
		$this->content=array();
		$this->initContent($content);
	}

	abstract protected function initContent($content);

	public function setIcon($icon){
		$this->content["icon"]=new HtmlIcon("icon-".$this->identifier, $icon);
	}

	public function removeIcon(){
		if(isset($this->content["icon"]))
			unset($this->content["icon"]);
		return $this;
	}

	public function setImage($image){
		$image=new HtmlImg("icon-".$this->identifier, $image);
		$image->asAvatar();
		$this->content["image"]=$image;
	}

	private function _getContent($key="content",$baseClass="content"){
		if(!is_array($this->content)){
			$this->content=[$this->content];
		}
		if(\array_key_exists($key, $this->content)===false){
			$this->content[$key]=new HtmlSemDoubleElement($key."-".$this->identifier,"div",$baseClass);
		}
		return $this->content[$key];
	}
	
	private function _getRightContent(){
		return $this->_getContent("right-content","right floated content");
	}
	
	public function addContent($content,$before=false){
		$this->_getContent("content")->addContent($content,$before);
		return $this;
	}
	
	public function addRightContent($content,$before=false){
		$this->_getRightContent()->addContent($content,$before);
		return $this;
	}

	public function setTitle($title,$description=NULL,$baseClass="title"){
		$title=new HtmlSemDoubleElement("","div",$baseClass,$title);
		$content=$this->_getContent();
		$content->addContent($title);
		if(isset($description)){
			$description=new HtmlSemDoubleElement("","div","description",$description);
			$content->addContent($description);
		}
		return $this;
	}

	public function getPart($partName="header"){
		$content=\array_merge($this->_getContent()->getContent(),array(@$this->content["icon"],@$this->content["image"]));
		return $this->getElementByPropertyValue("class", $partName, $content);
	}

	public function setActive($value=true){
		if($value){
			$this->setTagName("div");
			$this->removeProperty("href");
			$this->addToPropertyCtrl("class", "active", array("active"));
		}else{
			$this->removePropertyValue("class", "active");
		}
		return $this;
	}

	public function asLink($href=NULL,$part=NULL){
		$this->setTagName("a");
		if(isset($href))
			$this->setProperty("href", $href);
		return $this;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if(\is_array($this->content) && JArray::isAssociative($this->content))
			$this->content=JArray::sortAssociative($this->content, [ "right-content","icon","image","content" ]);
		return parent::compile($js, $view);
	}
}
