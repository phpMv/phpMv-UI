<?php

namespace Ajax\semantic\html\collections\menus;

use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\JsUtils;

use Ajax\semantic\html\elements\HtmlIcon;

class HtmlPaginationMenu extends HtmlMenu{
	private $_page;
	public function __construct( $identifier, $items=array() ){
		parent::__construct( $identifier,$items);
	}
	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\html\BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL,&$view=NULL){
		$max=\sizeof($this->content);
		$this->insertItem(new HtmlIcon("", "left chevron"))->setProperty("data-page", \max([1,$this->_page-1]))->addToProperty("class","_firstPage no-active");
		$this->addItem(new HtmlIcon("", "right chevron"))->setProperty("data-page", \min([$max,$this->_page+1]))->setProperty("data-max", $max)->addToProperty("class","_lastPage no-active");
		$this->asPagination();
		return parent::compile($js,$view);
	}

	public function setActiveItem($index) {
		$result=parent::setActiveItem($index);
		$this->_page=$index+1;
		return $result;
	}

	public function getPage() {
		return $this->_page;
	}

}
