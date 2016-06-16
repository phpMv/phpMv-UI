<?php

namespace Ajax\semantic\html\content\card;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\elements\html5\HtmlImg;

class HtmlCardContent extends HtmlSemDoubleElement {

	public function __construct($identifier, $content=array()) {
		parent::__construct($identifier, "div", "content", $content);
	}

	private function addElement($content, $baseClass) {
		$count=\sizeof($this->content);
		$result=new HtmlSemDoubleElement("element-" . $count . "-" . $this->identifier, "div", $baseClass, $content);
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

	public function addImage($src="", $alt="", $size=NULL) {
		$image=new HtmlImg("img-", $src, $alt);
		if (isset($size))
			$image->setSize($size);
		$this->content["image"]=$image;
		return $image;
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

	public function addHeaderContent($header, $metas=array(), $description=NULL) {
		$this->addElement($header, "header");
		$this->addMetas($metas);
		if (isset($description)) {
			$this->addElement($description, "description");
		}
		return $this;
	}
}