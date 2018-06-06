<?php

namespace Ajax\semantic\widgets\datatable;

use Ajax\service\JReflection;
use Ajax\common\html\BaseHtml;
use Ajax\service\AjaxCall;
use Ajax\JsUtils;
use Ajax\service\JString;

/**
 * @author jc
 * a DataTable refreshed with JSON datas
 * @since 2.2.2
 */
class JsonDataTable extends DataTable {
	protected $_modelClass="_jsonArrayModel";
	protected $_rowModelCallback;

	public function __construct($identifier, $model, $modelInstance=NULL) {
		parent::__construct($identifier, $model, $modelInstance);
		$this->_rowClass="_json _element";
	}

	protected function _generateContent($table){
		$this->_addRowModel($table);
		parent::_generateContent($table);
	}

	protected function _addRowModel($table){
		$fields=$this->_instanceViewer->getSimpleProperties();
		$row=$this->_createRow($table, $this->_modelClass,$fields);
		$row->setProperty("style","display:none;");
		$table->getBody()->_addRow($row);
	}

	protected function _createRow($table,$rowClass,$fields){
		$object=JReflection::jsonObject($this->_model);
		if(isset($this->_rowModelCallback)){
			$callback=$this->_rowModelCallback;
			$callback($object);
		}
		$row=$this->_generateRow($object, $fields,$table,"_jsonArrayChecked");
		$row->setClass($rowClass." _element");
		return $row;
	}

	/**
	 * {@inheritDoc}
	 * @see DataTable::_associatePaginationBehavior()
	 */
	protected function _associatePaginationBehavior(JsUtils $js=NULL,$offset=null){
		$callback=null;
		$menu=$this->_pagination->getMenu();
		
		if(isset($js)){
			$id=$this->identifier;
			$callback=$js->getScript($offset).$this->getHtmlComponent()->getInnerScript();
			$callback.=$js->trigger("#".$id." [name='selection[]']","change",false)."$('#".$id." tbody .ui.checkbox').checkbox();".$js->execOn("change", "#".$id." [name='selection[]']", $this->_getCheckedChange($js));
			$callback.=$this->_generatePaginationScript($id);
			if(isset($this->_urls["refresh"])){
				if(isset($menu))
				$js->jsonArrayOn("click", "#".$menu->getIdentifier()." a","#".$this->_identifier." tr.".$this->_modelClass, $this->_urls["refresh"],"post",["params"=>"{'p':$(this).attr('data-page'),'_model':'".JString::doubleBackSlashes($this->_model)."'}","jsCallback"=>$callback]);
			}
		}

	}
	
	protected function _generatePaginationScript($id){
		return ";var page=parseInt($(self).attr('data-page')) || 1;var pages_visibles=$('#pagination-{$id} .item').length-2;
			var lastPage=$('#pagination-{$id} ._lastPage');
			var middle= Math.ceil((pages_visibles-1)/ 2);
			var first=Math.max(1,page-middle);var max=lastPage.attr('data-max');
			var last=Math.min(max,first+pages_visibles-1);
			if(last-pages_visibles+1>=0)
				first=Math.min(first,last-pages_visibles+1);
			var number=first;
			$('#pagination-{$id} .item.pageNum').each(function(){
				$(this).attr('data-page',number);
				$(this).html(number);
				number++;
			});
			$('#pagination-{$id} .item').removeClass('active');
			$('#pagination-{$id} [data-page='+page+']:not(.no-active)').addClass('active');
			$('#pagination-{$id} ._firstPage').attr('data-page',Math.max(1,page-1));
			lastPage.attr('data-page',Math.min(lastPage.attr('data-max'),page+1));
			$('#{$id}').trigger('pageChange');$('#{$id}').trigger('activeRowChange');$('#pagination-{$id}').show();";
	}
	protected function _compileSearchFieldBehavior(JsUtils $js=NULL){
		
	}
	protected function _associateSearchFieldBehavior(JsUtils $js=NULL,$offset=null){
		if(isset($this->_searchField) && isset($js) && isset($this->_urls["refresh"])){
			$id=$this->identifier;
			$callback=$js->getScript($offset).$this->getHtmlComponent()->getInnerScript();
			$callback.=$js->trigger("#".$id." [name='selection[]']","change",false)."$('#".$id." tbody .ui.checkbox').checkbox();".$js->execOn("change", "#".$id." [name='selection[]']", $this->_getCheckedChange($js));
			$callback.="$('#pagination-{$id}').hide();$('#".$this->identifier."').trigger('searchTerminate',[$(self).val()]);";
			$js->jsonArrayOn("change", "#".$this->_searchField->getDataField()->getIdentifier(),"#".$this->_identifier." tr.".$this->_modelClass, $this->_urls["refresh"],"post",["params"=>"{'s':$(self).val(),'_model':'".JString::doubleBackSlashes($this->_model)."'}","jsCallback"=>$callback]);
		}
	}
	
	

	/**
	 * Returns a new AjaxCall object, must be compiled using $jquery object
	 * @param string $url
	 * @param string $method
	 * @param string $params
	 * @param string $jsCallback
	 * @return AjaxCall
	 */
	public function jsJsonArray($url, $method="get", $params="{}", $jsCallback=NULL,$parameters=[]){
		$parameters=\array_merge($parameters,["modelSelector"=>"#".$this->_identifier." tr.".$this->_modelClass,"url"=>$url,"method"=>$method,"params"=>$params,"jsCallback"=>$jsCallback]);
		return new AjaxCall("jsonArray", $parameters);
	}

	public function jsClear(){
		return "$('#{$this->identifier} tbody').find('._json').remove();";
	}

	public function clearOn(BaseHtml $element,$event, $stopPropagation=false, $preventDefault=false){
		return $element->addEvent($event, $this->jsClear(),$stopPropagation,$preventDefault);
	}

	public function clearOnClick(BaseHtml $element,$stopPropagation=false, $preventDefault=false){
		return $this->clearOn($element, "click",$stopPropagation,$preventDefault);
	}

	public function jsonArrayOn(BaseHtml $element,$event,$url, $method="get", $params="{}", $jsCallback=NULL,$parameters=[]){
		return $element->_addEvent($event, $this->jsJsonArray($url,$method,$params,$jsCallback,$parameters));
	}

	public function jsonArrayOnClick(BaseHtml $element,$url, $method="get", $params="{}", $jsCallback=NULL,$parameters=[]){
		return $this->jsonArrayOn($element, "click", $url,$method,$params,$jsCallback,$parameters);
	}

	/**
	 * Paginates the DataTable element with a Semantic HtmlPaginationMenu component
	 * @param number $page the active page number
	 * @param number $total_rowcount the total number of items
	 * @param number $items_per_page The number of items per page
	 * @param number $pages_visibles The number of visible pages in the Pagination component
	 * @return DataTable
	 */
	public function paginate($page,$total_rowcount,$items_per_page=10,$pages_visibles=null){
		return parent::paginate($page, $total_rowcount,$items_per_page,$pages_visibles);
	}

	public function setRowModelCallback($_rowModelCallback) {
		$this->_rowModelCallback=$_rowModelCallback;
		return $this;
	}
}
