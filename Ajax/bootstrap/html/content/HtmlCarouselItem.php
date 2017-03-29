<?php

namespace Ajax\bootstrap\html\content;

use Ajax\common\html\BaseHtml;
use Ajax\JsUtils;

class HtmlCarouselItem extends BaseHtml {
	protected $imageSrc;
	protected $imageAlt;
	protected $caption;

	public function __construct($identifier) {
		parent::__construct($identifier);
		$this->_template=include __DIR__.'/../templates/tplCarouselItem.php';
	}

	public function getImageSrc() {
		return $this->imageSrc;
	}

	public function setImageSrc($imageSrc) {
		$this->imageSrc=$imageSrc;
		return $this;
	}

	public function getImageAlt() {
		return $this->imageAlt;
	}

	public function setImageAlt($imageAlt) {
		$this->imageAlt=$imageAlt;
		return $this;
	}

	public function getCaption() {
		return $this->caption;
	}

	public function setCaption($caption) {
		$this->caption=$caption;
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::run()
	 */
	public function run(JsUtils $js) {

	}

	public function __toString() {
		return $this->compile();
	}

	public function setClass($value) {
		$this->setProperty("class", $value);
	}
}
