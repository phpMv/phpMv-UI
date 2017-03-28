<?php

namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\modules\checkbox\HtmlRadio;
use Ajax\semantic\html\collections\form\traits\CheckboxTrait;

/**
 * Semantic Radio component
 * @see http://semantic-ui.com/collections/form.html#radio
 * @author jc
 * @version 1.001
 */
class HtmlFormRadio extends HtmlFormField {
	use CheckboxTrait;

	public function __construct($identifier, $name=NULL, $label=NULL, $value=NULL, $type=NULL) {
		parent::__construct("field-".$identifier, new HtmlRadio($identifier, $name, $label, $value, $type));
		$this->_identifier=$identifier;
	}
}
