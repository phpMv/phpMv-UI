<?php

namespace Ajax\semantic\html\content\table;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\base\traits\TableElementTrait;
use Ajax\service\JArray;

/**
 *
 * @author jc
 *
 */
class HtmlTR extends HtmlSemCollection {
	use TableElementTrait;
	private $_tdTagName;
	private $_container;
	private $_row;

	public function __construct($identifier) {
		parent::__construct($identifier, "tr", "");
		$this->_states=[ State::ACTIVE,State::POSITIVE,State::NEGATIVE,State::WARNING,State::ERROR,State::DISABLED ];
	}

	public function setColCount($colCount) {
		$count=$this->count();
		for($i=$count; $i < $colCount; $i++) {
			$item=$this->addItem(NULL);
			$item->setTagName($this->_tdTagName);
		}
		return $this;
	}

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\common\html\HtmlCollection::createItem()
	 */
	protected function createItem($value) {
		$count=$this->count();
		$td=new HtmlTD("", $value, $this->_tdTagName);
		$td->setContainer($this->_container, $this->_row, $count);
		return $td;
	}

	public function setTdTagName($tagName="td") {
		$this->_tdTagName=$tagName;
	}

	/**
	 * Define the container (HtmlTableContent) and the num of the row
	 * @param HtmlTableContent $container
	 * @param int $row
	 */
	public function setContainer($container, $row) {
		$this->_container=$container;
		$this->_row=$row;
	}

	/**
	 * Sets values to the row cols
	 * @param mixed $values
	 */
	public function setValues($values=array()) {
		$count=$this->count();
		if (\is_array($values) === false) {
			$values=\array_fill(0, $count, $values);
		} else {
			if (JArray::isAssociative($values) === true) {
				$values=\array_values($values);
			}
		}
		$count=\min(\sizeof($values), $count);
		
		for($i=0; $i < $count; $i++) {
			$cell=$this->content[$i];
			$cell->setValue($values[$i]);
		}
	}

	/**
	 * Removes the col at $index
	 * @param int $index the index of the col to remove
	 * @return \Ajax\semantic\html\content\table\HtmlTR
	 */
	public function delete($index) {
		$this->removeItem($index);
		return $this;
	}

	public function mergeCol($colIndex=0) {
		return $this->getItem($colIndex)->mergeCol();
	}

	public function mergeRow($colIndex=0) {
		return $this->getItem($colIndex)->mergeRow();
	}

	public function getColPosition($colIndex) {
		$pos=0;
		$rows=$this->_container->getContent();
		for($i=0; $i < $this->_row; $i++) {
			$max=\min($colIndex, $rows[$i]->count());
			for($j=0; $j < $max; $j++) {
				$rowspan=$rows[$i]->getItem($j)->getRowspan();
				if ($rowspan + $i > $this->_row)
					$pos++;
			}
		}
		if ($pos > $colIndex)
			return NULL;
		$count=$this->count();
		for($i=0; $i < $count; $i++) {
			$pos+=$this->content[$i]->getColspan();
			if ($pos >= $colIndex + 1)
				return $i;
		}
		return null;
	}

	public function conditionalCellFormat($callback, $format) {
		$cells=$this->content;
		foreach ( $cells as $cell ) {
			$cell->conditionalCellFormat($callback, $format);
		}
		return $this;
	}

	public function conditionalRowFormat($callback, $format) {
		if ($callback($this)) {
			$this->addToProperty("class", $format);
		}
		return $this;
	}

	public function containsStr($needle) {
		$cells=$this->content;
		foreach ( $cells as $cell ) {
			if (\strpos($cell->getContent(), $needle) !== false)
				return true;
		}
		return false;
	}

	public function apply($callback) {
		$callback($this);
		return $this;
	}

	public function applyCells($callback) {
		$cells=$this->content;
		foreach ( $cells as $cell ) {
			$cell->apply($callback);
		}
		return $this;
	}
}