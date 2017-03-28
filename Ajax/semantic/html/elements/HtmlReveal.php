<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\RevealType;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\common\html\html5\HtmlImg;
use Ajax\common\html\HtmlSingleElement;

class HtmlReveal extends HtmlSemDoubleElement {

	public function __construct($identifier, $visibleContent,$hiddenContent,$type=RevealType::FADE,$attributeType=NULL) {
		parent::__construct($identifier, "div", "ui reveal");
		$this->setElement(0, $visibleContent);
		$this->setElement(1, $hiddenContent);
		$this->setType($type,$attributeType);
	}

	private function setElement($index,$content){
		if(!$content instanceof HtmlSingleElement){
			$content=new HtmlLabel("",$content);
		}
		if($content instanceof HtmlSemDoubleElement){
			$content=new HtmlSemDoubleElement($this->identifier."-".$index,"div","",$content);
		}elseif ($content instanceof HtmlImg){
			$this->addToPropertyCtrl("class", "image", array("image"));
		}
		$content->addToProperty("class",(($index===0)?"visible":"hidden")." content");
		$this->content[$index]=$content;
		return $this;
	}

	public function setVisibleContent($visibleContent){
		return $this->setElement(0, $visibleContent);
	}

	public function setHiddenContent($hiddenContent){
		return $this->setElement(1, $hiddenContent);
	}

	public function getVisibleContent(){
		return $this->content[0];
	}

	public function getHiddenContent(){
		return $this->content[1];
	}

	public function setType($type,$attribute=NULL){
		$this->addToPropertyCtrl("class", $type, RevealType::getConstants());
		if(isset($attribute)){
			$this->addToPropertyCtrl("class", $attribute, Direction::getConstants());
		}
		return $this;
	}

	public function setFade($attribute=NULL){
		return $this->setType(RevealType::FADE,$attribute);
	}

	public function setMove($attribute=NULL){
		return $this->setType(RevealType::MOVE,$attribute);
	}

	public function setRotate($attribute=NULL){
		return $this->setType(RevealType::ROTATE,$attribute);
	}

	public function setCircular(){
		return $this->addToProperty("class", "circular");
	}

}
