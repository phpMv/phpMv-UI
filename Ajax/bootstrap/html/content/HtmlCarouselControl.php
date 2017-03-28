<?php

namespace Ajax\bootstrap\html\content;

use Ajax\bootstrap\html\HtmlLink;

class HtmlCarouselControl extends HtmlLink {
	protected $sens;
	protected $glyphIcon;
	protected $caption;

	public function __construct($identifier) {
		parent::__construct($identifier);
		$this->_template=include __DIR__.'/../templates/tplCarouselControl.php';
	}

	public function getSens() {
		return $this->sens;
	}

	public function setSens($sens) {
		$this->sens=$sens;
		return $this;
	}

	public function getGlyphIcon() {
		return $this->glyphIcon;
	}

	public function setGlyphIcon($glyphIcon) {
		$this->glyphIcon=$glyphIcon;
		return $this;
	}

	public function getCaption() {
		return $this->caption;
	}

	public function setCaption($caption) {
		$this->caption=$caption;
		return $this;
	}
}
