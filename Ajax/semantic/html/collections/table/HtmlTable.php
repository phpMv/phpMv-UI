<?php
namespace Ajax\semantic\html\collections\table;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\content\table\HtmlTableContent;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\JsUtils;
use Ajax\service\JArray;
use Ajax\semantic\html\content\table\HtmlTR;
use Ajax\semantic\html\collections\table\traits\TableTrait;
use Ajax\semantic\html\content\table\HtmlTD;
use Ajax\semantic\html\base\constants\TextAlignment;
use Ajax\common\html\BaseHtml;

/**
 * Semantic HTML Table component
 *
 * @author jc
 *
 */
class HtmlTable extends HtmlSemDoubleElement {
	use TableTrait;

	private $_colCount;

	private $_compileParts;

	private $_footer;

	private $_afterCompileEvents;

	private $_activeRowSelector;

	private $_focusable = false;

	/**
	 *
	 * @return ActiveRow
	 */
	public function getActiveRowSelector() {
		return $this->_activeRowSelector;
	}

	protected $_innerScript;

	public function __construct($identifier, $rowCount, $colCount) {
		parent::__construct($identifier, "table", "ui table");
		$this->content = array();
		$this->setRowCount($rowCount, $colCount);
		$this->_variations = [
			Variation::CELLED,
			Variation::PADDED,
			Variation::COMPACT
		];
		$this->_compileParts = [
			"thead",
			"tbody",
			"tfoot"
		];
		$this->_afterCompileEvents = [];
	}

	/**
	 * Returns/create eventually a part of the table corresponding to the $key : thead, tbody or tfoot
	 *
	 * @param string $key
	 * @return HtmlTableContent
	 */
	public function getPart($key) {
		if (\array_key_exists($key, $this->content) === false) {
			$this->content[$key] = new HtmlTableContent("", $key);
			if ($key !== "tbody") {
				$this->content[$key]->setRowCount(1, $this->_colCount);
			}
		}
		return $this->content[$key];
	}

	protected function _getFirstPart() {
		if (isset($this->content["thead"])) {
			return $this->content["thead"];
		}
		return $this->content["tbody"];
	}

	/**
	 * Returns/create eventually the body of the table
	 *
	 * @return HtmlTableContent
	 */
	public function getBody() {
		return $this->getPart("tbody");
	}

	/**
	 * Returns the number of rows (TR)
	 *
	 * @return int
	 */
	public function getRowCount() {
		return $this->getPart("tbody")->count();
	}

	/**
	 * Returns/create eventually the header of the table
	 *
	 * @return HtmlTableContent
	 */
	public function getHeader() {
		return $this->getPart("thead");
	}

	/**
	 * Returns/create eventually the footer of the table
	 *
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function getFooter() {
		return $this->getPart("tfoot");
	}

	/**
	 * Checks if the part corresponding to $key exists
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function hasPart($key) {
		return \array_key_exists($key, $this->content) === true;
	}

	/**
	 *
	 * @param int $rowCount
	 * @param int $colCount
	 * @return HtmlTableContent
	 */
	public function setRowCount($rowCount, $colCount) {
		$this->_colCount = $colCount;
		return $this->getBody()->setRowCount($rowCount, $colCount);
	}

	/**
	 * Returns the cell (HtmlTD) at position $row,$col
	 *
	 * @param int $row
	 * @param int $col
	 * @return HtmlTD
	 */
	public function getCell($row, $col) {
		return $this->getBody()->getCell($row, $col);
	}

	/**
	 * Retuns the row at $rowIndex
	 *
	 * @param int $rowIndex
	 * @return HtmlTR
	 */
	public function getRow($rowIndex) {
		return $this->getBody()->getRow($rowIndex);
	}

	/**
	 * Adds a new row and sets $values to his cols
	 *
	 * @param array $values
	 * @return HtmlTR
	 */
	public function addRow($values = array()) {
		$row = $this->getBody()->addRow($this->_colCount);
		$row->setValues(\array_values($values));
		return $row;
	}

	/**
	 * adds and returns a new row
	 *
	 * @return HtmlTR
	 */
	public function newRow() {
		return $this->getBody()->newRow($this->_colCount);
	}

	/**
	 * Sets the tbody values
	 *
	 * @param array $values
	 *        	values in an array of array
	 * @return HtmlTable
	 */
	public function setValues($values = array()) {
		$this->getBody()->setValues($values);
		return $this;
	}

	/**
	 * Sets the header values
	 *
	 * @param array $values
	 * @return HtmlTableContent
	 */
	public function setHeaderValues($values = array()) {
		return $this->getHeader()->setValues($values);
	}

	/**
	 * Sets the footer values
	 *
	 * @param array $values
	 * @return HtmlTableContent
	 */
	public function setFooterValues($values = array()) {
		return $this->getFooter()->setValues($values);
	}

	/**
	 * Sets values to the col at index $colIndex
	 *
	 * @param int $colIndex
	 * @param array $values
	 * @return HtmlTable
	 */
	public function setColValues($colIndex, $values = array()) {
		$this->getBody()->setColValues($colIndex, $values);
		return $this;
	}

	/**
	 * Sets values to the row at index $rowIndex
	 *
	 * @param int $rowIndex
	 * @param array $values
	 * @return HtmlTable
	 */
	public function setRowValues($rowIndex, $values = array()) {
		$this->getBody()->setRowValues($rowIndex, $values);
		return $this;
	}

	public function addColVariations($colIndex, $variations = array()) {
		return $this->getBody()->addColVariations($colIndex, $variations);
	}

	/**
	 * Sets the col alignment to center
	 *
	 * @param int $colIndex
	 * @return HtmlTable
	 */
	public function colCenter($colIndex) {
		return $this->colAlign($colIndex, "colCenter");
	}

	/**
	 * Sets the col alignment to right
	 *
	 * @param int $colIndex
	 * @return HtmlTable
	 */
	public function colRight($colIndex) {
		return $this->colAlign($colIndex, "colRight");
	}

	/**
	 * Sets col alignment to left
	 *
	 * @param int $colIndex
	 * @return HtmlTable
	 */
	public function colLeft($colIndex) {
		return $this->colAlign($colIndex, "colLeft");
	}

	/**
	 * Sets the col alignment to center
	 *
	 * @param int $colIndex
	 * @return HtmlTable
	 */
	public function colCenterFromRight($colIndex) {
		return $this->colAlign($colIndex, "colCenterFromRight");
	}

	/**
	 * Sets the col alignment to right
	 *
	 * @param int $colIndex
	 * @return HtmlTable
	 */
	public function colRightFromRight($colIndex) {
		return $this->colAlign($colIndex, "colRightFromRight");
	}

	/**
	 * Sets col alignment to left
	 *
	 * @param int $colIndex
	 * @return HtmlTable
	 */
	public function colLeftFromRight($colIndex) {
		return $this->colAlign($colIndex, "colLeftFromRight");
	}

	public function setColAlignment($colIndex, $alignment) {
		switch ($alignment) {
			case TextAlignment::LEFT:
				$function = "colLeft";
				break;

			case TextAlignment::RIGHT:
				$function = "colRight";
				break;

			case TextAlignment::CENTER:
				$function = "colCenter";
				break;

			default:
				$function = "colLeft";
		}
		$this->colAlign($colIndex, $function);
		return $this;
	}

	public function setColAlignmentFromRight($colIndex, $alignment) {
		switch ($alignment) {
			case TextAlignment::LEFT:
				$function = "colLeftFromRight";
				break;

			case TextAlignment::RIGHT:
				$function = "colRightFromRight";
				break;

			case TextAlignment::CENTER:
				$function = "colCenterFromRight";
				break;

			default:
				$function = "colLeftFromRight";
		}
		$this->colAlign($colIndex, $function);
		return $this;
	}

	private function colAlign($colIndex, $function) {
		if (\is_array($colIndex)) {
			foreach ($colIndex as $cIndex) {
				$this->colAlign($cIndex, $function);
			}
		} else {
			if ($this->hasPart("thead")) {
				$this->getHeader()->$function($colIndex);
			}
			$this->getBody()->$function($colIndex);
		}
		return $this;
	}

	/**
	 * Applies a format on each cell when $callback returns true
	 *
	 * @param callable $callback
	 *        	function with the cell as parameter, must return a boolean
	 * @param string $format
	 *        	css class to apply
	 * @return HtmlTable
	 */
	public function conditionalCellFormat($callback, $format) {
		$this->getBody()->conditionalCellFormat($callback, $format);
		return $this;
	}

	/**
	 * Applies a format on each row when $callback returns true
	 *
	 * @param callable $callback
	 *        	function with the row as parameter, must return a boolean
	 * @param string $format
	 *        	css class to apply
	 * @return HtmlTable
	 */
	public function conditionalRowFormat($callback, $format) {
		$this->getBody()->conditionalRowFormat($callback, $format);
		return $this;
	}

	/**
	 * Applies a callback function on each cell
	 *
	 * @param callable $callback
	 * @return HtmlTable
	 */
	public function applyCells($callback) {
		$this->getBody()->applyCells($callback);
		return $this;
	}

	/**
	 * Applies a callback function on each row
	 *
	 * @param callable $callback
	 * @return HtmlTable
	 */
	public function applyRows($callback) {
		$this->getBody()->applyRows($callback);
		return $this;
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js = NULL, &$view = NULL) {
		if (\sizeof($this->_compileParts) < 3) {
			$this->_template = "%content%";
			$this->refresh($js);
		}
		$this->content = JArray::sortAssociative($this->content, $this->_compileParts);
		return parent::compile($js, $view);
	}

	protected function compile_once(JsUtils $js = NULL, &$view = NULL) {
		parent::compile_once($js, $view);
		if ($this->propertyContains("class", "sortable")) {
			$this->addEvent("execute", "$('#" . $this->identifier . "').tablesort().data('tablesort').sort($('th.default-sort'));");
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$result = $function($object);
		if (\is_array($result)) {
			$result = $this->addRow($function($object));
		} else {
			$result = $this->getBody()->_addRow($result);
		}
		if (isset($this->_afterCompileEvents["onNewRow"])) {
			if (\is_callable($this->_afterCompileEvents["onNewRow"]))
				$this->_afterCompileEvents["onNewRow"]($result, $object);
		}
		return $result;
	}

	/**
	 * Sets the parts of the Table to compile
	 *
	 * @param array $parts
	 *        	array of thead,tbody,tfoot
	 * @return HtmlTable
	 */
	public function setCompileParts($parts = [
		"tbody"
	]) {
		$this->_compileParts = $parts;
		return $this;
	}

	public function refreshTR() {
		$this->setCompileParts();
		$this->getPart("tbody")->refreshTR();
	}

	public function refresh($js) {
		$this->_footer = $this->getFooter();
		if (isset($js)) {
			$js->exec('$("#' . $this->identifier . ' tfoot").replaceWith("' . \addslashes($this->_footer) . '");', true);
		}
	}

	public function run(JsUtils $js) {
		if (! $this->_runned) {
			if (isset($this->_activeRowSelector)) {
				$this->_activeRowSelector->run();
			}
		}
		$result = parent::run($js);
		if (isset($this->_footer))
			$this->_footer->run($js);
		$this->_runned = true;
		return $result;
	}

	/**
	 * The callback function called after the insertion of each row when fromDatabaseObjects is called
	 * callback function takes the parameters $row : the row inserted and $object: the instance of model used
	 *
	 * @param callable $callback
	 * @return HtmlTable
	 */
	public function onNewRow($callback) {
		$this->_afterCompileEvents["onNewRow"] = $callback;
		return $this;
	}

	/**
	 * Defines how a row is selectable
	 *
	 * @param string $class
	 * @param string $event
	 * @param boolean $multiple
	 * @return HtmlTable
	 */
	public function setActiveRowSelector($class = "active", $event = "click", $multiple = false) {
		$this->_activeRowSelector = new ActiveRow($this, $class, $event, $multiple);
		return $this;
	}

	public function getActiveRowClass() {
		if (isset($this->_activeRowSelector)) {
			return $this->_activeRowSelector->getClass();
		}
		return 'active';
	}

	public function hasActiveRowSelector() {
		return isset($this->_activeRowSelector);
	}

	public function hideColumn($colIndex) {
		if (isset($this->content["thead"])) {
			$this->content["thead"]->hideColumn($colIndex);
		}
		$this->content["tbody"]->hideColumn($colIndex);
		if (isset($this->content["tfoot"])) {
			$this->content["tfoot"]->hideColumn($colIndex);
		}
		return $this;
	}

	public function setColWidth($colIndex, $width) {
		$part = $this->_getFirstPart();
		if ($part !== null && $part->count() > 0)
			$part->getCell(0, $colIndex)->setWidth($width);
		return $this;
	}

	public function setColWidths($widths) {
		$part = $this->_getFirstPart();
		if ($part !== null && $part->count() > 0) {
			$count = $part->getColCount();
			if (! \is_array($widths)) {
				$widths = \array_fill(0, $count, $widths);
			}
			$max = \min(\sizeof($widths), $count);
			for ($i = 0; $i < $max; $i ++) {
				$part->getCell(0, $i)->setWidth($widths[$i]);
			}
		}
		return $this;
	}

	public function mergeIdentiqualValues($colIndex, $function = "strip_tags") {
		$body = $this->getBody();
		$body->mergeIdentiqualValues($colIndex, $function);
		return $this;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getInnerScript() {
		return $this->_innerScript;
	}

	/**
	 *
	 * @param mixed $_innerScript
	 */
	public function setInnerScript($_innerScript) {
		$this->_innerScript = $_innerScript;
	}

	public function onActiveRowChange($jsCode) {
		$this->on("activeRowChange", $jsCode);
		return $this;
	}

	public function addMergeRow($colCount, $value = null) {
		return $this->getBody()->addMergeRow($colCount, $value);
	}

	/**
	 *
	 * @param bool $focusable
	 */
	public function setFocusable(bool $focusable): void {
		$this->getBody()->setFocusable($focusable);
	}
}
