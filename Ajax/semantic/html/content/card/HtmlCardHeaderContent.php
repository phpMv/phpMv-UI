<?php

namespace Ajax\semantic\html\content\card;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;

use Ajax\service\JArray;

class HtmlCardHeaderContent extends HtmlCardContent {

	public function __construct($identifier, $header=NULL, $metas=array(), $description=NULL) {
		parent::__construct($identifier, array ());
		if (isset($header)) {
			$this->setHeader($header);
		}
		$this->addMetas($metas);
		if (isset($description)) {
			$this->setDescription($description);
		}
	}

	public function setDescription($value) {
		$this->content["description"]=new HtmlSemDoubleElement("description-" . $this->identifier, "div", "description", $value);
	}

	public function setHeader($value) {
		$this->content["header"]=new HtmlSemDoubleElement("header-" . $this->identifier, "a", "header", $value);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->content=JArray::sortAssociative($this->content, [ "image","header","meta","description" ]);
		return parent::compile($js, $view);
	}
}