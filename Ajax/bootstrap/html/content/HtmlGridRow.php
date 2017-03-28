<?php
namespace Ajax\bootstrap\html\content;

use Ajax\bootstrap\html\base\CssSize;
use Ajax\JsUtils;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;

/**
 * Inner element for Twitter Bootstrap Grid row
 * @see http://getbootstrap.com/css/#grid
 * @author jc
 * @version 1.001
 */
class HtmlGridRow extends HtmlBsDoubleElement {
	private $cols;
	public function __construct($identifier,$numCols=NULL){
		parent::__construct($identifier,"div");
		$this->setProperty("class", "row");
		$this->cols=array();
		if(isset($numCols)){
			$numCols=min(12,$numCols);
			$numCols=max(1,$numCols);
			$width=12/$numCols;
			for ($i=0;$i<$numCols;$i++){
				$this->addCol(CssSize::SIZE_MD,$width);
			}
		}
	}

	public function addCol($size=CssSize::SIZE_MD,$width=1){
		$col=new HtmlGridCol($this->identifier."-col-".(sizeof($this->cols)+1),$size,$width);
		$this->cols[]=$col;
		return $col;
	}

	public function addColAt($size=CssSize::SIZE_MD,$width=1,$offset=1){
		$col=$this->addCol($size,$width);
		return $col->setOffset($size, max($offset,sizeof($this->cols)+1));
	}

	public function getCol($index,$force=true){
		$result=null;
		if($index<sizeof($this->cols)+1){
			$result=$this->cols[$index-1];
		}else if ($force){
			$result=$this->addColAt(CssSize::SIZE_MD,1,$index);
		}
		return $result;
	}

	public function getColAt($offset,$force=true){
		$result=null;
		foreach ($this->cols as $col){
			$offsets=$col->getOffsets();
			if($result=array_search($offset, $offsets)){
				break;
			}
		}
		if(!$result || isset($result)===false){
			$result=$this->getCol($offset,$force);
		}
		return $result;
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {

		foreach ($this->cols as $col){
			$this->addContent($col);
		}
		return parent::compile($js,$view);
	}
	public function getCols() {
		return $this->cols;
	}

	public function setContentForAll($content){
		foreach ($this->cols as $col){
			$col->setContent($content);
		}
	}
	public function merge($size=CssSize::SIZE_MD,$start,$width){
		$col=$this->getColAt($start,false);
		if(isset($col)){
			$col->setWidth($size,$width+1);
			$this->delete($size,$start+1, $width);
		}
	}
	public function delete($size=CssSize::SIZE_MD,$start,$width){
		while($start<sizeof($this->cols)+1 && $width>0){
			$col=$this->getColAt($start,false);
			if(isset($col)){
				$widthCol=$col->getWidth($size);
				if($widthCol<=$width){
					unset($this->cols[$start-1]);
					$this->cols = array_values($this->cols);
					$width=$width-$widthCol;
				}
			}else{
				$width=0;
			}
		}
	}
}
