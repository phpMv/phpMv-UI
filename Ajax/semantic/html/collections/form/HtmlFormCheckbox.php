<?php

namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\base\constants\CheckboxType;
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\semantic\html\collections\form\traits\CheckboxTrait;

/**
 * Semantic Checkbox component
 * @see http://semantic-ui.com/collections/form.html#checkbox
 * @author jc
 * @version 1.001
 */
class HtmlFormCheckbox extends HtmlFormField {
	use CheckboxTrait;
	public function __construct($identifier, $label=NULL, $value=NULL, $type=NULL) {
		parent::__construct("field-".$identifier, new HtmlCheckbox($identifier,$label,$value,$type));
		$this->_identifier=$identifier;
	}

	public static function slider($identifier, $label="", $value=NULL) {
		return new HtmlFormCheckbox($identifier, $label, $value, CheckboxType::SLIDER);
	}

	public static function toggle($identifier, $label="", $value=NULL) {
		return new HtmlFormCheckbox($identifier, $label, $value, CheckboxType::TOGGLE);
	}
}
