<?php
namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlHeader;
use Ajax\JsUtils;
use Ajax\common\html\HtmlSingleElement;

class HtmlDimmer extends HtmlSemDoubleElement {

	private $_container;

	private $_inverted;

	public function __construct($identifier, $content = NULL) {
		parent::__construct($identifier, "div", "ui dimmer");
		$this->setContent($content);
		$this->_inverted = false;
	}

	public function setContent($content) {
		$this->content = new HtmlSemDoubleElement("content-" . $this->identifier, "div", "content", new HtmlSemDoubleElement("", "div", "center", $content));
		return $this;
	}

	public function asIcon($icon, $title, $subHeader = NULL) {
		$header = new HtmlHeader("header-" . $this->identifier);
		$header->asIcon($icon, $title, $subHeader);
		if ($this->_inverted === false)
			$header->setInverted();
		return $this->setContent($header);
	}

	public function asPage() {
		return $this->addToProperty("class", "page");
	}

	public function setInverted($recursive = true) {
		parent::setInverted($recursive);
		$this->_inverted = true;
		return $this;
	}

	public function run(JsUtils $js) {
		if ($this->_container instanceof HtmlSingleElement) {
			$this->_bsComponent = $js->semantic()->dimmer("#" . $this->_container->getIdentifier(), $this->_params);
		} else {
			$this->_bsComponent = $js->semantic()->dimmer("#" . $this->identifier, $this->_params);
		}
		return parent::run($js);
	}

	public function jsShow() {
		if (isset($this->_container))
			return '$("#.' . $this->_container->getIdentifier() . ').dimmer("show");';
	}

	public function setBlurring() {
		return $this->addToProperty("class", "blurring");
	}

	public function setParams($_params) {
		$this->_params = $_params;
		return $this;
	}

	public function setContainer($_container) {
		$this->_container = $_container;
		return $this;
	}

	public function setClosable($closable) {
		$this->_params['closable'] = $closable;
		return $this;
	}
}
