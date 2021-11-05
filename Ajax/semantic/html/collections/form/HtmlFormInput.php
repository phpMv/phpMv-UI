<?php
namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\collections\form\traits\TextFieldsTrait;
use Ajax\semantic\html\elements\HtmlInput;

class HtmlFormInput extends HtmlFormField {
	use TextFieldsTrait;

	public function __construct($identifier, $label = NULL, $type = "text", $value = NULL, $placeholder = NULL) {
		if (! isset($placeholder) && $type === "text")
			$placeholder = $label;
		parent::__construct("field-" . $identifier, new HtmlInput($identifier, $type, $value, $placeholder), $label);
		$this->_identifier = $identifier;
	}

	public function getDataField() {
		$field = $this->getField();
		if ($field instanceof HtmlInput)
			$field = $field->getDataField();
		return $field;
	}

	/**
	 * Changes the input type to password and adds an icon
	 *
	 * @param string $keyIcon
	 */
	public function asPassword($keyIcon = 'key') {
		$this->setInputType('password');
		if ($keyIcon != '') {
			$this->addIcon($keyIcon);
		}
	}

	/**
	 * Adds an action to show/hide the password
	 *
	 * @param string $buttonIcon
	 * @param string $keyIcon
	 * @param string $slashIcon
	 * @return mixed|\Ajax\semantic\html\elements\HtmlButton
	 */
	public function addTogglePasswordAction($buttonIcon = 'eye', $keyIcon = 'key', $slashIcon = 'slash') {
		$this->asPassword($keyIcon);
		$action = $this->addAction('see');
		$action->asIcon($buttonIcon);
		$action->onClick('$(this).find(".icon").toggleClass("' . $slashIcon . '");$(this).closest(".field").find("input").attr("type",(_,attr)=>(attr=="text")?"password":"text")');
		return $action;
	}
}
