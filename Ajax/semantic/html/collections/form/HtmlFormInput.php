<?php
namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\collections\form\traits\TextFieldsTrait;
use Ajax\semantic\html\elements\HtmlInput;

class HtmlFormInput extends HtmlFormField {
	use TextFieldsTrait;

	const TOGGLE_CLICK = 0;

	const TOGGLE_MOUSEDOWN = 1;

	const TOGGLE_INTERVAL = 2;

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
	 * Adds an action to show/hide the password.
	 *
	 * @param string $buttonIcon
	 * @param string $keyIcon
	 * @param string $slashIcon
	 * @param string $type
	 *        	one of TOGGLE_CLICK, TOGGLE_MOUSEDOWN, TOGGLE_INTERVAL
	 * @return mixed|\Ajax\semantic\html\elements\HtmlButton
	 */
	public function addTogglePasswordAction($buttonIcon = 'eye', $keyIcon = 'key', $slashIcon = 'slash', $type = 0) {
		$this->asPassword($keyIcon);
		$action = $this->addAction('see');
		$action->asIcon($buttonIcon);
		switch ($type) {
			case self::TOGGLE_CLICK:
				$action->onClick('let th=$(this);' . $this->getJsToggle($slashIcon, '(_,attr)=>(attr=="text")?"password":"text"', 'toggle'));
				break;
			case self::TOGGLE_MOUSEDOWN:
				$action->onClick('');
				$action->addEvent('mousedown', 'let th=$(this);' . $this->getJsToggle($slashIcon, '"text"', 'add'));
				$action->addEvent('mouseup', 'let th=$(this);' . $this->getJsToggle($slashIcon, '"password"', 'remove'));
				$action->addEvent('mouseout', 'let th=$(this);' . $this->getJsToggle($slashIcon, '"password"', 'remove'));
				break;
			case self::TOGGLE_INTERVAL:
				$action->onClick('let th=$(this);' . $this->getJsToggle($slashIcon, '"text"', 'add') . 'setTimeout(function(){ ' . $this->getJsToggle($slashIcon, '"password"', 'remove') . ' }, 5000);');
				break;
		}
		return $action;
	}

	private function getJsToggle($slashIcon, $type, $actionClass) {
		return 'th.find(".icon").' . $actionClass . 'Class("' . $slashIcon . '");th.closest(".field").find("input").attr("type",' . $type . ');';
	}
}
