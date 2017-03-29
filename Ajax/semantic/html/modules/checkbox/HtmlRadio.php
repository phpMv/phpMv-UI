<?php

namespace Ajax\semantic\html\modules\checkbox;

use Ajax\semantic\html\modules\checkbox\AbstractCheckbox;

class HtmlRadio extends AbstractCheckbox {

	public function __construct($identifier, $name=NULL, $label=NULL, $value=NULL, $checkboxType=NULL) {
		parent::__construct($identifier, $name, $label, $value, "radio", "radio checkbox");
		if (isset($checkboxType)) {
			$this->setType($checkboxType);
		}
	}
}
