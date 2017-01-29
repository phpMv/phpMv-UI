<?php
namespace Ajax\semantic\widgets\datatable;

class Pagination {
	private $items_per_page;
	private $page;
	private $visible;
	private $page_count;
	private $pages_visibles;

	public function __construct($items_per_page=10,$pages_visibles=4,$page=1){
		$this->items_per_page=$items_per_page;
		$this->page=$page;
		$this->pages_visibles=$pages_visibles;
		$this->visible=true;
	}

	public function getObjects($objects){
		$offset = ($this->page - 1) * $this->items_per_page;
		$os=$objects;
		if(!\is_array($os)){
			$os=[];
			foreach ($objects as $o){
				$os[]=$o;
			}
		}
		$this->page_count = 0;
		$row_count=\sizeof($os);
		if (0 === $row_count) {
			$this->visible=false;
		} else {
			$this->visible=true;
			$this->page_count = (int)ceil($row_count / $this->items_per_page);
			if($this->page > $this->page_count+1) {
				$this->page = 1;
			}
		}
		return array_slice($os, $offset,$this->items_per_page);
	}

	public function getItemsPerPage() {
		return $this->items_per_page;
	}

	public function setItemsPerPage($items_per_page) {
		$this->items_per_page=$items_per_page;
		return $this;
	}

	public function getPage() {
		return $this->page;
	}

	public function setPage($page) {
		$this->page=$page;
		return $this;
	}

	public function getVisible() {
		return $this->visible;
	}

	public function setVisible($visible) {
		$this->visible=$visible;
		return $this;
	}

	public function getPageCount() {
		return $this->page_count;
	}

	public function getPagesNumbers(){
		$middle= (int)ceil(($this->pages_visibles-1)/ 2);
		$first=$this->page-$middle;
		if($first<1){
			$first=1;
		}
		$last=$first+$this->pages_visibles-1;
		if($last>$this->page_count){
			$last=$this->page_count;
		}
		return \range($first, $last);
	}

}