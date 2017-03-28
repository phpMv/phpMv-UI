<?php

namespace Ajax\bootstrap\html;

use Ajax\JsUtils;
use Ajax\bootstrap\html\content\HtmlCarouselControl;
use Ajax\bootstrap\html\base\CssRef;
use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\content\HtmlCarouselItem;

use Ajax\common\html\BaseHtml;
/**
 * Composant Twitter Bootstrap Carousel
 * @see http://getbootstrap.com/components/#carousel
 * @author jc
 * @version 1.001
 */
class HtmlCarousel extends BaseHtml {
	protected $indicators=array ();
	protected $slides=array ();
	protected $leftControl="";
	protected $rightControl="";
	protected $_base="";
	protected $_glyphs=array ();

	public function __construct($identifier, $images=NULL) {
		parent::__construct($identifier);
		$this->_template=include 'templates/tplCarousel.php';
		if ($images!=NULL) {
			if (\is_array($images)) {
				$this->fromArray($images);
			}
		}
	}

	public function getBase() {
		return $this->_base;
	}

	public function setBase($_base) {
		foreach ($this->slides as $slide){
			$imgSrc=$slide->getImageSrc();
			$slide->setImageSrc(str_replace($this->_base.$imgSrc, $_base.$imgSrc, $imgSrc));
		}
		$this->_base=$_base;
		return $this;
	}

	public function run(JsUtils $js) {
		$this->_bsComponent=$js->bootstrap()->carousel("#".$this->identifier);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	private function createControls() {
		$this->rightControl=$this->createControl("next", "right");
		$this->leftControl=$this->createControl("previous", "left");
	}

	/**
	 *
	 * @param string $caption
	 * @param string $sens
	 * @return HtmlCarouselControl|string
	 */
	private function createControl($caption="next", $sens="left") {
		$control=new HtmlCarouselControl($sens."-ctrl-".$this->identifier);
		$control->setClass($sens." carousel-control");
		$control->setProperty("data-slide", substr($caption, 0, 4));
		$control->setHref("#".$this->identifier);
		$control->setRole("button");
		$control->setCaption(ucfirst($caption));
		$control->setGlyphIcon($this->getGlyph($sens));
		return $control;
	}

	private function getGlyph($sens="left") {
		if (array_key_exists($sens, $this->_glyphs))
			return $this->_glyphs [$sens];
		return "glyphicon-chevron-".$sens;
	}

	public function setRightGlyph($glyphicon) {
		$glyphs=CssRef::glyphIcons();
		if (array_search($glyphicon, $glyphs)!==false)
			$this->_glyphs ["right"]=$glyphicon;
	}

	public function setLeftGlyph($glyphicon) {
		$glyphs=CssRef::glyphIcons();
		if (array_search($glyphicon, $glyphs)!==false)
			$this->_glyphs ["left"]=$glyphicon;
	}

	public function addImage($imageSrc, $imageAlt="", $caption=NULL, $description=NULL) {
		if(\is_array($imageSrc)){
			$this->addImage($imageSrc[0],@$imageSrc[1],@$imageSrc[2],@$imageSrc[3]);
		}else{
			$image=new HtmlCarouselItem("item-".$this->identifier);
			$image->setImageSrc($this->_base.$imageSrc);
			$image->setImageAlt($imageAlt);
			$image->setClass("item");
			if (isset($caption)) {
				$optCaption="<h3>".$caption."</h3>";
				if (isset($description)) {
					$optCaption.="<p>".$description."</p>";
				}
				$image->setCaption($optCaption);
			}
			$this->slides []=$image;
			$this->createIndicator();
		}
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromArray()
	 */
	public function fromArray($array) {
		if (\is_array($array) && sizeof($array)>0) {
			foreach ( $array as $value ) {
				if (\is_array($value)) {
					$this->addImage($value ["src"], @$value ["alt"], @$value ["caption"], @$value ["description"]);
				} else {
					$this->addImage($value);
				}
			}
		}
		return $this;
	}

	private function createIndicator() {
		$indicator=new HtmlBsDoubleElement("indicator-".$this->identifier);
		$indicator->setProperty("data-target", "#".$this->identifier);
		$indicator->setProperty("data-slide-to", sizeof($this->indicators));
		$indicator->setTagName("li");
		$this->indicators []=$indicator;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->slides [0]->setClass("item active");
		$this->indicators [0]->setClass("active");
		$this->createControls();
		return parent::compile($js, $view);
	}


	/* (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$this->addImage($function($object));
	}
}
