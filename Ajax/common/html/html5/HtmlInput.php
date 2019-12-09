<?php
namespace Ajax\common\html\html5;

use Ajax\common\html\HtmlSingleElement;
use Ajax\service\JString;
use Ajax\JsUtils;

class HtmlInput extends HtmlSingleElement {

	protected $_placeholder;

	public function __construct($identifier, $type = "text", $value = NULL, $placeholder = NULL) {
		parent::__construct($identifier, "input");
		$this->setProperty("name", $identifier);
		$this->setValue($value);
		$this->setPlaceholder($placeholder);
		$this->setProperty("type", $type);
	}

	public function setValue($value) {
		if (isset($value))
			$this->setProperty("value", $value);
		return $this;
	}

	public function setInputType($value) {
		return $this->setProperty("type", $value);
	}

	public function forceValue($value = 'true') {
		$this->wrap('<input type="hidden" value="false" name="' . $this->identifier . '"/>');
		$this->setValue($value);
		return $this;
	}

	public function setPlaceholder($value) {
		if (JString::isNotNull($value))
			$this->_placeholder = $value;
		return $this;
	}

	public function compile(JsUtils $js = NULL, &$view = NULL) {
		$value = $this->_placeholder;
		if (JString::isNull($value)) {
			if (JString::isNotNull($this->getProperty("name")))
				$value = \ucfirst($this->getProperty("name"));
		}
		$this->setProperty("placeholder", $value);
		return parent::compile($js, $view);
	}
}
