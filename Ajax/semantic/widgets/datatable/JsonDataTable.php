<?php

namespace Ajax\semantic\widgets\datatable;

use Ajax\semantic\widgets\datatable\DataTable;
use Ajax\service\JReflection;
use Ajax\common\html\BaseHtml;
use Ajax\service\AjaxCall;
use Ajax\JsUtils;

class JsonDataTable extends DataTable {
	protected $_modelClass="_jsonArrayModel";

	public function __construct($identifier, $model, $modelInstance=NULL) {
		parent::__construct($identifier, $model, $modelInstance);
	}

	protected function _generateContent($table){
		$this->_addRowModel($table);
		$this->_rowClass="_json";
		parent::_generateContent($table);
	}

	protected function _addRowModel($table){
		$object=JReflection::jsonObject($this->_model);
		$row=$this->_generateRow($object, $table);
		$row->setClass($this->_modelClass);
		$row->addToProperty("style","display:none;");
		$table->getBody()->_addRow($row);
	}

	/**
	 * {@inheritDoc}
	 * @see DataTable::_associatePaginationBehavior()
	 */
	protected function _associatePaginationBehavior($menu,$js=NULL){
		$callback=null;
		if(isset($js)){
			//$this->run($js);
		}
		if(isset($this->_urls["refresh"]))
			$this->jsonArrayOnClick($menu, $this->_urls["refresh"],"post","{'p':$(this).attr('data-page')}",$callback);
	}

	/**
	 * Returns a new AjaxCall object, must be compiled using $jquery object
	 * @param string $url
	 * @param string $method
	 * @param string $params
	 * @param callable $jsCallback
	 * @return AjaxCall
	 */
	public function jsJsonArray($url, $method="get", $params="{}", $jsCallback=NULL,$parameters=[]){
		$parameters=\array_merge($parameters,["modelSelector"=>"#".$this->_identifier." tr.".$this->_modelClass,"url"=>$url,"method"=>$method,"params"=>$params,"callback"=>$jsCallback]);
		return new AjaxCall("jsonArray", $parameters);
	}

	public function jsonArrayOn(BaseHtml $element,$event,$url, $method="get", $params="{}", $jsCallback=NULL,$parameters=[]){
		return $element->_addEvent($event, $this->jsJsonArray($url,$method,$params,$jsCallback,$parameters));
	}

	public function jsonArrayOnClick(BaseHtml $element,$url, $method="get", $params="{}", $jsCallback=NULL,$parameters=[]){
		return $this->jsonArrayOn($element, "click", $url,$method,$params,$jsCallback,$parameters);
	}

}