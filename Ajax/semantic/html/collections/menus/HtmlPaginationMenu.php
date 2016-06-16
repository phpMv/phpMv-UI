<?php

namespace Ajax\semantic\html\collections\menus;

use Ajax\semantic\html\collections\menus\HtmlMenu;
use Ajax\JsUtils;

use Ajax\semantic\html\elements\HtmlIcon;

class HtmlPaginationMenu extends HtmlMenu{

	public function __construct( $identifier, $items=array() ){
		parent::__construct( $identifier,$items);
	}
	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\html\BaseHtml::compile()
	 */
	public function compile(JsUtils $js=NULL,&$view=NULL){
		$this->insertItem(new HtmlIcon("", "left chevron"));
		$this->addItem(new HtmlIcon("", "right chevron"));
		$this->asPagination();
		return parent::compile($js,$view);
	}
}