<?php
namespace Ajax\common;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\widgets\datatable\PositionInTable;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\widgets\base\FieldAsTrait;
use Ajax\semantic\html\elements\HtmlButtonGroups;
use Ajax\semantic\widgets\base\InstanceViewer;
use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\service\JArray;
use Ajax\service\Javascript;
use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\JsUtils;
use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\semantic\html\collections\form\traits\FormTrait;
use Ajax\common\html\BaseWidget;
use Ajax\semantic\html\modules\HtmlModal;
use Ajax\common\html\traits\BaseHooksTrait;

abstract class Widget extends HtmlDoubleElement {
	use FieldAsTrait,FormTrait,BaseHooksTrait;

	/**
	 *
	 * @var string classname
	 */
	protected $_model;

	protected $_modelInstance;

	/**
	 *
	 * @var InstanceViewer
	 */
	protected $_instanceViewer;

	/**
	 *
	 * @var HtmlMenu
	 */
	protected $_toolbar;

	/**
	 *
	 * @var string
	 */
	protected $_toolbarPosition;

	/**
	 *
	 * @var boolean
	 */
	protected $_edition;

	/**
	 *
	 * @var HtmlForm
	 */
	protected $_form;

	protected $_generated;

	protected $_runned;

	protected $_hasRules;

	public function __construct($identifier, $model, $modelInstance = NULL) {
		parent::__construct($identifier);
		$this->_template = "%wrapContentBefore%%content%%wrapContentAfter%";
		$this->setModel($model);
		if (isset($modelInstance)) {
			if (\is_object($modelInstance)) {
				$this->_model = \get_class($modelInstance);
			}
			$this->show($modelInstance);
		}
		$this->_generated = false;
	}

	protected function _init($instanceViewer, $contentKey, $content, $edition) {
		$this->_instanceViewer = $instanceViewer;
		$this->content = [
			$contentKey => $content
		];
		$this->_self = $content;
		$this->_toolbarPosition = PositionInTable::BEFORETABLE;
		$this->_edition = $edition;
	}

	/**
	 *
	 * @param int|string $fieldName
	 * @return int|string|boolean
	 */
	protected function _getIndex($fieldName) {
		$index = $fieldName;
		if (\is_string($fieldName)) {
			$fields = $this->_instanceViewer->getVisibleProperties();
			$index = \array_search($fieldName, $fields);
		}
		return $index;
	}

	protected function _getFieldIdentifier($prefix, $name = "") {
		return $this->identifier . "-{$prefix}-" . $this->_instanceViewer->getIdentifier();
	}

	protected function _getFieldName($index) {
		return $this->_instanceViewer->getFieldName($index);
	}

	protected function _getFieldCaption($index) {
		return $this->_instanceViewer->getCaption($index);
	}

	abstract protected function _setToolbarPosition($table, $captions = NULL);

	public function show($modelInstance) {
		if (\is_array($modelInstance)) {
			$modelInstance = \json_decode(\json_encode($modelInstance), FALSE);
		}
		$this->_modelInstance = $modelInstance;
	}

	public function getModel() {
		return $this->_model;
	}

	public function setModel($_model) {
		$this->_model = $_model;
		return $this;
	}

	public function getInstanceViewer() {
		return $this->_instanceViewer;
	}

	public function setInstanceViewer($_instanceViewer) {
		$this->_instanceViewer = $_instanceViewer;
		return $this;
	}

	abstract public function getHtmlComponent();

	public function setAttached($value = true) {
		return $this->getHtmlComponent()->setAttached($value);
	}

	/**
	 * Associates a $callback function after the compilation of the field at $index position
	 * The $callback function can take the following arguments : $field=>the compiled field, $instance : the active instance of the object, $index: the field position
	 *
	 * @param int $index
	 *        	postion of the compiled field
	 * @param callable $callback
	 *        	function called after the field compilation
	 * @return Widget
	 */
	public function afterCompile($index, $callback) {
		$index = $this->_getIndex($index);
		if ($index !== false) {
			$this->_instanceViewer->afterCompile($index, $callback);
		}
		return $this;
	}

	public function setColor($color) {
		return $this->getHtmlComponent()->setColor($color);
	}

	public function setCaptions($captions) {
		$this->_instanceViewer->setCaptions($captions);
		return $this;
	}

	public function setCaption($index, $caption) {
		$this->_instanceViewer->setCaption($this->_getIndex($index), $caption);
		return $this;
	}

	public function setFields($fields) {
		$this->_instanceViewer->setVisibleProperties($fields);
		return $this;
	}

	public function addField($field, $key = null) {
		$this->_instanceViewer->addField($field, $key);
		return $this;
	}

	public function addFields($fields) {
		$this->_instanceViewer->addFields($fields);
		return $this;
	}

	public function countFields() {
		return $this->_instanceViewer->visiblePropertiesCount();
	}

	public function addMessage($attributes = NULL, $fieldName = "message") {
		$this->_instanceViewer->addField($fieldName);
		$count = $this->_instanceViewer->visiblePropertiesCount();
		return $this->fieldAsMessage($count - 1, $attributes);
	}

	public function addErrorMessage() {
		return $this->addMessage([
			"error" => true
		], "message");
	}

	public function insertField($index, $field, $key = null) {
		$index = $this->_getIndex($index);
		$this->_instanceViewer->insertField($index, $field, $key);
		return $this;
	}

	public function insertInField($index, $field, $key = null) {
		$index = $this->_getIndex($index);
		if ($index !== false) {
			$this->_instanceViewer->insertInField($index, $field, $key);
		}
		return $this;
	}

	/**
	 * Defines the function which displays the field value
	 *
	 * @param int|string $index
	 *        	index or name of the field to display
	 * @param callable $callback
	 *        	function parameters are : $value : the field value, $instance : the active instance of model, $fieldIndex : the field index, $rowIndex : the row index
	 * @return Widget
	 */
	public function setValueFunction($index, $callback) {
		$index = $this->_getIndex($index);
		if ($index !== false) {
			$this->_instanceViewer->setValueFunction($index, $callback);
		}
		return $this;
	}

	public function setIdentifierFunction($callback) {
		$this->_instanceViewer->setIdentifierFunction($callback);
		return $this;
	}

	/**
	 *
	 * @return \Ajax\semantic\html\collections\menus\HtmlMenu
	 */
	public function getToolbar() {
		if (isset($this->_toolbar) === false) {
			$this->_toolbar = new HtmlMenu("toolbar-" . $this->identifier);
		}
		return $this->_toolbar;
	}

	/**
	 * Adds a new element in toolbar
	 *
	 * @param mixed $element
	 * @param callable $callback
	 *        	function to call on $element
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addInToolbar($element, $callback = NULL) {
		$tb = $this->getToolbar();
		if ($element instanceof BaseWidget) {
			if ($element->getIdentifier() === "") {
				$element->setIdentifier("tb-item-" . $this->identifier . "-" . $tb->count());
			}
		}
		if (isset($callback)) {
			if (\is_callable($callback)) {
				$callback($element);
			}
		}
		return $tb->addItem($element);
	}

	/**
	 *
	 * @param string $caption
	 * @param string $icon
	 * @param callable $callback
	 *        	function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addItemInToolbar($caption, $icon = NULL, $callback = NULL) {
		$result = $this->addInToolbar($caption, $callback);
		if (isset($icon) && method_exists($result, "addIcon"))
			$result->addIcon($icon);
		return $result;
	}

	/**
	 *
	 * @param array $items
	 * @param callable $callback
	 *        	function($element)
	 * @return \Ajax\common\Widget
	 */
	public function addItemsInToolbar(array $items, $callback = NULL) {
		if (JArray::isAssociative($items)) {
			foreach ($items as $icon => $item) {
				$this->addItemInToolbar($item, $icon, $callback);
			}
		} else {
			foreach ($items as $item) {
				$this->addItemInToolbar($item, null, $callback);
			}
		}
		return $this;
	}

	/**
	 *
	 * @param mixed $value
	 * @param array $items
	 * @param callable $callback
	 *        	function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addDropdownInToolbar($value, $items, $callback = NULL) {
		$dd = $value;
		if (\is_string($value)) {
			$dd = new HtmlDropdown("dropdown-" . $this->identifier . "-" . $value, $value, $items);
		}
		return $this->addInToolbar($dd, $callback);
	}

	/**
	 *
	 * @param string $caption
	 * @param string $cssStyle
	 * @param callable $callback
	 *        	function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addButtonInToolbar($caption, $cssStyle = null, $callback = NULL) {
		$bt = new HtmlButton("bt-" . $caption, $caption, $cssStyle);
		return $this->addInToolbar($bt, $callback);
	}

	/**
	 *
	 * @param array $captions
	 * @param boolean $asIcon
	 * @param callable $callback
	 *        	function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addButtonsInToolbar(array $captions, $asIcon = false, $callback = NULL) {
		$bts = new HtmlButtonGroups("", $captions, $asIcon);
		return $this->addInToolbar($bts, $callback);
	}

	/**
	 *
	 * @param string $caption
	 * @param string $icon
	 * @param boolean $before
	 * @param boolean $labeled
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addLabelledIconButtonInToolbar($caption, $icon, $before = true, $labeled = false) {
		$bt = new HtmlButton("", $caption);
		$bt->addIcon($icon, $before, $labeled);
		return $this->addInToolbar($bt);
	}

	public function addSubmitInToolbar($identifier, $value, $cssStyle = NULL, $url = NULL, $responseElement = NULL, $parameters = NULL) {
		$button = new HtmlButton($identifier, $value, $cssStyle);
		$this->_buttonAsSubmit($button, "click", $url, $responseElement, $parameters);
		return $this->addInToolbar($button);
	}

	/**
	 * Defines a callback function to call for modifying captions
	 * function parameters 0are $captions: the captions to modify and $instance: the active model instance
	 *
	 * @param callable $captionCallback
	 * @return Widget
	 */
	public function setCaptionCallback($captionCallback) {
		$this->_instanceViewer->setCaptionCallback($captionCallback);
		return $this;
	}

	/**
	 * Makes the input fields editable
	 *
	 * @param boolean $_edition
	 * @return \Ajax\common\Widget
	 */
	public function setEdition($_edition = true) {
		$this->_edition = $_edition;
		return $this;
	}

	/**
	 * Defines the default function which displays fields value
	 *
	 * @param callable $defaultValueFunction
	 *        	function parameters are : $name : the field name, $value : the field value ,$index : the field index, $instance : the active instance of model
	 * @return \Ajax\common\Widget
	 */
	public function setDefaultValueFunction($defaultValueFunction) {
		$this->_instanceViewer->setDefaultValueFunction($defaultValueFunction);
		return $this;
	}

	/**
	 *
	 * @return callable
	 */
	public function getDefaultValueFunction() {
		return $this->_instanceViewer->getDefaultValueFunction();
	}

	/**
	 *
	 * @param string|boolean $disable
	 * @return string
	 */
	public function jsDisabled($disable = true) {
		return "$('#" . $this->identifier . " .ui.input,#" . $this->identifier . " .ui.dropdown,#" . $this->identifier . " .ui.checkbox').toggleClass('disabled'," . $disable . ");";
	}

	/**
	 *
	 * @param string $caption
	 * @param callable $callback
	 *        	function($element)
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addEditButtonInToolbar($caption, $callback = NULL) {
		$bt = new HtmlButton($this->identifier . "-editBtn", $caption);
		$bt->setToggle();
		$bt->setActive($this->_edition);
		$bt->onClick($this->jsDisabled(Javascript::prep_value("!$(event.target).hasClass('active')")));
		return $this->addInToolbar($bt, $callback);
	}

	public function setToolbar(HtmlMenu $_toolbar) {
		$this->_toolbar = $_toolbar;
		return $this;
	}

	public function setToolbarPosition($_toolbarPosition) {
		$this->_toolbarPosition = $_toolbarPosition;
		return $this;
	}

	public function getForm() {
		if (! isset($this->_form)) {
			$this->_form = new HtmlForm("frm-" . $this->identifier);
			$this->setEdition(true);
		}
		return $this->_form;
	}

	public function run(JsUtils $js) {
		parent::run($js);
		if (isset($this->_form)) {
			$this->runForm($js);
		}
	}

	protected function runForm(JsUtils $js) {
		$fields = $this->getContentInstances(HtmlFormField::class);
		foreach ($fields as $field) {
			$this->_form->addField($field);
		}
		return $this->_form->run($js);
	}

	protected function _compileForm() {
		if (isset($this->_form)) {
			$noValidate = "";
			if (\sizeof($this->_form->getValidationParams()) > 0)
				$noValidate = "novalidate";
			$this->wrapContent("<form class='" . $this->_form->getProperty('class') . "' id='frm-" . $this->identifier . "' name='frm-" . $this->identifier . "' " . $noValidate . ">", "</form>");
		}
	}

	/**
	 * Sets the parameters for the Form validation (on, inline, delay...)
	 *
	 * @param array $_validationParams
	 *        	example : ["on"=>"blur","inline"=>true]
	 * @return Widget
	 * @see https://semantic-ui.com/behaviors/form.html#/settings
	 */
	public function setValidationParams(array $_validationParams) {
		$this->getForm()->setValidationParams($_validationParams);
		return $this;
	}

	public function moveFieldTo($from, $to) {
		return $this->_instanceViewer->moveFieldTo($from, $to);
	}

	public function swapFields($index1, $index2) {
		$index1 = $this->_getIndex($index1);
		$index2 = $this->_getIndex($index2);
		return $this->_instanceViewer->swapFields($index1, $index2);
	}

	public function removeField($index) {
		$index = $this->_getIndex($index);
		if ($index !== false) {
			$this->_instanceViewer->removeField($index);
		}
		return $this;
	}

	public function asModal($header = null) {
		$modal = new HtmlModal("modal-" . $this->identifier, $header);
		$modal->setContent($this);
		if (isset($this->_form)) {
			$this->_form->onSuccess($modal->jsHide());
		}
		return $modal;
	}

	public function addToProperty($name, $value, $separator = " ") {
		return $this->getHtmlComponent()->addToProperty($name, $value, $separator);
	}

	/**
	 *
	 * @return mixed
	 */
	public function getModelInstance() {
		return $this->_modelInstance;
	}

	/**
	 *
	 * @return mixed true if widget has validation rules
	 */
	public function hasRules() {
		return $this->_hasRules;
	}
}
