<?php
namespace Ajax\semantic\widgets\datatable;

use Ajax\semantic\html\collections\menus\HtmlPaginationMenu;

class Pagination {
	private $items_per_page;
	private $page;
	private $visible;
	private $page_count;
	private $pages_visibles;
	private $row_count;
	private $menu;

	public function __construct($items_per_page=10,$pages_visibles=null,$page=1,$row_count=null){
		$this->items_per_page=$items_per_page;
		$this->row_count=$row_count;
		$this->page=$page;
		$this->setPagesVisibles($pages_visibles);
		$this->visible=true;
	}

	public function getObjects($objects){
		$auto=(!isset($this->row_count));
		$os=$objects;
		if(!\is_array($os)){
			$os=[];
			foreach ($objects as $o){
				$os[]=$o;
			}
		}
		$this->page_count = 0;
		$row_count=($auto)?\sizeof($os):$this->row_count;
		if (0 === $row_count) {
			$this->visible=false;
		} else {

			$this->page_count = (int)ceil($row_count / $this->items_per_page);
			$this->visible=$this->page_count>1;
			if($this->page > $this->page_count+1) {
				$this->page = 1;
			}
		}
		if($auto){
			$offset = ($this->page - 1) * $this->items_per_page;
			return array_slice($os, $offset,$this->items_per_page);
		}
		return $os;
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

	public function setPagesVisibles($pages_visibles) {
		if(!isset($pages_visibles))
			$pages_visibles=(int)ceil($this->row_count / $this->items_per_page)+1;
		$this->pages_visibles=$pages_visibles;
		return $this;
	}
	
	public function generateMenu($identifier){
		$menu=new HtmlPaginationMenu("pagination-".$identifier,$this->getPagesNumbers());
		$menu->setMax($this->page_count);
		$menu->floatRight();
		$menu->setActivePage($this->getPage());
		return $this->menu=$menu;
	}
	
	/**
	 * @return mixed
	 */
	public function getMenu() {
		return $this->menu;
	}
	
	public static function getPageOfRow($rownum,$itemsPerPage=10){
		$pageNum=0;$activeRow=0;
		while($activeRow<$rownum){
			$activeRow+=$itemsPerPage;
			$pageNum++;
		}
		return $pageNum;
	}
}
