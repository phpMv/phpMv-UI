<?php

namespace Ajax\semantic\html\content\table;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\traits\TextAlignmentTrait;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\base\traits\TableElementTrait;
use Ajax\semantic\html\elements\html5\HtmlLink;

class HtmlTD extends HtmlSemDoubleElement {
	use TextAlignmentTrait,TableElementTrait;
	private $_container;
	private $_row;
	private $_col;

	/**
	 *
	 * @param string $identifier
	 * @param mixed $content
	 * @param string $tagName
	 */
	public function __construct($identifier, $content=NULL, $tagName="td") {
		parent::__construct($identifier, $tagName, "", $content);
		$this->_variations=[ Variation::COLLAPSING ];
		$this->_states=[ State::ACTIVE,State::POSITIVE,State::NEGATIVE,State::WARNING,State::ERROR,State::DISABLED ];
	}

	public function setContainer($container, $row, $col) {
		$this->_container=$container;
		$this->_row=$row;
		$this->_col=$col;
	}

	public function setValue($value) {
		$this->content=$value;
		return $this;
	}

	public function setRowspan($rowspan) {
		$to=min($this->_container->count(), $this->_row + $rowspan - 1);
		for($i=$to; $i > $this->_row; $i--) {
			$this->_container->delete($i, $this->_col);
		}
		$this->setProperty("rowspan", $rowspan);
		return $this->_container;
	}

	public function mergeRow() {
		return $this->setRowspan($this->_container->count());
	}

	public function mergeCol() {
		return $this->setColspan($this->_container->getRow($this->_row)->count());
	}

	public function setColspan($colspan) {
		$to=min($this->_container->getRow($this->_row)->count(), $this->_col + $colspan - 1);
		for($i=$to; $i > $this->_col; $i--) {
			$this->_container->delete($this->_row, $this->_col + 1);
		}
		$this->setProperty("colspan", $colspan);
		return $this->_container;
	}

	public function getColspan() {
		$colspan=1;
		if (\array_key_exists("colspan", $this->properties))
			$colspan=$this->getProperty("colspan");
		return $colspan;
	}

	public function getRowspan() {
		$rowspan=1;
		if (\array_key_exists("rowspan", $this->properties))
			$rowspan=$this->getProperty("rowspan");
		return $rowspan;
	}

	public function conditionalCellFormat($callback, $format) {
		if ($callback($this)) {
			$this->addToProperty("class", $format);
		}
		return $this;
	}

	public function apply($callback) {
		$callback($this);
		return $this;
	}

	public function setSelectable($href="#") {
		if (\is_string($this->content)) {
			$this->content=new HtmlLink("", $href, $this->content);
		}
		return $this->addToProperty("class", "selectable");
	}
}