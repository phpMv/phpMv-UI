<?php
namespace Ajax\bootstrap\html\content;

use Ajax\bootstrap\html\base\CssSize;
use Ajax\JsUtils;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;

/**
 * Inner element for Twitter Bootstrap Grid col
 * @see http://getbootstrap.com/css/#grid
 * @author jc
 * @version 1.001
 */
class HtmlGridCol extends HtmlBsDoubleElement {
	private $positions;
	private $offsets;
	public function __construct($identifier,$size=CssSize::SIZE_MD,$width=1){
		parent::__construct($identifier,"div");
		$this->positions=array();
		$this->offsets=array();
		$this->addPosition($size,$width);
	}
	public function addPosition($size=CssSize::SIZE_MD,$width=1){
		$this->positions[$size]=$width;
		return $this;
	}
	private function _generateClass(){
		$result=array();
		foreach ($this->positions as $size=>$width){
			$result[]="col-".$size."-".$width;
		}
		foreach ($this->offsets as $size=>$offset){
			$result[]="col-".$size."-offset-".$offset;
		}
		return implode(" ", $result);
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->setProperty("class", $this->_generateClass());
		return parent::compile($js,$view);
	}

	public function setOffset($size,$offset){
		$this->offsets[$size]=$offset;
		return $this;
	}

	public function setOffsetForAll($newOffset){
		foreach ($this->offsets as &$value){
			$value=$newOffset;
		}
		unset($value);
		return $this;
	}

	public function setWidthForAll($newWidth){
		foreach ($this->positions as &$pos){
			$pos=$newWidth;
		}
		unset($pos);
		return $this;
	}

	public function setWidth($size=CssSize::SIZE_MD,$width=1){
		$this->positions[$size]=$width;
		return $this;
	}

	public function setPosition($size=CssSize::SIZE_MD,$width=1){
		return $this->addPosition($size,$width);
	}

	public function getWidth($size){
		return @$this->positions[$size];
	}

	public function getOffest($size){
		return @$this->offsets[$size];
	}

	public function addClear(){
		$this->wrap("","<div class='clearfix'></div>");
	}
	public function setOffsets($offsets) {
		$this->offsets = $offsets;
		return $this;
	}

	public function copy($identifier){
		$result=new HtmlGridCol($identifier);
		$result->setPositions($this->positions);
		$result->setOffsets($this->offsets);
		return $result;
	}
	public function setPositions($positions) {
		$this->positions = $positions;
		return $this;
	}
	public function getOffsets() {
		return $this->offsets;
	}



}
