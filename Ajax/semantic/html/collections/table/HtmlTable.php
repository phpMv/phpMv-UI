<?php

namespace Ajax\semantic\html\collections\table;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\content\table\HtmlTableContent;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\JsUtils;

use Ajax\service\JArray;
use Ajax\semantic\html\content\table\HtmlTR;
use Ajax\semantic\html\collections\table\traits\TableTrait;

/**
 * Semantic HTML Table component
 * @author jc
 *
 */
class HtmlTable extends HtmlSemDoubleElement {
	use TableTrait;
	private $_colCount;
	private $_compileParts;
	private $_footer;
	private $_afterCompileEvents;

	public function __construct($identifier, $rowCount, $colCount) {
		parent::__construct($identifier, "table", "ui table");
		$this->content=array ();
		$this->setRowCount($rowCount, $colCount);
		$this->_variations=[ Variation::CELLED,Variation::PADDED,Variation::COMPACT ];
		$this->_compileParts=["thead","tbody","tfoot"];
		$this->_afterCompileEvents=[];
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\collections\table\TableTrait::getTable()
	 */
	protected function getTable() {
		return $this;
	}

	/**
	 * Returns/create eventually a part of the table corresponding to the $key : thead, tbody or tfoot
	 * @param string $key
	 * @return HtmlTableContent
	 */
	public function getPart($key) {
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
	 * @return HtmlTR
	 */
	public function addRow($values=array()) {
		$row=$this->getBody()->addRow($this->_colCount);
		$row->setValues(\array_values($values));
		return $row;
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

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if(\sizeof($this->_compileParts)<3){
			$this->_template="%content%";
			$this->refresh();
		}else{
			if ($this->propertyContains("class", "sortable")) {
				$this->addEvent("execute", "$('#" . $this->identifier . "').tablesort();");
			}
		}
		$this->content=JArray::sortAssociative($this->content, $this->_compileParts);
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
			$result= $this->addRow($function($object));
		} else {
			$result= $this->getBody()->_addRow($result);
		}
		if(isset($this->_afterCompileEvents["onNewRow"])){
			if(\is_callable($this->_afterCompileEvents["onNewRow"]))
				$this->_afterCompileEvents["onNewRow"]($result,$object);
		}
		return $result;
	}

	/**
	 * @param array $parts
	 * @return \Ajax\semantic\html\collections\HtmlTable
	 */
	public function setCompileParts($parts=["tbody"]) {
		$this->_compileParts=$parts;
		return $this;
	}

	public function refresh(){
		$this->_footer=$this->getFooter();
		$this->addEvent("execute", '$("#'.$this->identifier.' tfoot").replaceWith("'.\addslashes($this->_footer).'");');
	}

	public function run(JsUtils $js){
		$result= parent::run($js);
		if(isset($this->_footer))
			$this->_footer->run($js);
		return $result;
	}

	/**
	 * The callback function called after the insertion of each row when fromDatabaseObjects is called
	 * callback function takes the parameters $row : the row inserted and $object: the instance of model used
	 * @param callable $callback
	 * @return \Ajax\semantic\html\collections\HtmlTable
	 */
	public function onNewRow($callback) {
		$this->_afterCompileEvents["onNewRow"]=$callback;
		return $this;
	}
}