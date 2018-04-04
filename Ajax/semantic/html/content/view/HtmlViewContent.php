<?php

namespace Ajax\semantic\html\content\view;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\elements\html5\HtmlImg;
use Ajax\JsUtils;
use Ajax\service\JArray;
use Ajax\semantic\html\elements\HtmlButtonGroups;

class HtmlViewContent extends HtmlSemDoubleElement {
	use ContentPartTrait;
	public function __construct($identifier, $content=array()) {
		parent::__construct($identifier, "div", "content",[]);
		$this->setContent($content);
	}

	public function setContent($value){
		if (\is_array($value)) {
			$header=JArray::getValue($value, "header", 0);
			$metas=JArray::getValue($value, "metas", 1);
			$description=JArray::getValue($value, "description", 2);
			$image=JArray::getValue($value, "image", 3);
			$extra=JArray::getValue($value, "extra", 4);
			if (isset($image)) {
				$this->addImage($image);
			}
			$this->addHeaderContent($header, $metas, $description,$extra);
		} else
			$this->addContent($value);
	}

	public function addElement($content, $baseClass="") {
		$count=\sizeof($this->content);
		$result=new HtmlViewContent("element-" . $count . "-" . $this->identifier, $content);
		$result->setClass($baseClass);
		$this->addContent($result);
		return $result;
	}

	public function addMeta($value, $direction=Direction::LEFT) {
		if (\array_key_exists("meta", $this->content) === false) {
			$this->content["meta"]=new HtmlSemDoubleElement("meta-" . $this->identifier, "div", "meta", array ());
		}
		if ($direction === Direction::RIGHT) {
			$value=new HtmlSemDoubleElement("", "span", "", $value);
			$value->setFloated($direction);
		}
		$this->content["meta"]->addContent($value);
		return $this->content["meta"];
	}

	public function addExtra($value) {
		if (\array_key_exists("extra", $this->content) === false) {
			$this->content["extra"]=new HtmlSemDoubleElement("extra-" . $this->identifier, "div", "extra", array ());
		}
		$this->content["extra"]->addContent($value);
		return $this->content["extra"];
	}

	public function addImage($src="", $alt="", $size=NULL) {
		$image=new HtmlImg("img-", $src, $alt);
		if (isset($size))
			$image->setSize($size);
		$this->content['image']=$image;
		return $image;
	}

	/**
	 * @param array $elements
	 * @param boolean $asIcons
	 * @param string $part
	 * @param boolean $before
	 * @return HtmlButtonGroups
	 */
	public function addContentButtons($elements=array(), $asIcons=false,$part="extra",$before=false){
		$buttons=new HtmlButtonGroups("buttons-".$this->identifier,$elements,$asIcons);
		$this->addElementInPart($buttons,$part, $before,true);
		return $buttons;
	}

	public function addMetas($metas) {
		if (\is_array($metas)) {
			foreach ( $metas as $meta ) {
				$this->addMeta($meta);
			}
		} else
			$this->addMeta($metas);
		return $this;
	}

	public function addContentIcon($icon, $caption=NULL, $direction=Direction::LEFT) {
		if ($direction === Direction::RIGHT) {
			if (isset($caption)) {
				$result=new HtmlSemDoubleElement("", "span", "", $caption);
				$result->addIcon($icon);
				$this->addContent($result);
			} else {
				$result=new HtmlIcon("", $icon);
				$this->addContent($result);
			}
			$result->setFloated($direction);
		} else {
			$this->addIcon($icon);
			$result=$this->addContent($caption);
		}
		return $result;
	}

	public function addContentText($caption, $direction=Direction::LEFT) {
		if ($direction === Direction::RIGHT) {
			$result=new HtmlSemDoubleElement("", "span", "", $caption);
			$this->addContent($result);
			$result->setFloated($direction);
		} else
			$result=$this->addContent($caption);
		return $result;
	}

	public function addContentIcons($icons, $direction=Direction::LEFT) {
		foreach ( $icons as $icon ) {
			$this->addContentIcon($icon, NULL, $direction);
		}
		return $this;
	}

	public function addHeaderContent($header, $metas=array(), $description=NULL,$extra=NULL) {
		if(isset($header))
			$this->addElement($header, "header");
		$this->addMetas($metas);
		if (isset($description)) {
			$this->addElement($description, "description");
		}
		if(isset($extra)){
			$this->addExtra($extra);
		}
		return $this;
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		return parent::compile($js, $view);
	}
}
