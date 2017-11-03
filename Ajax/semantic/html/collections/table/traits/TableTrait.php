<?php
namespace Ajax\semantic\html\collections\table\traits;

use Ajax\semantic\html\collections\table\HtmlTable;

/**
 * @author jc
 * @property HtmlTable $_self
 */
trait TableTrait{

	abstract public function addEvent($event, $jsCode, $stopPropagation=false, $preventDefault=false);
	abstract public function getOn($event, $url, $responseElement="", $parameters=array());

	protected function addToPropertyTable($property,$value){
		return $this->_self->addToProperty($property, $value);
	}

	public function setCelled() {
		return $this->addToPropertyTable("class", "celled");
	}

	public function setBasic($very=false) {
		$table=$this->_self;
		if ($very)
			$table->addToPropertyCtrl("class", "very", array ("very" ));
		return $table->addToPropertyCtrl("class", "basic", array ("basic" ));
	}

	public function setCompact($very=false) {
		$table=$this->_self;
		if ($very)
			$table->addToPropertyCtrl("class", "very", array ("very" ));
		return $table->addToPropertyCtrl("class", "compact", array ("compact" ));
	}

	public function setCollapsing() {
		return $this->addToPropertyTable("class", "collapsing");
	}

	public function setDefinition() {
		return $this->addToPropertyTable("class", "definition");
	}

	public function setStructured() {
		return $this->addToPropertyTable("class", "structured");
	}

	public function setSortable($colIndex=NULL) {
		$table=$this->_self;
		if (isset($colIndex) && $table->hasPart("thead")) {
			$table->getHeader()->sort($colIndex);
		}
		return $table->addToProperty("class", "sortable");
	}

	public function setSingleLine() {
		return $this->addToPropertyTable("class", "single line");
	}

	public function setFixed() {
		return $this->addToPropertyTable("class", "fixed");
	}

	public function setSelectable() {
		return $this->addToPropertyTable("class", "selectable");
	}

	public function setStriped() {
		return $this->addToPropertyTable("class", "striped");
	}

	public function onRowClick($jsCode, $stopPropagation=false, $preventDefault=false){
		return $this->onRow("click", $jsCode,$stopPropagation,$preventDefault);
	}

	public function onRow($event,$jsCode, $stopPropagation=false, $preventDefault=false){
		return $this->_self->addEvent($event."{{tr}}",$jsCode,$stopPropagation,$preventDefault);
	}

	public function getOnRow($event, $url, $responseElement="", $parameters=array()){
		$parameters=\array_merge($parameters,["stopPropagation"=>false,"preventDefault"=>false]);
		return $this->_self->getOn($event."{{tbody tr}}", $url,$responseElement,$parameters);
	}
}
