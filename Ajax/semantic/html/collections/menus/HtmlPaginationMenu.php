<?php

namespace Ajax\semantic\html\collections\menus;

use Ajax\JsUtils;

use Ajax\semantic\html\elements\HtmlIcon;

class HtmlPaginationMenu extends HtmlMenu{
	private $_page;
	private $_pages;
	private $_max;
	public function __construct( $identifier, $items=array() ){
		parent::__construct( $identifier,$items);
		$this->_pages=$items;
	}
	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\html\BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL,&$view=NULL){
		$max=$this->_max;
		if(!$this->_compiled){
			foreach ($this->content as $item){
				$item->addClass("pageNum");
			}
			$this->insertItem(new HtmlIcon("", "left chevron"))->setProperty("data-page", \max([1,$this->_page-1]))->addToProperty("class","_firstPage no-active");
			$this->addItem(new HtmlIcon("", "right chevron"))->setProperty("data-page", \min([$max,$this->_page+1]))->setProperty("data-max", $max)->addToProperty("class","_lastPage no-active");
			$this->asPagination();
		}
		return parent::compile($js,$view);
	}

	public function setActivePage($page){
		$index=$page-$this->_pages[0];
		$this->setActiveItem($index);
		$this->_page=$page;
		return $this;
	}

	public function getPage() {
		return $this->_page;
	}
	/**
	 * @param mixed $_max
	 */
	public function setMax($_max) {
		$this->_max = $_max;
	}


}
