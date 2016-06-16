<?php

namespace Ajax\semantic\html\collections;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\content\table\HtmlTableContent;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\JsUtils;

use Ajax\service\JArray;

/**
 * Semantic HTML Table component
 * @author jc
 *
 */
class HtmlTable extends HtmlSemDoubleElement {
	private $_colCount;

	public function __construct($identifier, $rowCount, $colCount) {
		parent::__construct($identifier, "table", "ui table");
		$this->content=array ();
		$this->setRowCount($rowCount, $colCount);
		$this->_variations=[ Variation::CELLED,Variation::PADDED,Variation::COMPACT ];
	}

	/**
	 * Returns/create eventually a part of the table corresponding to the $key : thead, tbody or tfoot
	 * @param string $key
	 * @return HtmlTableContent
	 */
	private function getPart($key) {
		if (\array_key_exists($key, $this->content) === false) {
			$this->content[$key]=new HtmlTableContent("", $key);
			if ($key !== "tbody") {
				$this->content[$key]->setRowCount(1, $this->_colCount);
			}
		}
		return $this->content[$key];
	}

	/**
	 * Returns/create eventually the body of the table
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function getBody() {
		return $this->getPart("tbody");
	}

	/**
	 * Returns/create eventually the header of the table
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function getHeader() {
		return $this->getPart("thead");
	}

	/**
	 * Returns/create eventually the footer of the table
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function getFooter() {
		return $this->getPart("tfoot");
	}

	/**
	 * Checks if the part corresponding to $key exists
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
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function setRowCount($rowCount, $colCount) {
		$this->_colCount=$colCount;
		return $this->getBody()->setRowCount($rowCount, $colCount);
	}

	/**
	 * Returns the cell (HtmlTD) at position $row,$col
	 * @param int $row
	 * @param int $col
	 * @return \Ajax\semantic\html\content\HtmlTD
	 */
	public function getCell($row, $col) {
		return $this->getBody()->getCell($row, $col);
	}

	/**
	 * Retuns the row at $rowIndex
	 * @param int $rowIndex
	 * @return \Ajax\semantic\html\content\HtmlTR
	 */
	public function getRow($rowIndex) {
		return $this->getBody()->getRow($rowIndex);
	}

	/**
	 * Adds a new row and sets $values to his cols
	 * @param array $values
	 * @return \Ajax\semantic\html\collections\HtmlTable
	 */
	public function addRow($values=array()) {
		$row=$this->getBody()->addRow($this->_colCount);
		$row->setValues(\array_values($values));
		return $this;
	}

	/**
	 * adds and returns a new row
	 * @return \Ajax\semantic\html\content\table\HtmlTR
	 */
	public function newRow() {
		return $this->getBody()->newRow($this->_colCount);
	}

	public function setValues($values=array()) {
		$this->getBody()->setValues($values);
		return $this;
	}

	public function setHeaderValues($values=array()) {
		return $this->getHeader()->setValues($values);
	}

	public function setFooterValues($values=array()) {
		return $this->getFooter()->setValues($values);
	}

	/**
	 * Sets values to the col at index $colIndex
	 * @param int $colIndex
	 * @param array $values
	 * @return \Ajax\semantic\html\collections\HtmlTable
	 */
	public function setColValues($colIndex, $values=array()) {
		$this->getBody()->setColValues($colIndex, $values);
		return $this;
	}

	/**
	 * Sets values to the row at index $rowIndex
	 * @param int $rowIndex
	 * @param array $values
	 * @return \Ajax\semantic\html\collections\HtmlTable
	 */
	public function setRowValues($rowIndex, $values=array()) {
		$this->getBody()->setRowValues($rowIndex, $values);
		return $this;
	}

	public function addColVariations($colIndex, $variations=array()) {
		return $this->getBody()->addColVariations($colIndex, $variations);
	}

	public function colCenter($colIndex) {
		return $this->colAlign($colIndex, "colCenter");
	}

	public function colRight($colIndex) {
		return $this->colAlign($colIndex, "colRight");
	}

	public function colLeft($colIndex) {
		return $this->colAlign($colIndex, "colLeft");
	}

	private function colAlign($colIndex, $function) {
		if (\is_array($colIndex)) {
			foreach ( $colIndex as $cIndex ) {
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

	public function setCelled() {
		return $this->addToProperty("class", "celled");
	}

	public function setBasic($very=false) {
		if ($very)
			$this->addToPropertyCtrl("class", "very", array ("very" ));
		return $this->addToPropertyCtrl("class", "basic", array ("basic" ));
	}

	public function setCollapsing() {
		return $this->addToProperty("class", "collapsing");
	}

	public function setDefinition() {
		return $this->addToProperty("class", "definition");
	}

	public function setStructured() {
		return $this->addToProperty("class", "structured");
	}

	public function setSortable($colIndex=NULL) {
		if (isset($colIndex) && $this->hasPart("thead")) {
			$this->getHeader()->sort($colIndex);
		}
		return $this->addToProperty("class", "sortable");
	}

	public function setSingleLine() {
		return $this->addToProperty("class", "single line");
	}

	public function setFixed() {
		return $this->addToProperty("class", "fixed");
	}

	public function conditionalCellFormat($callback, $format) {
		$this->getBody()->conditionalCellFormat($callback, $format);
		return $this;
	}

	public function conditionalRowFormat($callback, $format) {
		$this->getBody()->conditionalRowFormat($callback, $format);
		return $this;
	}

	public function applyCells($callback) {
		$this->getBody()->applyCells($callback);
		return $this;
	}

	public function applyRows($callback) {
		$this->getBody()->applyRows($callback);
		return $this;
	}

	public function setSelectable() {
		return $this->addToProperty("class", "selectable");
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->content=JArray::sortAssociative($this->content, [ "thead","tbody","tfoot" ]);
		if ($this->propertyContains("class", "sortable")) {
			$this->addEvent("execute", "$('#" . $this->identifier . "').tablesort();");
		}
		return parent::compile($js, $view);
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$result=$function($object);
		if (\is_array($result)) {
			return $this->addRow($function($object));
		} else {
			return $this->getBody()->_addRow($result);
		}
	}
}