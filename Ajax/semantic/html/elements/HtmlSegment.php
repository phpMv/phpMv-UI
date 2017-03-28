<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\base\constants\SegmentType;
use Ajax\semantic\html\base\traits\AttachedTrait;
use Ajax\semantic\html\base\constants\State;
use Ajax\semantic\html\base\constants\Variation;
use Ajax\semantic\html\base\constants\Emphasis;
use Ajax\semantic\html\base\traits\TextAlignmentTrait;
use Ajax\semantic\html\collections\HtmlGrid;

/**
 * Semantic Segment element
 * @see http://semantic-ui.com/elements/segment.html
 * @author jc
 * @version 1.001
 */
class HtmlSegment extends HtmlSemDoubleElement {
	use AttachedTrait,TextAlignmentTrait;

	public function __construct($identifier, $content="") {
		parent::__construct($identifier, "div", "ui segment");
		$this->_variations=\array_merge($this->_variations, [ Variation::PADDED,Variation::COMPACT ]);
		$this->_states=\array_merge($this->_states, [ State::LOADING ]);
		$this->content=$content;
	}

	/**
	 * Defines the segment type
	 * @param string $type one of "raised","stacked","piled" default : ""
	 * @return \Ajax\semantic\html\elements\HtmlSegment
	 */
	public function setType($type) {
		return $this->addToPropertyCtrl("class", $type, SegmentType::getConstants());
	}

	public function setSens($sens="vertical") {
		return $this->addToPropertyCtrl("class", $sens, array ("vertical","horizontal" ));
	}

	public function setEmphasis($value=Emphasis::SECONDARY) {
		return $this->addToPropertyCtrl("class", $value, Emphasis::getConstants());
	}

	public function setCircular() {
		return $this->addToProperty("class", "circular");
	}

	public function clear() {
		return $this->addToProperty("class", "clearing");
	}

	public function setCompact() {
		return $this->addToProperty("class", "compact");
	}

	public function setBasic() {
		return $this->setProperty("class", "ui basic segment");
	}

	public function asContainer() {
		return $this->addToPropertyCtrl("class", "container", array ("container" ));
	}

	public function addGrid($numRows=1, $numCols=NULL){
		$grid=new HtmlGrid("Grid-".$this->identifier,$numRows,$numCols);
		$this->content=$grid;
		return $grid;
	}
}
