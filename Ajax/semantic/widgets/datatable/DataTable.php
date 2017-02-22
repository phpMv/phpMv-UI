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

/**
 * DataTable widget for displaying list of objects
 * @version 1.0
 * @author jc
 * @since 2.2
 *
 */
class DataTable extends Widget {
	use TableTrait,DataTableFieldAsTrait,HasCheckboxesTrait;
	protected $_searchField;
	protected $_urls;
	protected $_pagination;
	protected $_compileParts;
	protected $_deleteBehavior;
	protected $_editBehavior;
	protected $_visibleHover=false;
	protected $_targetSelector;


	public function __construct($identifier,$model,$modelInstance=NULL) {
		parent::__construct($identifier, $model,$modelInstance);
		$this->_init(new InstanceViewer($identifier), "table", new HtmlTable($identifier, 0,0), false);
		$this->_urls=[];
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
			$js->getOnClick("#".$this->identifier." ._".$op, $this->_urls[$op],$this->getTargetSelector(),$params);
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
			$table->setHeaderValues($captions);
			if(isset($this->_compileParts))
				$table->setCompileParts($this->_compileParts);

			if(isset($this->_searchField) && isset($js)){
				if(isset($this->_urls["refresh"]))
					$this->_searchField->postOn("change", $this->_urls["refresh"],"{'s':$(this).val()}","#".$this->identifier." tbody",["preventDefault"=>false,"jqueryDone"=>"replaceWith"]);
			}

			$this->_generateContent($table);

			if($this->_hasCheckboxes && $table->hasPart("thead")){
					$table->getHeader()->getCell(0, 0)->addClass("no-sort");
			}

			if(isset($this->_pagination) && $this->_pagination->getVisible()){
				$this->_generatePagination($table);
			}
			if(isset($this->_toolbar)){
				$this->_setToolbarPosition($table, $captions);
			}
			$this->content=JArray::sortAssociative($this->content, [PositionInTable::BEFORETABLE,"table",PositionInTable::AFTERTABLE]);
			$this->_compileForm();
			$this->_generated=true;
		}
		return parent::compile($js,$view);
	}



	protected function _generateContent($table){
		$objects=$this->_modelInstance;
		if(isset($this->_pagination)){
			$objects=$this->_pagination->getObjects($this->_modelInstance);
		}
		InstanceViewer::setIndex(0);
		$table->fromDatabaseObjects($objects, function($instance) use($table){
			$this->_instanceViewer->setInstance($instance);
			InstanceViewer::$index++;
			$values= $this->_instanceViewer->getValues();
			if($this->_hasCheckboxes){
				$ck=new HtmlCheckbox("ck-".$this->identifier,"");
				$field=$ck->getField();
				$field->setProperty("value",$this->_instanceViewer->getIdentifier());
				$field->setProperty("name", "selection[]");
				\array_unshift($values, $ck);
			}
			$result=$table->newRow();
			$result->setIdentifier($this->identifier."-tr-".$this->_instanceViewer->getIdentifier());
			$result->setValues($values);
			return $result;
		});
	}

	private function _generatePagination($table){
		$footer=$table->getFooter();
		$footer->mergeCol();
		$menu=new HtmlPaginationMenu("pagination-".$this->identifier,$this->_pagination->getPagesNumbers());
		$menu->floatRight();
		$menu->setActiveItem($this->_pagination->getPage()-1);
		$footer->setValues($menu);
		if(isset($this->_urls["refresh"]))
			$menu->postOnClick($this->_urls["refresh"],"{'p':$(this).attr('data-page')}","#".$this->identifier." tbody",["preventDefault"=>false,"jqueryDone"=>"replaceWith"]);
	}

	protected function _getFieldName($index){
		return parent::_getFieldName($index)."[]";
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
	 * @return \Ajax\semantic\widgets\datatable\DataTable
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

	public function getHtmlComponent(){
		return $this->content["table"];
	}

	public function getUrls() {
		return $this->_urls;
	}

	/**
	 * Sets the associative array of urls for refreshing, updating or deleting
	 * @param string|array $urls associative array with keys refresh: for refreshing with search field or pagination, edit : for updating a row, delete: for deleting a row
	 * @return \Ajax\semantic\widgets\datatable\DataTable
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

	public function paginate($items_per_page=10,$page=1){
		$this->_pagination=new Pagination($items_per_page,4,$page);
	}



	public function refresh($compileParts=["tbody"]){
		$this->_compileParts=$compileParts;
		return $this;
	}


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

	public function asForm(){
		return $this->getForm();
	}



	protected function getTargetSelector() {
		$result=$this->_targetSelector;
		if(!isset($result))
			$result="#".$this->identifier;
		return $result;
	}

	/**
	 * Sets the response element selector for Edit and Delete request with ajax
	 * @param string $_targetSelector
	 * @return \Ajax\semantic\widgets\datatable\DataTable
	 */
	public function setTargetSelector($_targetSelector) {
		$this->_targetSelector=$_targetSelector;
		return $this;
	}
}