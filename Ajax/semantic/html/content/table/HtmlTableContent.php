<?php

namespace Ajax\semantic\html\content\table;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\service\JArray;
use Ajax\common\html\HtmlCollection;
use Ajax\common\html\HtmlDoubleElement;
use Ajax\semantic\html\base\traits\BaseTrait;

/**
 * a table content (thead, tbody or tfoot)
 * @author jc
 *
 */
class HtmlTableContent extends HtmlSemCollection {
	protected $_tdTagNames=[ "thead" => "th","tbody" => "td","tfoot" => "th" ];
	protected $_merged=false;

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
	 * @return HtmlTableContent
	 */
	public function setRowCount($rowCount, $colCount) {
		$count=$this->count();
		for($i=$count; $i < $rowCount; $i++) {
			$this->addItem($colCount);
		}
		return $this;
	}

	public function getTdTagName($tagName) {
		return $this->_tdTagNames[$this->tagName];
	}
	
	public function refreshTR(){
		$this->_template="%wrapContentBefore%%content%%wrapContentAfter%";
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\HtmlCollection::createItem()
	 * @return HtmlTR
	 */
	protected function createItem($value) {
		$count=$this->count();
		$tr=new HtmlTR("");
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

	/**
	 * @param int $colCount
	 * @return HtmlTR
	 */
	public function addRow($colCount) {
		return $this->addItem($colCount);
	}

	/**
	 * @param mixed $row
	 * @return HtmlTR
	 */
	public function _addRow($row) {
		return $this->addItem($row);
	}
	
	public function addMergeRow($colCount,$value=null){
		$row=$this->addRow($colCount);
		$row->mergeCol();
		if(isset($value)){
			$row->setValues([$value]);
		}
		return $row;
	}
	
	/**
	 * @return HtmlTR
	 */
	public function getItem($index){
		return parent::getItem($index);
	}

	/**
	 * Returns the cell (HtmlTD) at position $row,$col
	 * @param int $row
	 * @param int $col
	 * @return HtmlTD|HtmlDoubleElement
	 */
	public function getCell($row, $col) {
		$row=$this->getItem($row);
		if (isset($row) && $row instanceof HtmlCollection) {
			$col=$row->getItem($col);
		}else{
			$col=$row;
		}
		return $col;
	}

	/**
	 *
	 * @param int $index
	 * @return HtmlTR
	 */
	public function getRow($index) {
		return $this->getItem($index);
	}

	/**
	 *
	 * @param int $row
	 * @param int $col
	 * @param mixed $value
	 * @return HtmlTableContent
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
		return $this->_addOrSetValues($values, function(HtmlTR $row,$_values){$row->setValues($_values);});
	}

	/**
	 * Adds the cells values
	 * @param mixed $values
	 */
	public function addValues($values=array()) {
		return $this->_addOrSetValues($values, function(HtmlTR $row,$_values){$row->addValues($_values);});
	}

	/**
	 * Adds or sets the cells values
	 * @param mixed $values
	 * @param callable $callback
	 */
	protected function _addOrSetValues($values,$callback) {
		$count=$this->count();
		$isArray=true;
		if (!\is_array($values)) {
			$values=\array_fill(0, $count, $values);
			$isArray=false;
		}
		if (JArray::dimension($values) == 1 && $isArray)
			$values=[ $values ];

		$count=\min(\sizeof($values), $count);

		for($i=0; $i < $count; $i++) {
			$row=$this->content[$i];
			$callback($row,$values[$i]);
		}
		return $this;
	}

	public function setColValues($colIndex, $values=array()) {
		$count=$this->count();
		if (!\is_array($values)) {
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
			$cell=$this->getCell($i, $colIndex);
			if($cell instanceof BaseTrait)
				$cell->addVariations($variations);
		}
		return $this;
	}
	
	public function addPropertyCol($colIndex, $name,$value) {
		$count=$this->count();
		for($i=0; $i < $count; $i++) {
			$cell=$this->getCell($i, $colIndex);
			if(isset($cell))
				$cell->addToProperty($name,$value);
		}
		return $this;
	}

	public function setRowValues($rowIndex, $values=array()) {
		$count=$this->count();
		if (!\is_array($values)) {
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
	
	private function colAlignFromRight($colIndex, $function) {
		$count=$this->count();
		for($i=0; $i < $count; $i++) {
			$maxRow=$this->content[$i]->count();
			$index=$maxRow-$colIndex-1;
			if (($cell=$this->getCell($i, $index))!== NULL){
				if($cell->getColspan()==1)
					$cell->$function();
			}
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
	
	public function colCenterFromRight($colIndex) {
		return $this->colAlignFromRight($colIndex, "textCenterAligned");
	}
	
	public function colRightFromRight($colIndex) {
		return $this->colAlignFromRight($colIndex, "textRightAligned");
	}
	
	public function colLeftFromRight($colIndex) {
		return $this->colAlignFromRight($colIndex, "textLeftAligned");
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
			$result=$this->getItem(0)->count();
		return $result;
	}

	/**
	 * Removes the cell at position $rowIndex,$colIndex
	 * @param int $rowIndex
	 * @param int $colIndex
	 * @return HtmlTableContent
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

	public function toDelete($rowIndex, $colIndex){
		$row=$this->getItem($rowIndex);
		if (isset($row) === true)
			$row->toDelete($colIndex);
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
		$this->content[0]->getItem($colIndex)->addToProperty("class", "default-sort");
		return $this;
	}

	/**
	 * @param mixed $callback
	 * @param string $format
	 * @return HtmlTableContent
	 */
	public function conditionalCellFormat($callback, $format) {
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$row->conditionalCellFormat($callback, $format);
		}
		return $this;
	}

	public function conditionalColFormat($colIndex,$callback,$format){
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$cell=$row->getItem($colIndex);
			$cell->conditionnalCellFormat($callback,$format);
		}
		return $this;
	}

	/**
	 * @param mixed $callback
	 * @param string $format
	 * @return HtmlTableContent
	 */
	public function conditionalRowFormat($callback, $format) {
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$row->conditionalRowFormat($callback, $format);
		}
		return $this;
	}

	public function hideColumn($colIndex){
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$cell=$row->getItem($colIndex);
			$cell->addToProperty("style","display:none;");
		}
		return $this;
	}

	/**
	 * @param mixed $callback
	 * @return HtmlTableContent
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
	 * @return HtmlTableContent
	 */
	public function applyRows($callback) {
		$rows=$this->content;
		foreach ( $rows as $row ) {
			$row->apply($callback);
		}
		return $this;
	}

	public function mergeIdentiqualValues($colIndex,$function="strip_tags"){
		$rows=$this->content;
		$identiqual=null;
		$counter=0;
		$cellToMerge=null;
		$functionExists=\function_exists($function);
		foreach ( $rows as $row ) {
			$cell=$row->getItem($colIndex);
			$value=$cell->getContent();
			if($functionExists)
				$value=\call_user_func($function,$value);
			if($value!==$identiqual){
				if($counter>0 && isset($cellToMerge)){
					$cellToMerge->setRowspan($counter);
				}
				$counter=0;
				$cellToMerge=$cell;
				$identiqual=$value;
			}
			$counter++;
		}
		if($counter>0 && isset($cellToMerge)){
			$cellToMerge->setRowspan($counter);
		}
		return $this;
	}

	public function _isMerged(){
		return $this->_merged;
	}

	public function _setMerged($value){
		$this->_merged=$value;
		return $this;
	}
}
