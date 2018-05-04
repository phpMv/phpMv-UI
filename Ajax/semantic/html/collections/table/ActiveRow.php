<?php

namespace Ajax\semantic\html\collections\table;

/**
 * A class for row selection in Table or DataTable
 * @author jc
 * @since 2.2.2
 */
class ActiveRow {
	/**
	 * @var HtmlTable
	 */
	private $table;
	private $class;
	private $event;
	private $multiple;
	/**
	 * @param HtmlTable $table
	 * @param string $class
	 * @param string $event
	 * @param boolean $multiple
	 */
	public function __construct($table,$class="active",$event="click",$multiple=false){
		$this->table=$table;
		$this->class=$class;
		$this->event=$event;
		$this->multiple=$multiple;
	}

	public function getClass() {
		return $this->class;
	}

	public function setClass($class) {
		$this->class=$class;
		return $this;
	}

	public function getEvent() {
		return $this->event;
	}

	public function setEvent($event) {
		$this->event=$event;
		return $this;
	}

	public function getMultiple() {
		return $this->multiple;
	}

	public function setMultiple($multiple) {
		$this->multiple=$multiple;
		return $this;
	}

	public function run(){
		$multiple="";
		if(!$this->multiple){
			$multiple="$(this).closest('tbody').children('tr').removeClass('".$this->class."');";
		}
		$this->table->onRow($this->event, $multiple."$(this).toggleClass('".$this->class."');".$this->table->jsTrigger("activeRowChange","[this]"),false,false);
	}

}
