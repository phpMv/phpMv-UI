<?php
namespace Ajax\semantic\html\modules;

use Ajax\JsUtils;
use Ajax\common\html\HtmlDoubleElement;
use Ajax\common\html\html5\HtmlInput;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\base\traits\LabeledIconTrait;
use Ajax\semantic\html\collections\form\traits\FieldTrait;
use Ajax\semantic\html\content\HtmlDropdownItem;
use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\service\JArray;

class HtmlDropdown extends HtmlSemDoubleElement {
	use FieldTrait,LabeledIconTrait {
		addIcon as addIconP;
	}

	protected $mClass = "menu";

	protected $mTagName = "div";

	protected $items = array();

	protected $_params = array(
		"action" => "nothing",
		"on" => "hover",
		"showOnFocus" => true
	);

	protected $input;

	protected $value;

	protected $_associative;

	protected $_multiple;

	public function __construct($identifier, $value = "", $items = array(), $associative = true) {
		parent::__construct($identifier, "div");
		$this->_template = include dirname(__FILE__) . '/../templates/tplDropdown.php';
		$this->setProperty("class", "ui dropdown");
		$this->_multiple = false;
		$content = [];
		if ($value instanceof HtmlSemDoubleElement) {
			$text = $value;
		} else {
			$text = new HtmlSemDoubleElement("text-" . $this->identifier, "div");
			$text->setClass("text");
			$this->setValue($value);
		}
		$content = [
			"text" => $text
		];
		$content["arrow"] = new HtmlIcon($identifier . "-icon", "dropdown");
		$this->content = $content;
		$this->tagName = "div";
		$this->_associative = $associative;
		$this->addItems($items);
	}

	public function getField() {
		return $this->input;
	}

	public function getDataField() {
		return $this->input;
	}

	public function addItem($item, $value = NULL, $image = NULL, $description = NULL) {
		$itemO = $this->beforeAddItem($item, $value, $image, $description);
		$this->items[] = $itemO;
		return $itemO;
	}

	public function addIcon($icon, $before = true, $labeled = false) {
		$this->removeArrow();
		$this->addIconP($icon, $before, $labeled);
		$elm = $this->getElementById("text-" . $this->identifier, $this->content);
		if (isset($elm)) {
			$elm->setWrapAfter("");
		}
		return $this;
	}

	public function addIcons($icons) {
		$count = $this->count();
		for ($i = 0; $i < \sizeof($icons) && $i < $count; $i ++) {
			$this->getItem($i)->addIcon($icons[$i]);
		}
	}

	/**
	 * Insert an item at a position
	 *
	 * @param mixed $item
	 * @param int $position
	 * @return HtmlDropdownItem
	 */
	public function insertItem($item, $position = 0) {
		$itemO = $this->beforeAddItem($item);
		$start = array_slice($this->items, 0, $position);
		$end = array_slice($this->items, $position);
		$start[] = $item;
		$this->items = array_merge($start, $end);
		return $itemO;
	}

	protected function removeArrow() {
		if (\sizeof($this->content) > 1) {
			unset($this->content["arrow"]);
			$this->content = \array_values($this->content);
		}
	}

	protected function beforeAddItem($item, $value = NULL, $image = NULL, $description = NULL) {
		$itemO = $item;
		if (\is_array($item)) {
			$description = JArray::getValue($item, "description", 3);
			$value = JArray::getValue($item, "value", 1);
			$image = JArray::getValue($item, "image", 2);
			$item = JArray::getValue($item, "item", 0);
		}
		if (! $item instanceof HtmlDropdownItem) {
			$itemO = new HtmlDropdownItem("dd-item-" . $this->identifier . "-" . \sizeof($this->items), $item, $value, $image, $description);
		} elseif ($itemO instanceof HtmlDropdownItem) {
			$this->addToProperty("class", "vertical");
		}
		return $itemO;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$this->addItem($function($object));
	}

	public function addInput($name) {
		if (! isset($name))
			$name = "input-" . $this->identifier;
		$this->setAction("activate");
		$this->input = new HtmlInput($name, "hidden");
		$this->input->setIdentifier("input-" . $this->identifier);
		return $this->input;
	}

	/**
	 * Adds a search input item
	 *
	 * @param string $placeHolder
	 * @param string $icon
	 * @return HtmlDropdownItem
	 */
	public function addSearchInputItem($placeHolder = NULL, $icon = NULL) {
		return $this->addItem(HtmlDropdownItem::searchInput($placeHolder, $icon));
	}

	/**
	 * Adds a divider item
	 *
	 * @return HtmlDropdownItem
	 */
	public function addDividerItem() {
		return $this->addItem(HtmlDropdownItem::divider());
	}

	/**
	 * Adds an header item
	 *
	 * @param string $caption
	 * @param string $icon
	 * @return HtmlDropdownItem
	 */
	public function addHeaderItem($caption = NULL, $icon = NULL) {
		return $this->addItem(HtmlDropdownItem::header($caption, $icon));
	}

	/**
	 * Adds an item with a circular label
	 *
	 * @param string $caption
	 * @param string $color
	 * @return HtmlDropdownItem
	 */
	public function addCircularLabelItem($caption, $color) {
		return $this->addItem(HtmlDropdownItem::circular($caption, $color));
	}

	/**
	 *
	 * @param string $caption
	 * @param string $image
	 * @return \Ajax\semantic\html\content\HtmlDropdownItem
	 */
	public function addMiniAvatarImageItem($caption, $image) {
		return $this->addItem(HtmlDropdownItem::avatar($caption, $image));
	}

	public function addItems($items) {
		if (\is_array($items) && $this->_associative) {
			foreach ($items as $k => $v) {
				$this->addItem($v)->setData($k);
			}
		} else {
			foreach ($items as $item) {
				$this->addItem($item);
			}
		}
	}

	/**
	 * Sets the values of a property for each item in the collection
	 *
	 * @param string $property
	 * @param array|mixed $values
	 * @return $this
	 */
	public function setPropertyValues($property, $values) {
		$i = 0;
		if (\is_array($values) === false) {
			$values = \array_fill(0, $this->count(), $values);
		}
		foreach ($values as $value) {
			$c = $this->items[$i ++];
			if (isset($c)) {
				$c->setProperty($property, $value);
			} else {
				return $this;
			}
		}
		return $this;
	}

	public function each($callBack) {
		foreach ($this->items as $index => $value) {
			$callBack($index, $value);
		}
		return $this;
	}

	public function getItem($index) {
		return $this->items[$index];
	}

	/**
	 *
	 * @return int
	 */
	public function count() {
		return \sizeof($this->items);
	}

	/**
	 *
	 * @param boolean $dropdown
	 */
	public function asDropdown($dropdown) {
		if ($dropdown === false) {
			$this->_template = include dirname(__FILE__) . '/../templates/tplDropdownMenu.php';
			$dropdown = "menu";
		} else {
			$dropdown = "dropdown";
			$this->mClass = "menu";
		}
		return $this->addToPropertyCtrl("class", $dropdown, array(
			"menu",
			"dropdown"
		));
	}

	public function setVertical() {
		return $this->addToPropertyCtrl("class", "vertical", array(
			"vertical"
		));
	}

	public function setInline() {
		return $this->addToPropertyCtrl("class", "inline", [
			"inline"
		]);
	}

	public function setSimple() {
		return $this->addToPropertyCtrl("class", "simple", array(
			"simple"
		));
	}

	public function asButton($floating = false) {
		$this->removeArrow();
		if ($floating)
			$this->addToProperty("class", "floating");
		$this->removePropertyValue("class", "selection");
		return $this->addToProperty("class", "button");
	}

	public function asSelect($name = NULL, $multiple = false, $selection = true) {
		$this->_multiple = $multiple;
		if (isset($name))
			$this->addInput($name);
		if ($multiple) {
			$this->addToProperty("class", "multiple");
		}
		if ($selection) {
			if ($this->propertyContains("class", "button") === false)
				$this->addToPropertyCtrl("class", "selection", array(
					"selection"
				));
		}
		return $this;
	}

	public function asSearch($name = NULL, $multiple = false, $selection = true) {
		$this->asSelect($name, $multiple, $selection);
		return $this->addToProperty("class", "search");
	}

	public function setSelect($name = NULL, $multiple = false) {
		$this->_template = '<%tagName% id="%identifier%" %properties%>%items%</%tagName%>';
		if (! isset($name))
			$name = "select-" . $this->identifier;
		$this->input = null;
		if ($multiple) {
			$this->setProperty("multiple", true);
			$this->addToProperty("class", "multiple");
		}
		$this->setAction("activate");
		$this->tagName = "select";
		$this->setProperty("name", $name);
		$this->content = null;
		foreach ($this->items as $item) {
			$item->asOption();
		}
		return $this;
	}

	public function asSubmenu($pointing = NULL) {
		$this->setClass("ui dropdown link item");
		if (isset($pointing)) {
			$this->setPointing($pointing);
		}
		return $this;
	}

	public function setPointing($value = Direction::NONE) {
		return $this->addToPropertyCtrl("class", $value . " pointing", Direction::getConstantValues("pointing"));
	}

	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

	public function setDefaultText($text) {
		$this->content["text"] = new HtmlSemDoubleElement("", "div", "default text", $text);
		return $this;
	}

	private function applyValue() {
		$value = $this->value;
		if (isset($this->input) && isset($value)) {
			$this->input->setProperty("value", $value);
		} else {
			$this->setProperty("value", $value);
		}
		$textElement = $this->getElementById("text-" . $this->identifier, $this->content);
		if (isset($textElement) && ($textElement instanceof HtmlDoubleElement) && ! $this->_multiple)
			$textElement->setContent($value);
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if ($this->propertyContains("class", "simple") === false) {
			if (isset($this->_bsComponent) === false) {
				$this->_bsComponent = $js->semantic()->dropdown("#" . $this->identifier, $this->_params);
				$this->_bsComponent->setItemSelector(".item");
			}
			$this->addEventsOnRun($js);
			return $this->_bsComponent;
		}
	}

	public function setCompact() {
		return $this->addToPropertyCtrl("class", "compact", array(
			"compact"
		));
	}

	public function setAction($action) {
		$this->_params["action"] = $action;
		return $this;
	}

	public function setOn($on) {
		$this->_params["on"] = $on;
		return $this;
	}

	public function setShowOnFocus($value) {
		$this->_params["showOnFocus"] = $value;
		return $this;
	}

	public function setAllowAdditions($value) {
		$this->_params["allowAdditions"] = $value;
		return $this;
	}

	public function setFullTextSearch($value) {
		$this->_params["fullTextSearch"] = $value;
		return $this;
	}

	public function compile(JsUtils $js = NULL, &$view = NULL) {
		$this->applyValue();
		return parent::compile($js, $view);
	}

	public function getInput() {
		return $this->input;
	}

	public function setIcon($icon = "dropdown") {
		$this->content["arrow"] = new HtmlIcon($this->identifier . "-icon", $icon);
		return $this;
	}

	public function jsAddItem($caption, $value = null) {
		$value = $value ?? $caption;
		$js = "var first=$('#{$this->identifier} .item').first();if(first!=undefined){var newItem =first.clone();first.parent().append(newItem);newItem.html({$caption});newItem.attr('data-value',{$value}).removeClass('active filtered');}";
		return $js;
	}

	public function setClearable($value) {
		$this->_params["clearable"] = $value;
		return $this;
	}

	/**
	 * Is called after a dropdown selection is added using a multiple select dropdown, only receives the added value
	 * Parameters addedValue, addedText, $addedChoice
	 */
	public function setOnAdd($jsCode) {
		$this->_params["onAdd"] = $jsCode;
		return $this;
	}

	/**
	 * Is called after a dropdown selection is removed using a multiple select dropdown, only receives the removed value
	 * Parameters removedValue, removedText, $removedChoice
	 */
	public function setOnRemove($jsCode) {
		$this->_params["onRemove"] = $jsCode;
		return $this;
	}
}
