<?php

namespace Ajax\semantic\html\modules\checkbox;

use Ajax\semantic\html\base\constants\CheckboxType;

class HtmlCheckbox extends AbstractCheckbox {

	public function __construct($identifier, $label=NULL, $value=NULL, $checkboxType=NULL) {
		parent::__construct($identifier, $identifier, $label, $value, "checkbox", "checkbox");
		if (isset($checkboxType)) {
			$this->setType($checkboxType);
		}
	}

	public static function slider($identifier, $label="", $value=NULL) {
		return new HtmlCheckbox($identifier, $label, $value, CheckboxType::SLIDER);
	}

	public static function toggle($identifier, $label="", $value=NULL) {
		return new HtmlCheckbox($identifier, $label, $value, CheckboxType::TOGGLE);
	}
}
