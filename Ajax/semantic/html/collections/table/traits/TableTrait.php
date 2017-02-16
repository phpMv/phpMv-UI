<?php
namespace Ajax\semantic\html\collections\table\traits;

trait TableTrait{
	/**
	 * @return HtmlTable
	 */
	abstract protected function getTable();

	protected function addToPropertyTable($property,$value){
		return $this->getTable()->addToProperty($property, $value);
	}

	public function setCelled() {
		return $this->addToPropertyTable("class", "celled");
	}

	public function setBasic($very=false) {
		$table=$this->getTable();
		if ($very)
			$table->addToPropertyCtrl("class", "very", array ("very" ));
		return $table->addToPropertyCtrl("class", "basic", array ("basic" ));
	}

	public function setCompact($very=false) {
		$table=$this->getTable();
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
		$table=$this->getTable();
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
}