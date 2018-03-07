<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\base\traits\TextAlignmentTrait;
use Ajax\semantic\html\base\constants\HeaderSize;
use Ajax\semantic\html\elements\html5\HtmlImg;
use Ajax\semantic\html\base\traits\AttachedTrait;

/**
 * Semantic Header component
 * @see http://phpmv-ui.kobject.net/index/direct/main/43
 * @see http://semantic-ui.com/elements/header.html
 * @author jc
 * @version 1.001
 */
class HtmlHeader extends HtmlSemDoubleElement {
	use TextAlignmentTrait,AttachedTrait;
	protected $image;

	public function __construct($identifier, $niveau=1, $content=NULL, $type="page") {
		parent::__construct($identifier, "div", "ui header");
		$this->_template='<%tagName%  id="%identifier%" %properties%>%image%%wrapContentBefore%%content%%wrapContentAfter%</%tagName%>';
		if (isset($type)) {
			if ($type == "page") {
				$this->asPageHeader($niveau);
			} else
				$this->asContentHeader($niveau);
		}
		$this->content=$content;
	}

	public function asPageHeader($niveau) {
		$this->tagName="h" . $niveau;
	}

	public function asContentHeader($niveau) {
		$this->tagName="div";
		if (\is_int($niveau)) {
			$niveau=HeaderSize::getConstantValues()[$niveau];
		}
		$this->setSize($niveau);
	}

	public function asIcon($icon, $title, $subHeader=NULL) {
		$this->addToProperty("class", "icon");
		$this->image=new HtmlIcon("icon-" . $this->identifier, $icon);
		return $this->asTitle($title, $subHeader);
	}

	public function asImage($src, $title, $subHeader=NULL) {
		$this->image=new HtmlImg("img-" . $this->identifier, $src, $title);
		return $this->asTitle($title, $subHeader);
	}

	public function asTitle($title, $subHeader=NULL) {
		if (!\is_object($title)) {
			$this->content=new HtmlDoubleElement("content-" . $this->identifier, "div");
			$this->content->setContent($title);
		} else {
			$this->content=$title;
		}
		$this->content->setClass("content");
		if (isset($subHeader)) {
			$sub=new HtmlDoubleElement("subheader-" . $this->identifier, "div");
			$sub->setClass("sub header");
			$sub->setContent($subHeader);
			$this->content->addContent($sub);
		}
		return $this;
	}

	public function getImage() {
		return $this->image;
	}

	public function setCircular() {
		if (isset($this->image)) {
			$this->image->setCircular();
		}
		return $this;
	}

	public function setDividing() {
		return $this->addToProperty("class", "dividing");
	}

	public function setBlock() {
		return $this->addToProperty("class", "block");
	}

	public static function image($identifier, $image, $niveau=1, $header=NULL, $subHeader=NULL) {
		$result=new HtmlHeader($identifier, $niveau, $header);
		$result->asImage($image, $header, $subHeader);
		$result->getImage()->addToProperty("class", "mini rounded");
		return $result;
	}
}
