<?php
namespace Ajax\semantic\html\collections\form;

use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\modules\HtmlDropdown;

class HtmlFormDropdown extends HtmlFormField {

	public function __construct($identifier, $items = array(), $label = NULL, $value = "", $multiple = false, $associative = true) {
		parent::__construct("field-" . $identifier, (new HtmlDropdown("dropdown-" . $identifier, $value, $items, $associative))->asSelect($identifier, $multiple), $label);
		$this->_identifier = $identifier;
	}

	public function setItems($items) {
		return $this->getField()->setItems($items);
	}

	public function addItem($item, $value = NULL, $image = NULL) {
		return $this->getField()->addItem($item, $value, $image);
	}

	public static function multipleDropdown($identifier, $items = array(), $label = NULL, $value = "", $associative = true) {
		return new HtmlFormDropdown($identifier, $items, $label, $value, true, $associative);
	}

	/**
	 *
	 * @return HtmlDropdown
	 */
	public function getDataField() {
		return $this->getField()->getInput();
	}

	public function asSelect($name = NULL, $multiple = false, $selection = true) {
		$this->getField()->asSelect($name, $multiple, $selection);
		return $this;
	}
	
	public function setClearable($value) {
		return $this->getField()->setClearable($value);
	}

	/**
	 *
	 * @param boolean $floating
	 * @return HtmlDropdown
	 */
	public function asButton($floating = false) {
		$field = $this->content["field"];
		$label = $this->content["label"];
		$field->addContent($label);
		$this->content = [
			"field" => $field
		];
		$this->content["field"]->asButton($floating);
		return $this->content["field"];
	}

	/**
	 *
	 * @param string $action
	 * @param string $direction
	 * @param string $icon
	 * @param boolean $labeled
	 * @return mixed|HtmlButton
	 */
	public function addAction($action, $direction = Direction::RIGHT, $icon = NULL, $labeled = false) {
		$this->getField()->setStyle('display:inline-block; width: auto');
		$actionO = $action;
		if (\is_object($action) === false) {
			$actionO = new HtmlButton("action-" . $this->identifier, $action);
			if (isset($icon))
				$actionO->addIcon($icon, true, $labeled);
		}
		$this->addContent($actionO, \strstr($direction, Direction::LEFT) !== false);
		return $actionO;
	}
}
