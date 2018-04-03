<?php

namespace Ajax\semantic\html\collections;

use Ajax\semantic\html\content\HtmlGridRow;
use Ajax\semantic\html\base\constants\Wide;
use Ajax\semantic\html\base\constants\VerticalAlignment;
use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\base\traits\TextAlignmentTrait;
use Ajax\semantic\html\content\HtmlGridCol;

/**
 * Semantic Grid component
 * @see http://semantic-ui.com/collections/grid.html
 * @author jc
 * @version 1.001
 */
class HtmlGrid extends HtmlSemCollection {
	use TextAlignmentTrait;
	private $_createCols;
	private $_colSizing=true;
	private $_implicitRows=false;

	public function __construct($identifier, $numRows=1, $numCols=NULL, $createCols=true, $implicitRows=false) {
		parent::__construct($identifier, "div", "ui grid");
		$this->_implicitRows=$implicitRows;
		$this->_createCols=$createCols;
		if (isset($numCols)) {
			$this->_colSizing=false;
			$this->setWide($numCols);
		}
		if($createCols)
			$this->setRowsCount($numRows, $numCols);
	}

	public function asSegment() {
		return $this->addToPropertyCtrl("class", "segment", array ("segment" ));
	}

	public function asContainer() {
		return $this->addToPropertyCtrl("class", "container", array ("container" ));
	}

	/**
	 * Defines the grid width (alias for setWidth)
	 * @param int $wide
	 */
	public function setWide($wide) {
		if(isset(Wide::getConstants()["W" . $wide])){
			$wide=Wide::getConstants()["W" . $wide];
			$this->addToPropertyCtrl("class", $wide, Wide::getConstants());
			return $this->addToPropertyCtrl("class", "column", array ("column" ));
		}
		return $this;
	}

	/**
	 * Defines the grid width
	 * @param int $width
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function setWidth($width) {
		return $this->setWide($width);
	}

	/**
	 * Adds a row with $colsCount columns
	 * @param int $colsCount number of columns to create
	 * @return mixed
	 */
	public function addRow($colsCount=NULL) {
		$rowCount=$this->rowCount() + 1;
		$this->setRowsCount($rowCount, $colsCount, true);
		return $this->content[$rowCount - 1];
	}

	/**
	 * Adds a col
	 * @param int $width with of the column to add
	 * @return mixed|\Ajax\semantic\html\collections\HtmlGrid
	 */
	public function addCol($width=NULL) {
		$colCount=$this->colCount() + 1;
		$this->setColsCount($colCount, true, $width);
		if ($this->hasOnlyCols($this->count()))
			return $this->content[$colCount - 1];
		return $this;
	}

	/**
	 *
	 * @param array $sizes array of width of the columns to create
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function addCols($sizes=array()) {
		foreach ( $sizes as $size ) {
			$this->addCol($size);
		}
		return $this;
	}

	/**
	 * Create $rowsCount rows
	 * @param int $rowsCount
	 * @param int $colsCount
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function setRowsCount($rowsCount, $colsCount=NULL, $force=false) {
		$count=$this->count();
		if ($rowsCount < 2 && $force === false) {
			for($i=$count; $i < $colsCount; $i++) {
				$this->addItem(new HtmlGridCol("col-" . $this->identifier . "-" . $i));
			}
		} else {
			if ($this->hasOnlyCols($count)) {
				$tmpContent=$this->content;
				$item=$this->addItem($colsCount);
				$item->setContent($tmpContent);
				$this->content=array ();
				$count=1;
			}
			for($i=$count; $i < $rowsCount; $i++) {
				$this->addItem($colsCount);
			}
		}
		return $this;
	}

	protected function hasOnlyCols($count) {
		return $count > 0 && $this->content[0] instanceof HtmlGridCol;
	}

	/**
	 * Defines the number of columns in the grid
	 * @param int $numCols
	 * @param boolean $toCreate
	 * @param int $width
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function setColsCount($numCols, $toCreate=true, $width=NULL) {
		if (isset($width)===false) {
			$this->setWide($numCols);
		}
		if ($toCreate === true) {
			$count=$this->count();
			if ($count == 0 || $this->hasOnlyCols($count)) {
				for($i=$count; $i < $numCols; $i++) {
					$this->addItem(new HtmlGridCol("col-" . $this->identifier . "-" . $i, $width));
				}
			} else {
				for($i=0; $i < $count; $i++) {
					$this->getItem($i)->setColsCount($numCols);
				}
			}
		}
		return $this;
	}

	/**
	 * return the row at $index
	 * @param int $index
	 * @return HtmlGridRow
	 */
	public function getRow($index) {
		return $this->getItem($index);
	}
	
	/**
	 * @return HtmlGridRow
	 */
	public function getItem($index){
		return parent::getItem($index);
	}

	/**
	 * Returns the row count
	 * @return int
	 */
	public function rowCount() {
		$count=$this->count();
		if ($this->hasOnlyCols($count))
			return 0;
		return $count;
	}

	/**
	 * Returns the column count
	 * @return int
	 */
	public function colCount() {
		$count=$this->count();
		if ($this->hasOnlyCols($count))
			return $count;
		if ($count > 0)
			return $this->getItem(0)->count();
		return 0;
	}

	/**
	 * Returns the cell (HtmlGridCol) at position $row,$col
	 * @param int $row
	 * @param int $col
	 * @return HtmlGridCol|HtmlGridRow
	 */
	public function getCell($row, $col) {
		if ($row < 2 && $this->hasOnlyCols($this->count()))
			return $this->getItem($col);
		$rowO=$this->getItem($row);
		if (isset($rowO)) {
			$colO=$rowO->getItem($col);
		}
		return $colO;
	}

	/**
	 * Adds dividers between columns ($vertically=false) or between rows ($vertically=true)
	 * @param boolean $vertically
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function setDivided($vertically=false) {
		$value=($vertically === true) ? "vertically divided" : "divided";
		return $this->addToPropertyCtrl("class", $value, array ("divided" ));
	}

	/**
	 * Divides rows into cells
	 * @param boolean $internally true for internal cells
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function setCelled($internally=false) {
		$value=($internally === true) ? "internally celled" : "celled";
		return $this->addToPropertyCtrl("class", $value, array ("celled","internally celled" ));
	}

	/**
	 * A grid can have its columns centered
	 */
	public function setCentered() {
		return $this->addToPropertyCtrl("class", "centered", array ("centered" ));
	}

	/**
	 * automatically resize all elements to split the available width evenly
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function setEqualWidth() {
		return $this->addToProperty("class", "equal width");
	}

	/**
	 * Adds vertical or/and horizontal gutters
	 * @param string $value
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function setPadded($value=NULL) {
		if (isset($value))
			$this->addToPropertyCtrl("class", $value, array ("vertically","horizontally" ));
		return $this->addToProperty("class", "padded");
	}

	/**
	 *
	 * @param boolean $very
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function setRelaxed($very=false) {
		$value=($very === true) ? "very relaxed" : "relaxed";
		return $this->addToPropertyCtrl("class", $value, array ("relaxed","very relaxed" ));
	}

	public function setVerticalAlignment($value=VerticalAlignment::MIDDLE) {
		return $this->addToPropertyCtrl("class", $value . " aligned", VerticalAlignment::getConstantValues("aligned"));
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\HtmlCollection::createItem()
	 */
	protected function createItem($value) {
		if ($this->_createCols === false)
			$value=null;
		$item=new HtmlGridRow($this->identifier . "-row-" . ($this->count() + 1), $value, $this->_colSizing, $this->_implicitRows);
		return $item;
	}

	/**
	 * Sets $values to the grid
	 * @param array $values
	 */
	public function setValues($values, $force=true) {
		$count=$this->count();
		$valuesSize=\sizeof($values);
		if ($this->_createCols === false || $force === true) {
			for($i=$count; $i < $valuesSize; $i++) {
				$colSize=\sizeof($values[$i]);
				$this->addItem(new HtmlGridRow($this->identifier . "-row-" . ($this->count() + 1), $colSize, $this->_colSizing, $this->_implicitRows));
			}
		}
		$count=\min(array ($this->count(),$valuesSize ));
		for($i=0; $i < $count; $i++) {
			$this->content[$i]->setValues($values[$i], $this->_createCols === false);
		}
	}
	
	public function setColWidth($numCol,$width){
		foreach ($this->content as $row){
			$row->getCol($numCol)->setWidth($width);
		}
		return $this;
	}

	/**
	 * stretch the row contents to take up the entire column height
	 * @return \Ajax\semantic\html\content\HtmlGridRow
	 */
	public function setStretched() {
		return $this->addToProperty("class", "stretched");
	}

	/**
	 * Adds a divider after the specified col
	 * @param integer $afterColIndex
	 * @param boolean $vertical
	 * @param mixed $content
	 * @return \Ajax\semantic\html\collections\HtmlGrid
	 */
	public function addDivider($afterColIndex, $vertical=true, $content=NULL) {
		$col=$this->getCell(0, $afterColIndex);
		if($col instanceof HtmlGridCol)
			$col->addDivider($vertical, $content);
		return $this;
	}
}
