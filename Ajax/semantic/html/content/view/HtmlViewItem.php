<?php

namespace Ajax\semantic\html\content\view;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlHeader;
use Ajax\JsUtils;
use Ajax\service\JArray;

use Ajax\semantic\html\elements\HtmlImage;
use Ajax\semantic\html\elements\HtmlReveal;
use Ajax\semantic\html\base\constants\RevealType;
use Ajax\semantic\html\elements\HtmlButtonGroups;
use Ajax\semantic\html\content\view\HtmlViewContent;
use Ajax\semantic\html\content\view\HtmlViewHeaderContent;

abstract class HtmlViewItem extends HtmlSemDoubleElement {

	protected $_sortContentBy=[];

	public function __construct($identifier,$baseClass,$content=[]) {
		parent::__construct($identifier, "div", $baseClass, $content);
	}

	private function createContent($content, $baseClass="content") {
		$count=\sizeof($this->content);
		$result=new HtmlViewContent("content-" . $count . "-" . $this->identifier, $content);
		$result->setClass($baseClass);
		return $result;
	}

	private function addElementInContent($key, $element) {
		if (\array_key_exists($key, $this->content) === false) {
			$this->content[$key]=array ();
		}
		$this->content[$key][]=$element;
		return $element;
	}

	private function getPart($part, $index=NULL) {
		if (\array_key_exists($part, $this->content)) {
			if (isset($index))
				return $this->content[$part][$index];
			return $this->content[$part];
		}
		return NULL;
	}

	public function addHeader($header, $niveau=4, $type="page") {
		if (!$header instanceof HtmlHeader) {
			$header=new HtmlHeader("header-" . $this->identifier, $niveau, $header, $type);
		}
		$this->content["header"]=$this->createContent($header);
	}

	public function addImage($image, $title="") {
		if (!$image instanceof HtmlImage) {
			$image=new HtmlImage("image-" . $this->identifier, $image, $title);
		}
		$image->setClass("ui image");
		return $this->addElementInContent("content", $image);
	}

	public function addReveal($visibleContent, $hiddenContent=NULL, $type=RevealType::FADE, $attributeType=NULL) {
		$reveal=$visibleContent;
		if (!$visibleContent instanceof HtmlReveal) {
			$reveal=new HtmlReveal("reveral-" . $this->identifier, $visibleContent, $hiddenContent, $type, $attributeType);
		}
		return $this->addElementInContent("content", $reveal);
	}

	public function addRevealImage($visibleContent, $hiddenContent=NULL, $type=RevealType::FADE, $attributeType=NULL) {
		$reveal=$visibleContent;
		if (!$visibleContent instanceof HtmlReveal) {
			return $this->addReveal(new HtmlImage("", $visibleContent), new HtmlImage("", $hiddenContent), $type, $attributeType);
		}
		return $this->addElementInContent("content", $reveal);
	}

	public function addExtraContent($content=array()) {
		return $this->addElementInContent("extra-content", $this->createContent($content, "extra content"));
	}

	public function addContent($content=array(), $before=false) {
		if (!$content instanceof HtmlViewContent) {
			$content=$this->createContent($content);
		}
		return $this->addElementInContent("content", $content);
	}

	/**
	 * @param array $elements
	 * @param boolean $asIcons
	 * @return \Ajax\semantic\html\elements\HtmlButtonGroups
	 */
	public function addButtons($elements=array(), $asIcons=false){
		$buttons=new HtmlButtonGroups("buttons-".$this->identifier,$elements,$asIcons);
		$this->addElementInContent("content", $buttons);
		return $buttons;
	}



	public function addItemHeaderContent($header, $metas=array(), $description=NULL,$extra=NULL) {
		$count=\sizeof($this->content);
		return $this->addElementInContent("content", new HtmlViewHeaderContent("content-" . $count . "-" . $this->identifier, $header, $metas, $description,$extra));
	}

	public function addItemContent($content=array()) {
		$count=\sizeof($this->content);
		return $this->addElementInContent("content", new HtmlViewContent("content-" . $count . "-" . $this->identifier, $content));
	}

	public function getItemContent($index=NULL) {
		return $this->getPart("content", $index);
	}

	public function getItemExtraContent() {
		return $this->getPart("extra-content");
	}

	public function getItemImage() {
		return $this->getPart("image");
	}

	public function getItemHeader() {
		return $this->getPart("header");
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->content=JArray::sortAssociative($this->content, [ "header","image","content","extra-content" ]);
		return parent::compile($js, $view);
	}

	public function asLink($href="",$target=NULL) {
		$this->addToProperty("class", "link");
		if ($href !== "") {
			$this->setProperty("href", $href);
			if (isset($target))
				$this->setProperty("target", $target);
		}
		return $this;
	}
}