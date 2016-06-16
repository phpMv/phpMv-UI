<?php

namespace Ajax\semantic\html\content\table;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\service\JArray;

/**
 * a table content (thead, tbody or tfoot)
 * @author jc
 *
 */
class HtmlTableContent extends HtmlSemCollection {
	protected $_tdTagNames=[ "thead" => "th","tbody" => "td","tfoot" => "th" ];

	/**
	 *
	 * @param string $identifier
	 * @param string $tagName
	 * @param int $rowCount
	 * @param int $colCount
	 */
	public function __construct($identifier, $tagName="tbody", $rowCount=NULL, $colCount=NULL) {
		parent::__construct($identifier, $tagName, "");
		if (isset($rowCount) && isset($colCount))
			$this->setRowCount($rowCount, $colCount);
	}

	/**
	 *
	 * @param int $rowCount
	 * @param int $colCount
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function setRowCount($rowCount, $colCount) {
		$count=$this->count();
		for($i=$count; $i < $rowCount; $i++) {
			$this->addItem($colCount);
		}
		/*
		 * for($i=0; $i < $rowCount; $i++) {
		 * $item=$this->content[$i];
		 * $item->setTdTagName($this->_tdTagNames[$this->tagName]);
		 * $this->content[$i]->setColCount($colCount);
		 * }
		 */
		return $this;
	}

	public function getTdTagName($tagName) {
		return $this->_tdTagNames[$this->tagName];
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\HtmlCollection::createItem()
	 */
	protected function createItem($value) {
		$count=$this->count();
		$tr=new HtmlTR("", $value);
		$tr->setContainer($this, $count);
		$tr->setTdTagName($this->_tdTagNames[$this->tagName]);
		if (isset($value) === true) {
			$tr->setColCount($value);
		}
		return $tr;
	}

	public function newRow($value) {
		return $this->createItem($value);
	}

	public function addRow($colCount) {
		return $this->addItem($colCount);
	}

	public function _addRow($row) {
		$this->addItem($row);
	}

	/**
	 * Returns the cell (HtmlTD) at position $row,$col
	 * @param int $row
	 * @param int $col
	 * @return \Ajax\semantic\html\content\HtmlTD
	 */
	public function getCell($row, $col) {
		$row=$this->getItem($row);
		if (isset($row)) {
			$col=$row->getItem($col);
		}
		return $col;
	}

	/**
	 *
	 * @param int $index
	 * @return \Ajax\semantic\html\content\HtmlTR
	 */
	public function getRow($index) {
		return $this->getItem($index);
	}

	/**
	 *
	 * @param int $row
	 * @param int $col
	 * @param mixed $value
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function setCellValue($row, $col, $value="") {
		$cell=$this->getCell($row, $col);
		if (isset($cell) === true) {
			$cell->setValue($value);
		}
		return $this;
	}

	/**
	 * Sets the cells values
	 * @param mixed $values
	 */
	public function setValues($values=array()) {
		$count=$this->count();
		$isArray=true;
		if (\is_array($values) === false) {
			$values=\array_fill(0, $count, $values);
			$isArray=false;
		}
		if (JArray::dimension($values) == 1 && $isArray)
			$values=[ $values ];

		$count=\min(\sizeof($values), $count);

		for($i=0; $i < $count; $i++) {
			$row=$this->content[$i];
			$row->setValues($values[$i]);
		}
		return $this;
	}

	public function setColValues($colIndex, $values=array()) {
		$count=$this->count();
		if (\is_array($values) === false) {
			$values=\array_fill(0, $count, $values);
		}
		$count=\min(\sizeof($values), $count);
		for($i=0; $i < $count; $i++) {
			$this->getCell($i, $colIndex)->setValue($values[$i]);
		}
		return $this;
	}

	public function addColVariations($colIndex, $variations=array()) {
		$count=$this->count();
		for($i=0; $i < $count; $i++) {
			$this->getCell($i, $colIndex)->addVariations($variations);
		}
		return $this;
	}

	public function setRowValues($rowIndex, $values=array()) {
		$count=$this->count();
		if (\is_array($values) === false) {
			$values=\array_fill(0, $count, $values);
		}
		$this->getItem($rowIndex)->setValues($values);
		return $this;
	}

	private function colAlign($colIndex, $function) {
		$count=$this->count();
		for($i=0; $i < $count; $i++) {
			$index=$this->content[$i]->getColPosition($colIndex);
			if ($index !== NULL)
				$this->getCell($i, $index)->$function();
		}
		return $this;
	}

	public function colCenter($colIndex) {
		return $this->colAlign($colIndex, "textCenterAligned");
	}

	public function colRight($colIndex) {
		return $this->colAlign($colIndex, "textRightAligned");
	}

	public function colLeft($colIndex) {
		return $this->colAlign($colIndex, "textLeftAligned");
	}

	/**
	 * Returns the number of rows (TR)
	 * @return int
	 */
	public function getRowCount() {
		return $this->count();
	}

	/**
	 * Returns the number of columns (TD)
	 * @return int
	 */
	public function getColCount() {
		$result=0;
		if ($this->count() > 0)
			$result=$this->getItem(0)->getColCount();
		return $result;
	}

	/**
	 * Removes the cell at position $rowIndex,$colIndex
	 * @param int $rowIndex
	 * @param int $colIndex
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function delete($rowIndex, $colIndex=NULL) {
		if (isset($colIndex)) {
			$row=$this->getItem($rowIndex);
			if (isset($row) === true) {
				$row->delete($colIndex);
			}
		} else {
			$this->removeItem($rowIndex);
		}
		return $this;
	}

	public function mergeCol($rowIndex=0, $colIndex=0) {
		return $this->getItem($rowIndex)->mergeCol($colIndex);
	}

	public function mergeRow($rowIndex=0, $colIndex=0) {
		return $this->getItem($rowIndex)->mergeRow($colIndex);
	}

	public function setFullWidth() {
		return $this->addToProperty("class", "full-width");
	}

	public function sort($colIndex) {
		$this->content[0]->getItem($colIndex)->addToProperty("class", "sorted ascending");
	}

	/**
	 * @param mixed $callback
	 * @param string $format
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function conditionalCellFormat($callback, $format) {
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$row->conditionalCellFormat($callback, $format);
		}
		return $this;
	}

	/**
	 * @param mixed $callback
	 * @param string $format
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function conditionalRowFormat($callback, $format) {
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$row->conditionalRowFormat($callback, $format);
		}
		return $this;
	}

	/**
	 * @param mixed $callback
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function applyCells($callback) {
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$row->applyCells($callback);
		}
		return $this;
	}

	/**
	 * @param mixed $callback
	 * @return \Ajax\semantic\html\content\table\HtmlTableContent
	 */
	public function applyRows($callback) {
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$row->apply($callback);
		}
		return $this;
	}
}