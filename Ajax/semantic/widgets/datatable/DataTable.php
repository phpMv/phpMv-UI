<?php
namespace Ajax\semantic\widgets\datatable;

use Ajax\JsUtils;
use Ajax\common\Widget;
use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\semantic\html\base\traits\BaseTrait;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\collections\table\HtmlTable;
use Ajax\semantic\html\collections\table\traits\TableTrait;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\semantic\widgets\base\InstanceViewer;
use Ajax\service\JArray;
use Ajax\service\JString;

/**
 * DataTable widget for displaying list of objects
 *
 * @version 1.0
 * @author jc
 * @since 2.2
 *       
 */
class DataTable extends Widget {
	use TableTrait,DataTableFieldAsTrait,HasCheckboxesTrait,BaseTrait;

	protected $_searchField;

	protected $_urls;

	protected $_pagination;

	protected $_compileParts;

	protected $_deleteBehavior;

	protected $_editBehavior;

	protected $_displayBehavior;

	protected $_visibleHover = false;

	protected $_targetSelector;

	protected $_refreshSelector;

	protected $_emptyMessage;

	protected $_json;

	protected $_rowClass = "_element";

	protected $_sortable;

	protected $_hiddenColumns;

	protected $_colWidths;

	public function __construct($identifier, $model, $modelInstance = NULL) {
		parent::__construct($identifier, $model, $modelInstance);
		$this->_init(new InstanceViewer($identifier), "table", new HtmlTable($identifier, 0, 0), false);
		$this->_urls = [];
		$this->_emptyMessage = new HtmlMessage("", "nothing to display");
		$this->_emptyMessage->setIcon("info circle");
	}

	public function run(JsUtils $js) {
		$offset = $js->scriptCount();
		if ($this->_hasCheckboxes && isset($js)) {
			$this->_runCheckboxes($js);
		}
		if ($this->_visibleHover) {
			$js->execOn("mouseover", "#" . $this->identifier . " tr", "$(event.target).closest('tr').find('.visibleover').css('visibility', 'visible');", [
				"preventDefault" => false,
				"stopPropagation" => true
			]);
			$js->execOn("mouseout", "#" . $this->identifier . " tr", "$(event.target).closest('tr').find('.visibleover').css('visibility', 'hidden');", [
				"preventDefault" => false,
				"stopPropagation" => true
			]);
		}
		if (\is_array($this->_deleteBehavior))
			$this->_generateBehavior("delete", $this->_deleteBehavior, $js);
		if (\is_array($this->_editBehavior))
			$this->_generateBehavior("edit", $this->_editBehavior, $js);
		if (\is_array($this->_displayBehavior)) {
			$this->_generateBehavior("display", $this->_displayBehavior, $js);
		}
		parent::run($js);
		if (isset($this->_pagination))
			$this->_associatePaginationBehavior($js, $offset);
		$this->_associateSearchFieldBehavior($js, $offset);
	}

	protected function _generateBehavior($op, $params, JsUtils $js) {
		if (isset($this->_urls[$op])) {
			$params = \array_merge($params, [
				"attr" => "data-ajax"
			]);
			$js->ajaxOnClick("#" . $this->identifier . " ._" . $op, $this->_urls[$op], $this->getTargetSelector($op), $params);
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see TableTrait::getTable()
	 */
	protected function getTable() {
		return $this->content["table"];
	}

	public function refreshTR() {
		$this->getTable()->refreshTR();
		return $this;
	}

	public function refreshTD($fieldName, $jquery, $view) {
		$index = $this->_getIndex($fieldName);
		$this->compile($jquery, $view);
		return $this->refreshTR()
			->getTable()
			->getCell(0, $index);
	}

	public function compile(JsUtils $js = NULL, &$view = NULL) {
		if (! $this->_generated) {
			if (isset($this->_buttonsColumn)) {
				$this->_instanceViewer->sortColumnContent($this->_buttonsColumn, $this->_buttons);
			}
			$this->_instanceViewer->setInstance($this->_model);
			$captions = $this->_instanceViewer->getCaptions();
			$table = $this->content["table"];
			if ($this->_hasCheckboxes) {
				$this->_generateMainCheckbox($captions);
			}
			$table->setRowCount(0, \sizeof($captions));
			$this->_generateHeader($table, $captions);

			if (isset($this->_compileParts))
				$table->setCompileParts($this->_compileParts);

			$this->_generateContent($table);

			$this->compileExtraElements($table, $captions);
			$this->_compileSearchFieldBehavior($js);

			$this->content = JArray::sortAssociative($this->content, [
				PositionInTable::BEFORETABLE,
				"table",
				PositionInTable::AFTERTABLE
			]);
			$this->_compileForm();
			$this->_applyStyleAttributes($table);
			$this->_generated = true;
		}
		return parent::compile($js, $view);
	}

	protected function compileExtraElements($table, $captions) {
		if ($this->_hasCheckboxes && $table->hasPart("thead")) {
			$table->getHeader()
				->getCell(0, 0)
				->addClass("no-sort");
		}

		if (isset($this->_toolbar)) {
			$this->_setToolbarPosition($table, $captions);
		}
		if (isset($this->_pagination) && $this->_pagination->getVisible()) {
			$this->_generatePagination($table);
		}
	}

	protected function _applyStyleAttributes($table) {
		if (isset($this->_hiddenColumns))
			$this->_hideColumns();
		if (isset($this->_colWidths)) {
			foreach ($this->_colWidths as $colIndex => $width) {
				$table->setColWidth($colIndex, $width);
			}
		}
	}

	protected function _hideColumns() {
		foreach ($this->_hiddenColumns as $colIndex) {
			$this->_self->hideColumn($colIndex);
		}
		return $this;
	}

	protected function _generateHeader(HtmlTable $table, $captions) {
		$table->setHeaderValues($captions);
		if (isset($this->_sortable)) {
			$table->setSortable($this->_sortable);
		}
	}

	protected function _generateContent($table) {
		$objects = $this->_modelInstance;
		if (isset($this->_pagination)) {
			$objects = $this->_pagination->getObjects($this->_modelInstance);
		}
		InstanceViewer::setIndex(0);
		$fields = $this->_instanceViewer->getSimpleProperties();
		$groupByFields = $this->_instanceViewer->getGroupByFields();
		if (! is_array($groupByFields)) {
			$table->fromDatabaseObjects($objects, function ($instance) use ($table, $fields) {
				return $this->_generateRow($instance, $fields, $table);
			});
		} else {
			$activeValues = array_combine($groupByFields, array_fill(0, sizeof($groupByFields), null));
			$uuids = [];
			$table->fromDatabaseObjects($objects, function ($instance) use ($table, $fields, &$activeValues, $groupByFields, &$uuids) {
				$this->_instanceViewer->setInstance($instance);
				foreach ($groupByFields as $index => $gbField) {
					$this->_generateGroupByRow($index, $gbField, $table, $fields, $activeValues, $uuids);
				}
				return $this->_generateRow($instance, $fields, $table, null, $uuids);
			});
		}
		if ($table->getRowCount() == 0) {
			$result = $table->addRow();
			$result->mergeRow();
			$result->setValues([
				$this->_emptyMessage
			]);
		}
	}

	protected function _generateGroupByRow($index, $gbField, $table, $fields, &$activeValues, &$uuids) {
		$newValue = $this->_instanceViewer->getValue($gbField);
		if ($this->getElementContent($activeValues[$gbField]) !== $this->getElementContent($newValue)) {
			if ($index == 0) {
				$uuids = [];
			}
			$uuid = uniqid("grp");
			$uuids[$gbField] = $uuid;
			$id = $this->_instanceViewer->getIdentifier();
			$result = $table->addMergeRow(sizeof($fields) + 1, $newValue);
			$result->setIdentifier($this->identifier . "-tr-gb-" . $id);
			$result->setProperty("data-ajax", $id);
			$result->setProperty("data-group", $uuid);
			$result->addToProperty("class", $this->_rowClass);
			$activeValues[$gbField] = $newValue;
		}
	}

	private function getElementContent($elm) {
		if ($elm instanceof HtmlDoubleElement) {
			return $elm->getTextContent();
		}
		return $elm;
	}

	public function getFieldValue($index) {
		$index = $this->_getIndex($index);
		if (is_numeric($index)) {
			$values = $this->_instanceViewer->getValues();
			if (isset($values[$index])) {
				return $values[$index];
			}
		}
		return null;
	}

	protected function _generateRow($instance, $fields, &$table, $checkedClass = null, $uuids = null) {
		$this->_instanceViewer->setInstance($instance);
		InstanceViewer::$index ++;
		$values = $this->_instanceViewer->getValues();
		$id = $this->_instanceViewer->getIdentifier();
		$dataAjax = $id;
		$id = $this->cleanIdentifier($id);
		if ($this->_hasCheckboxes) {
			$ck = new HtmlCheckbox("ck-" . $this->identifier . "-" . $id, "");
			$checked = false;
			if (isset($this->_checkedCallback)) {
				$func = $this->_checkedCallback;
				$checked = $func($instance);
			}
			$ck->setChecked($checked);
			$ck->setOnChange("event.stopPropagation();");
			$field = $ck->getField();
			$field->setProperty("value", $dataAjax);
			$field->setProperty("name", "selection[]");
			if (isset($checkedClass))
				$field->setClass($checkedClass);
			\array_unshift($values, $ck);
		}
		$result = $table->newRow();
		$result->setIdentifier($this->identifier . "-tr-" . $id);
		$result->setProperty("data-ajax", $dataAjax);
		$result->setValues($values);
		$result->addToProperty("class", $this->_rowClass);
		$result->setPropertyValues("data-field", $fields);
		if (isset($uuids)) {
			$result->setProperty("data-child", implode(" ", $uuids));
		}
		return $result;
	}

	protected function _generatePagination($table) {
		if (isset($this->_toolbar)) {
			if ($this->_toolbarPosition == PositionInTable::FOOTER)
				$this->_toolbar->setFloated("left");
		}
		$footer = $table->getFooter();
		$footer->mergeCol();
		$footer->addValues($this->_pagination->generateMenu($this->identifier));
	}

	protected function _associatePaginationBehavior(JsUtils $js = NULL, $offset = null) {
		if (isset($this->_urls["refresh"])) {
			$menu = $this->_pagination->getMenu();
			if (isset($menu) && isset($js)) {
				$js->postOnClick("#" . $menu->getIdentifier() . " .item", $this->_urls["refresh"], "{'p':$(this).attr('data-page'),'_model':'" . JString::doubleBackSlashes($this->_model) . "'}", $this->getRefreshSelector(), [
					"preventDefault" => false,
					"jqueryDone" => "replaceWith",
					"hasLoader" => false,
					"jsCallback" => '$("#' . $this->identifier . '").trigger("pageChange");$("#' . $this->identifier . '").trigger("activeRowChange");'
				]);
				$page = $_POST["p"] ?? null;
				if (isset($page)) {
					$js->execAtLast('$("#' . $this->getIdentifier() . ' .pagination").children("a.item").removeClass("active");$("#' . $this->getIdentifier() . ' .pagination").children("a.item[data-page=' . $page . ']:not(.no-active)").addClass("active");');
				}
			}
		}
	}

	protected function _compileSearchFieldBehavior(JsUtils $js = NULL) {
		if (isset($this->_searchField) && isset($js) && isset($this->_urls["refresh"])) {
			$this->_searchField->postOn("change", $this->_urls["refresh"], "{'s':$(self).val(),'_model':'" . JString::doubleBackSlashes($this->_model) . "'}", "#" . $this->identifier . " tbody", [
				"preventDefault" => false,
				"jqueryDone" => "replaceWith",
				"hasLoader" => "internal",
				"jsCallback" => '$("#' . $this->identifier . '").trigger("searchTerminate",[$(self).val()]);'
			]);
		}
	}

	protected function _associateSearchFieldBehavior(JsUtils $js = NULL, $offset = null) {}

	protected function _getFieldName($index) {
		$fieldName = parent::_getFieldName($index);
		if (\is_object($fieldName))
			$fieldName = "field-" . $index;
		return $fieldName . "[]";
	}

	protected function _getFieldCaption($index) {
		return null;
	}

	protected function _setToolbarPosition($table, $captions = NULL) {
		switch ($this->_toolbarPosition) {
			case PositionInTable::BEFORETABLE:
			case PositionInTable::AFTERTABLE:
				if (isset($this->_compileParts) === false) {
					$this->content[$this->_toolbarPosition] = $this->_toolbar;
				}
				break;
			case PositionInTable::HEADER:
			case PositionInTable::FOOTER:
			case PositionInTable::BODY:
				$this->addToolbarRow($this->_toolbarPosition, $table, $captions);
				break;
		}
	}

	/**
	 * Associates a $callback function after the compilation of the field at $index position
	 * The $callback function can take the following arguments : $field=>the compiled field, $instance : the active instance of the object, $index: the field position
	 *
	 * @param int $index
	 *        	postion of the compiled field
	 * @param callable $callback
	 *        	function called after the field compilation
	 * @return DataTable
	 */
	public function afterCompile($index, $callback) {
		$this->_instanceViewer->afterCompile($index, $callback);
		return $this;
	}

	private function addToolbarRow($part, $table, $captions) {
		$hasPart = $table->hasPart($part);
		if ($hasPart) {
			$row = $table->getPart($part)->addRow(\sizeof($captions));
		} else {
			$row = $table->getPart($part)->getRow(0);
		}
		$row->mergeCol();
		$row->setValues([
			$this->_toolbar
		]);
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see Widget::getHtmlComponent()
	 * @return HtmlTable
	 */
	public function getHtmlComponent() {
		return $this->content["table"];
	}

	public function getUrls() {
		return $this->_urls;
	}

	/**
	 * Sets the associative array of urls for refreshing, updating or deleting
	 * think of defining the update zone with the setTargetSelector method
	 *
	 * @param string|array $urls
	 *        	associative array with keys refresh: for refreshing with search field or pagination, edit : for updating a row, delete: for deleting a row
	 * @return DataTable
	 */
	public function setUrls($urls) {
		if (\is_array($urls)) {
			$this->_urls["refresh"] = JArray::getValue($urls, "refresh", 0);
			$this->_urls["edit"] = JArray::getValue($urls, "edit", 1);
			$this->_urls["delete"] = JArray::getValue($urls, "delete", 2);
			$this->_urls["display"] = JArray::getValue($urls, "display", 3);
		} else {
			$this->_urls = [
				"refresh" => $urls,
				"edit" => $urls,
				"delete" => $urls,
				"display" => $urls
			];
		}
		return $this;
	}

	/**
	 * Paginates the DataTable element with a Semantic HtmlPaginationMenu component
	 *
	 * @param number $page
	 *        	the active page number
	 * @param number $total_rowcount
	 *        	the total number of items
	 * @param number $items_per_page
	 *        	The number of items per page
	 * @param number $pages_visibles
	 *        	The number of visible pages in the Pagination component
	 * @return DataTable
	 */
	public function paginate($page, $total_rowcount, $items_per_page = 10, $pages_visibles = null) {
		$this->_pagination = new Pagination($items_per_page, $pages_visibles, $page, $total_rowcount);
		return $this;
	}

	/**
	 * Auto Paginates the DataTable element with a Semantic HtmlPaginationMenu component
	 *
	 * @param number $page
	 *        	the active page number
	 * @param number $items_per_page
	 *        	The number of items per page
	 * @param number $pages_visibles
	 *        	The number of visible pages in the Pagination component
	 * @return DataTable
	 */
	public function autoPaginate($page = 1, $items_per_page = 10, $pages_visibles = 4) {
		$this->_pagination = new Pagination($items_per_page, $pages_visibles, $page);
		return $this;
	}

	/**
	 *
	 * @param array $compileParts
	 * @return DataTable
	 */
	public function refresh($compileParts = ["tbody"]) {
		$this->_compileParts = $compileParts;
		return $this;
	}

	/**
	 * Adds a search input in toolbar
	 *
	 * @param string $position
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addSearchInToolbar($position = Direction::RIGHT) {
		return $this->addInToolbar($this->getSearchField())
			->setPosition($position);
	}

	public function getSearchField() {
		if (isset($this->_searchField) === false) {
			$this->_searchField = new HtmlInput("search-" . $this->identifier, "search", "", "Search...");
			$this->_searchField->addIcon("search", Direction::RIGHT);
		}
		return $this->_searchField;
	}

	/**
	 * The callback function called after the insertion of each row when fromDatabaseObjects is called
	 * callback function takes the parameters $row : the row inserted and $object: the instance of model used
	 *
	 * @param callable $callback
	 * @return DataTable
	 */
	public function onNewRow($callback) {
		$this->content["table"]->onNewRow($callback);
		return $this;
	}

	/**
	 * Returns a form corresponding to the Datatable
	 *
	 * @return \Ajax\semantic\html\collections\form\HtmlForm
	 */
	public function asForm() {
		return $this->getForm();
	}

	protected function getTargetSelector($op) {
		$result = $this->_targetSelector;
		if (! isset($result[$op]))
			$result = "#" . $this->identifier;
		return $result[$op];
	}

	/**
	 * Sets the response element selector for Edit and Delete request with ajax
	 *
	 * @param string|array $_targetSelector
	 *        	string or associative array ["edit"=>"edit_selector","delete"=>"delete_selector"]
	 * @return DataTable
	 */
	public function setTargetSelector($_targetSelector) {
		if (! \is_array($_targetSelector)) {
			$_targetSelector = [
				"edit" => $_targetSelector,
				"delete" => $_targetSelector
			];
		}
		$this->_targetSelector = $_targetSelector;
		return $this;
	}

	public function getRefreshSelector() {
		if (isset($this->_refreshSelector))
			return $this->_refreshSelector;
		return "#" . $this->identifier . " tbody";
	}

	/**
	 *
	 * @param string $_refreshSelector
	 * @return DataTable
	 */
	public function setRefreshSelector($_refreshSelector) {
		$this->_refreshSelector = $_refreshSelector;
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Ajax\common\Widget::show()
	 */
	public function show($modelInstance) {
		if (\is_array($modelInstance)) {
			if (isset($modelInstance[0]) && \is_array(array_values($modelInstance)[0]))
				$modelInstance = \json_decode(\json_encode($modelInstance), FALSE);
		}
		$this->_modelInstance = $modelInstance;
	}

	public function getRowClass() {
		return $this->_rowClass;
	}

	/**
	 * Sets the default row class (tr class)
	 *
	 * @param string $_rowClass
	 * @return DataTable
	 */
	public function setRowClass($_rowClass) {
		$this->_rowClass = $_rowClass;
		return $this;
	}

	/**
	 * Sets the message displayed when there is no record
	 *
	 * @param mixed $_emptyMessage
	 * @return DataTable
	 */
	public function setEmptyMessage($_emptyMessage) {
		$this->_emptyMessage = $_emptyMessage;
		return $this;
	}

	public function setSortable($colIndex = NULL) {
		$this->_sortable = $colIndex;
		return $this;
	}

	public function setActiveRowSelector($class = "active", $event = "click", $multiple = false) {
		$this->_self->setActiveRowSelector($class, $event, $multiple);
		return $this;
	}

	public function hideColumn($colIndex) {
		if (! \is_array($this->_hiddenColumns))
			$this->_hiddenColumns = [];
		$this->_hiddenColumns[] = $colIndex;
		return $this;
	}

	public function setColWidth($colIndex, $width) {
		$this->_colWidths[$colIndex] = $width;
		return $this;
	}

	public function setColWidths($_colWidths) {
		$this->_colWidths = $_colWidths;
		return $this;
	}

	public function setColAlignment($colIndex, $alignment) {
		$this->content["table"]->setColAlignment($colIndex, $alignment);
		return $this;
	}

	public function trigger($event, $params = "[]") {
		return $this->getHtmlComponent()->trigger($event, $params);
	}

	public function onActiveRowChange($jsCode) {
		$this->getHtmlComponent()->onActiveRowChange($jsCode);
		return $this;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getDeleteBehavior() {
		return $this->_deleteBehavior;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getEditBehavior() {
		return $this->_editBehavior;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getDisplayBehavior() {
		return $this->_displayBehavior;
	}

	/**
	 *
	 * @param mixed $_displayBehavior
	 */
	public function setDisplayBehavior($_displayBehavior) {
		$this->_displayBehavior = $_displayBehavior;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getGroupByFields() {
		return $this->_instanceViewer->getGroupByFields();
	}

	/**
	 *
	 * @param mixed $_groupByFields
	 */
	public function setGroupByFields($_groupByFields) {
		$this->_instanceViewer->setGroupByFields($_groupByFields);
	}
}
