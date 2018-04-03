<?php
namespace Ajax\bootstrap\html;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\content\HtmlGridRow;
use Ajax\bootstrap\html\content\HtmlGridCol;
use Ajax\JsUtils;


/**
 * Composant Twitter Bootstrap Grid system
 * @see http://getbootstrap.com/css/#grid
 * @author jc
 * @version 1.001
 */
class HtmlGridSystem extends HtmlBsDoubleElement {
	private $rows;
	
	public function __construct($identifier,$numRows=1,$numCols=NULL){
		parent::__construct($identifier,"div");
		$this->setProperty("class", "container-fluid");
		$this->rows=array();
		$this->setNumRows($numRows,$numCols);
	}
	
	/**
	 * Add a new row
	 * @param int $numCols 
	 * @return \Ajax\bootstrap\html\content\HtmlGridRow
	 */
	public function addRow($numCols=NULL){
		$row=new HtmlGridRow($this->identifier."-row-".(sizeof($this->rows)+1),$numCols);
		$this->rows[]=$row;
		return $row;
	}
	
	/**
	 * return the row at $index
	 * @param int $index
	 * @param boolean $force add the row at $index if true
	 * @return \Ajax\bootstrap\html\content\HtmlGridRow
	 */
	public function getRow($index,$force=true){
		if($index<sizeof($this->rows)){
			$result=$this->rows[$index-1];
		}else if ($force){
			$this->setNumRows($index);
			$result=$this->rows[$index-1];
		}
		return $result;
	}
	
	/**
	 * Create $numRows rows
	 * @param int $numRows
	 * @param int $numCols
	 * @return \Ajax\bootstrap\html\HtmlGridSystem
	 */
	public function setNumRows($numRows,$numCols=NULL){
		for($i=sizeof($this->rows);$i<$numRows;$i++){
			$this->addRow($numCols);
		}
		return $this;
	}
	
	/**
	 * @param int $row
	 * @param int $col
	 * @param boolean $force add the cell at $row,$col if true
	 * @return HtmlGridCol
	 */
	public function getCell($row,$col,$force=true){
		$row=$this->getRow($row,$force);
		if(isset($row)){
			$col=$row->getCol($col,$force);
		}
		return $col;
	}
	
	/**
	 * @param int $row
	 * @param int $col
	 * @return HtmlGridCol
	 */
	public function getCellAt($row,$col,$force=true){
		$row=$this->getRow($row,$force);
		if(isset($row)){
			$col=$row->getColAt($col,$force);
		}
		return $col;
	}
	
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		foreach ($this->rows as $row){
			$this->addContent($row);
		}
		return parent::compile($js,$view);
	}
	public function setContentForAll($content){
		foreach ($this->rows as $row){
			$row->setContentForAll($content);
		}
	}
}
