<?php

namespace Ajax\semantic\widgets\dataelement;

use Ajax\common\Widget;
use Ajax\semantic\widgets\datatable\PositionInTable;
use Ajax\JsUtils;
use Ajax\service\JArray;
use Ajax\semantic\html\collections\table\HtmlTable;
use Ajax\semantic\html\base\traits\BaseTrait;

/**
 * DataElement widget for displaying an instance of model
 * @version 1.0
 * @author jc
 * @since 2.2
 *
 */
class DataElement extends Widget {
	use BaseTrait;
	protected $_colWidths;

	public function __construct($identifier, $modelInstance=NULL) {
		parent::__construct($identifier, null,$modelInstance);
		$this->_init(new DeInstanceViewer($identifier), "table", new HtmlTable($identifier, 0,2), false);
		$this->content["table"]->setDefinition();
	}

	public function compile(JsUtils $js=NULL,&$view=NULL){
		if(!$this->_generated){
			$this->_instanceViewer->setInstance($this->_modelInstance);

			$table=$this->content["table"];
			$this->_generateContent($table);

			if(isset($this->_toolbar)){
				$this->_setToolbarPosition($table);
			}
			if(isset($this->_colWidths)){
				$this->_applyStyleAttributes($table);
			}
			$this->content=JArray::sortAssociative($this->content, [PositionInTable::BEFORETABLE,"table",PositionInTable::AFTERTABLE]);
			$this->_compileForm();
			$this->_generated=true;
		}
		return parent::compile($js,$view);
	}

	/**
	 * @param HtmlTable $table
	 */
	protected function _generateContent($table){
		$values= $this->_instanceViewer->getValues();
		$captions=$this->_instanceViewer->getCaptions();
		$count=$this->_instanceViewer->count();

		for($i=0;$i<$count;$i++){
			$table->addRow([$captions[$i],$values[$i]]);
		}
	}

	protected function _applyStyleAttributes(HtmlTable $table){
		$table->setColWidths($this->_colWidths);
	}
	protected function _getFieldName($index){
		return $this->_instanceViewer->getFieldName($index);
	}

	protected function _getFieldCaption($index){
		return null;
	}

	protected function _getFieldIdentifier($prefix,$name=""){
		return $this->identifier."-{$prefix}-".$name;
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\Widget::getHtmlComponent()
	 * @return HtmlTable
	 */
	public function getHtmlComponent() {
		return $this->content["table"];
	}

	/**
	 * {@inheritdoc}
	 * @see \Ajax\common\Widget::_setToolbarPosition()
	 */
	protected function _setToolbarPosition($table, $captions=NULL) {
		$this->content[$this->_toolbarPosition]=$this->_toolbar;
	}

	/**
	 * The callback function called after the insertion of each row when fromDatabaseObjects is called
	 * callback function takes the parameters $row : the row inserted and $object: the instance of model used
	 * @param callable $callback
	 * @return DataElement
	 */
	public function onNewRow($callback) {
		$this->content["table"]->onNewRow($callback);
		return $this;
	}

	public function asForm(){
		return $this->getForm();
	}

	public function setColCaptionWidth($width){
		$this->_colWidths[0]=$width;
		return $this;
	}

	public function setColValueWidth($width) {
		$this->_colWidths[1]=$width;
		return $this;
	}

	public function setColWidths($widths){
		$this->_colWidths=$widths;
		return $this;
	}
}
