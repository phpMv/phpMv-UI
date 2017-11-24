<?php

namespace Ajax\semantic\widgets\datatable;

use Ajax\common\Widget;
use Ajax\JsUtils;
use Ajax\semantic\html\collections\table\HtmlTable;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\semantic\html\collections\menus\HtmlPaginationMenu;
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\semantic\html\base\constants\Direction;
use Ajax\service\JArray;
use Ajax\semantic\widgets\base\InstanceViewer;
use Ajax\semantic\html\collections\table\traits\TableTrait;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\semantic\html\base\traits\BaseTrait;

/**
 * DataTable widget for displaying list of objects
 * @version 1.0
 * @author jc
 * @since 2.2
 *
 */
class DataTable extends Widget {
	use TableTrait,DataTableFieldAsTrait,HasCheckboxesTrait,BaseTrait;
	protected $_searchField;
	protected $_urls;
	protected $_pagination;
	protected $_compileParts;
	protected $_deleteBehavior;
	protected $_editBehavior;
	protected $_visibleHover=false;
	protected $_targetSelector;
	protected $_refreshSelector;
	protected $_emptyMessage;
	protected $_json;
	protected $_rowClass="";
	protected $_sortable;
	protected $_hiddenColumns;
	protected $_colWidths;


	public function __construct($identifier,$model,$modelInstance=NULL) {
		parent::__construct($identifier, $model,$modelInstance);
		$this->_init(new InstanceViewer($identifier), "table", new HtmlTable($identifier, 0,0), false);
		$this->_urls=[];
		$this->_emptyMessage=new HtmlMessage("","nothing to display");
		$this->_emptyMessage->setIcon("info circle");
	}

	public function run(JsUtils $js){
		if($this->_hasCheckboxes && isset($js)){
			$this->_runCheckboxes($js);
		}
		if($this->_visibleHover){
			$js->execOn("mouseover", "#".$this->identifier." tr", "$(event.target).closest('tr').find('.visibleover').css('visibility', 'visible');",["preventDefault"=>false,"stopPropagation"=>true]);
			$js->execOn("mouseout", "#".$this->identifier." tr", "$(event.target).closest('tr').find('.visibleover').css('visibility', 'hidden');",["preventDefault"=>false,"stopPropagation"=>true]);
		}
		if(\is_array($this->_deleteBehavior))
			$this->_generateBehavior("delete",$this->_deleteBehavior, $js);
		if(\is_array($this->_editBehavior))
			$this->_generateBehavior("edit",$this->_editBehavior,$js);
		return parent::run($js);
	}



	protected function _generateBehavior($op,$params,JsUtils $js){
		if(isset($this->_urls[$op])){
			$params=\array_merge($params,["attr"=>"data-ajax"]);
			$js->ajaxOnClick("#".$this->identifier." ._".$op, $this->_urls[$op],$this->getTargetSelector($op),$params);
		}
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\collections\table\TableTrait::getTable()
	 */
	protected function getTable() {
		return $this->content["table"];
	}


	public function compile(JsUtils $js=NULL,&$view=NULL){
		if(!$this->_generated){
			$this->_instanceViewer->setInstance($this->_model);
			$captions=$this->_instanceViewer->getCaptions();
			$table=$this->content["table"];
			if($this->_hasCheckboxes){
				$this->_generateMainCheckbox($captions);
			}
			$table->setRowCount(0, \sizeof($captions));
			$this->_generateHeader($table,$captions);

			if(isset($this->_compileParts))
				$table->setCompileParts($this->_compileParts);

			$this->_generateContent($table);

			$this->compileExtraElements($table, $captions,$js);

			$this->content=JArray::sortAssociative($this->content, [PositionInTable::BEFORETABLE,"table",PositionInTable::AFTERTABLE]);
			$this->_compileForm();
			$this->_applyStyleAttributes($table);
			$this->_generated=true;
		}
		return parent::compile($js,$view);
	}

	protected function compileExtraElements($table,$captions,JsUtils $js=NULL){
		if(isset($this->_searchField) && isset($js) && isset($this->_urls["refresh"])){
				$this->_searchField->postOn("change", $this->_urls["refresh"],"{'s':$(this).val()}","#".$this->identifier." tbody",["preventDefault"=>false,"jqueryDone"=>"replaceWith"]);
		}
		if($this->_hasCheckboxes && $table->hasPart("thead")){
			$table->getHeader()->getCell(0, 0)->addClass("no-sort");
		}

		if(isset($this->_toolbar)){
			$this->_setToolbarPosition($table, $captions);
		}
		if(isset($this->_pagination) && $this->_pagination->getVisible()){
			$this->_generatePagination($table,$js);
		}
	}

	protected function _applyStyleAttributes($table){
		if(isset($this->_hiddenColumns))
			$this->_hideColumns();
			if(isset($this->_colWidths)){
				foreach ($this->_colWidths as $colIndex=>$width){
					$table->setColWidth($colIndex,$width);
				}
			}
	}

	protected function _hideColumns(){
		foreach ($this->_hiddenColumns as $colIndex){
			$this->_self->hideColumn($colIndex);
		}
		return $this;
	}

	protected function _generateHeader(HtmlTable $table,$captions){
		$table->setHeaderValues($captions);
		if(isset($this->_sortable)){
			$table->setSortable($this->_sortable);
		}
	}



	protected function _generateContent($table){
		$objects=$this->_modelInstance;
		if(isset($this->_pagination)){
			$objects=$this->_pagination->getObjects($this->_modelInstance);
		}
			InstanceViewer::setIndex(0);
			$table->fromDatabaseObjects($objects, function($instance) use($table){
				return $this->_generateRow($instance, $table);
			});
		if($table->getRowCount()==0){
			$result=$table->addRow();
			$result->mergeRow();
			$result->setValues([$this->_emptyMessage]);
		}
	}

	protected function _generateRow($instance,&$table,$checkedClass=null){
		$this->_instanceViewer->setInstance($instance);
		InstanceViewer::$index++;
		$values= $this->_instanceViewer->getValues();
		$id=$this->_instanceViewer->getIdentifier();
		$dataAjax=$id;
		$id=$this->cleanIdentifier($id);
		if($this->_hasCheckboxes){
			$ck=new HtmlCheckbox("ck-".$this->identifier."-".$id,"");
			$ck->setOnChange("event.stopPropagation();");
			$field=$ck->getField();
			$field->setProperty("value",$dataAjax);
			$field->setProperty("name", "selection[]");
			if(isset($checkedClass))
				$field->setClass($checkedClass);
			\array_unshift($values, $ck);
		}
		$result=$table->newRow();
		$result->setIdentifier($this->identifier."-tr-".$id);
		$result->setProperty("data-ajax",$dataAjax);
		$result->setValues($values);
		$result->addToProperty("class",$this->_rowClass);
		return $result;
	}

	protected function _generatePagination($table,$js=NULL){
		if(isset($this->_toolbar)){
			if($this->_toolbarPosition==PositionInTable::FOOTER)
				$this->_toolbar->setFloated("left");
		}
		$footer=$table->getFooter();
		$footer->mergeCol();
		$menu=new HtmlPaginationMenu("pagination-".$this->identifier,$this->_pagination->getPagesNumbers());
		$menu->floatRight();
		$menu->setActiveItem($this->_pagination->getPage()-1);
		$footer->addValues($menu);
		$this->_associatePaginationBehavior($menu,$js);
	}

	protected function _associatePaginationBehavior(HtmlMenu $menu,JsUtils $js=NULL){
		if(isset($this->_urls["refresh"])){
			$menu->postOnClick($this->_urls["refresh"],"{'p':$(this).attr('data-page')}",$this->getRefreshSelector(),["preventDefault"=>false,"jqueryDone"=>"replaceWith","hasLoader"=>false]);
		}
	}

	protected function _getFieldName($index){
		$fieldName=parent::_getFieldName($index);
		if(\is_object($fieldName))
			$fieldName="field-".$index;
		return $fieldName."[]";
	}

	protected function _getFieldCaption($index){
		return null;
	}

	protected function _setToolbarPosition($table,$captions=NULL){
		switch ($this->_toolbarPosition){
			case PositionInTable::BEFORETABLE:
			case PositionInTable::AFTERTABLE:
				if(isset($this->_compileParts)===false){
					$this->content[$this->_toolbarPosition]=$this->_toolbar;
				}
				break;
			case PositionInTable::HEADER:
			case PositionInTable::FOOTER:
			case PositionInTable::BODY:
				$this->addToolbarRow($this->_toolbarPosition,$table, $captions);
				break;
		}
	}

	/**
	 * Associates a $callback function after the compilation of the field at $index position
	 * The $callback function can take the following arguments : $field=>the compiled field, $instance : the active instance of the object, $index: the field position
	 * @param int $index postion of the compiled field
	 * @param callable $callback function called after the field compilation
	 * @return DataTable
	 */
	public function afterCompile($index,$callback){
		$this->_instanceViewer->afterCompile($index,$callback);
		return $this;
	}

	private function addToolbarRow($part,$table,$captions){
		$hasPart=$table->hasPart($part);
		if($hasPart){
			$row=$table->getPart($part)->addRow(\sizeof($captions));
		}else{
			$row=$table->getPart($part)->getRow(0);
		}
		$row->mergeCol();
		$row->setValues([$this->_toolbar]);
	}

	/**
	 * {@inheritDoc}
	 * @see Widget::getHtmlComponent()
	 * @return HtmlTable
	 */
	public function getHtmlComponent(){
		return $this->content["table"];
	}

	public function getUrls() {
		return $this->_urls;
	}

	/**
	 * Sets the associative array of urls for refreshing, updating or deleting
	 * think of defining the update zone with the setTargetSelector method
	 * @param string|array $urls associative array with keys refresh: for refreshing with search field or pagination, edit : for updating a row, delete: for deleting a row
	 * @return DataTable
	 */
	public function setUrls($urls) {
		if(\is_array($urls)){
			$this->_urls["refresh"]=JArray::getValue($urls, "refresh",0);
			$this->_urls["edit"]=JArray::getValue($urls, "edit",1);
			$this->_urls["delete"]=JArray::getValue($urls, "delete",2);
		}else{
			$this->_urls=["refresh"=>$urls,"edit"=>$urls,"delete"=>$urls];
		}
		return $this;
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
		$this->_pagination=new Pagination($items_per_page,$pages_visibles,$page,$total_rowcount);
		return $this;
	}

	/**
	 * Auto Paginates the DataTable element with a Semantic HtmlPaginationMenu component
	 * @param number $page the active page number
	 * @param number $items_per_page The number of items per page
	 * @param number $pages_visibles The number of visible pages in the Pagination component
	 * @return DataTable
	 */
	public function autoPaginate($page=1,$items_per_page=10,$pages_visibles=4){
		$this->_pagination=new Pagination($items_per_page,$pages_visibles,$page);
		return $this;
	}



	/**
	 * @param array $compileParts
	 * @return DataTable
	 */
	public function refresh($compileParts=["tbody"]){
		$this->_compileParts=$compileParts;
		return $this;
	}


	/**
	 * Adds a search input in toolbar
	 * @param string $position
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addSearchInToolbar($position=Direction::RIGHT){
		return $this->addInToolbar($this->getSearchField())->setPosition($position);
	}

	public function getSearchField(){
		if(isset($this->_searchField)===false){
			$this->_searchField=new HtmlInput("search-".$this->identifier,"search","","Search...");
			$this->_searchField->addIcon("search",Direction::RIGHT);
		}
		return $this->_searchField;
	}

	/**
	 * The callback function called after the insertion of each row when fromDatabaseObjects is called
	 * callback function takes the parameters $row : the row inserted and $object: the instance of model used
	 * @param callable $callback
	 * @return DataTable
	 */
	public function onNewRow($callback) {
		$this->content["table"]->onNewRow($callback);
		return $this;
	}

	/**
	 * Returns a form corresponding to the Datatable
	 * @return \Ajax\semantic\html\collections\form\HtmlForm
	 */
	public function asForm(){
		return $this->getForm();
	}



	protected function getTargetSelector($op) {
		$result=$this->_targetSelector;
		if(!isset($result[$op]))
			$result="#".$this->identifier;
		return $result[$op];
	}

	/**
	 * Sets the response element selector for Edit and Delete request with ajax
	 * @param string|array $_targetSelector string or associative array ["edit"=>"edit_selector","delete"=>"delete_selector"]
	 * @return DataTable
	 */
	public function setTargetSelector($_targetSelector) {
		if(!\is_array($_targetSelector)){
			$_targetSelector=["edit"=>$_targetSelector,"delete"=>$_targetSelector];
		}
		$this->_targetSelector=$_targetSelector;
		return $this;
	}

	public function getRefreshSelector() {
		if(isset($this->_refreshSelector))
			return $this->_refreshSelector;
		return "#".$this->identifier." tbody";
	}

	/**
	 * @param string $_refreshSelector
	 * @return DataTable
	 */
	public function setRefreshSelector($_refreshSelector) {
		$this->_refreshSelector=$_refreshSelector;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\Widget::show()
	 */
	public function show($modelInstance){
		if(\is_array($modelInstance)){
			if(isset($modelInstance[0]) && \is_array(array_values($modelInstance)[0]))
				$modelInstance=\json_decode(\json_encode($modelInstance), FALSE);
		}
		$this->_modelInstance=$modelInstance;
	}

	public function getRowClass() {
		return $this->_rowClass;
	}

	/**
	 * Sets the default row class (tr class)
	 * @param string $_rowClass
	 * @return DataTable
	 */
	public function setRowClass($_rowClass) {
		$this->_rowClass=$_rowClass;
		return $this;
	}

	/**
	 * Sets the message displayed when there is no record
	 * @param mixed $_emptyMessage
	 * @return DataTable
	 */
	public function setEmptyMessage($_emptyMessage) {
		$this->_emptyMessage=$_emptyMessage;
		return $this;
	}

	public function setSortable($colIndex=NULL) {
		$this->_sortable=$colIndex;
		return $this;
	}

	public function setActiveRowSelector($class="active",$event="click",$multiple=false){
		$this->_self->setActiveRowSelector($class,$event,$multiple);
		return $this;
	}

	public function hideColumn($colIndex){
		if(!\is_array($this->_hiddenColumns))
			$this->_hiddenColumns=[];
		$this->_hiddenColumns[]=$colIndex;
		return $this;
	}

	public function setColWidth($colIndex,$width){
		$this->_colWidths[$colIndex]=$width;
		return $this;
	}
	public function setColWidths($_colWidths) {
		$this->_colWidths = $_colWidths;
		return $this;
	}

	public function setColAlignment($colIndex,$alignment){
		$this->content["table"]->setColAlignment($colIndex,$alignment);
		return $this;
	}
}
