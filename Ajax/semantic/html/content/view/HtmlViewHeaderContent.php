<?php

namespace Ajax\semantic\html\content\view;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;

use Ajax\service\JArray;

class HtmlViewHeaderContent extends HtmlViewContent {

	public function __construct($identifier, $header=NULL, $metas=array(), $description=NULL,$extra=null) {
		parent::__construct($identifier, array ());
		if (isset($header)) {
			$this->setHeader($header);
		}
		$this->addMetas($metas);
		if (isset($description)) {
			$this->setDescription($description);
		}
		if (isset($extra)) {
			$this->setExtra($extra);
		}
	}

	public function setDescription($value) {
		$this->content["description"]=new HtmlSemDoubleElement("description-" . $this->identifier, "div", "description", $value);
	}

	public function setHeader($value) {
		$this->content["header"]=new HtmlSemDoubleElement("header-" . $this->identifier, "a", "header", $value);
	}

	public function setExtra($value) {
		$this->content["extra"]=new HtmlSemDoubleElement("extra-" . $this->identifier, "a", "extra", $value);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->content=JArray::sortAssociative($this->content, [ "image","header","meta","description","extra" ]);
		return parent::compile($js, $view);
	}
}