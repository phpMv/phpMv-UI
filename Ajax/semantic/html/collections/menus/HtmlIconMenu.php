<?php

namespace Ajax\semantic\html\collections\menus;

use Ajax\bootstrap\html\HtmlLink;
use Ajax\semantic\html\elements\HtmlIcon;

/**
 * Semantic Menu component with only icons
 * @see http://semantic-ui.com/collections/menu.html
 * @author jc
 * @version 1.001
 */
class HtmlIconMenu extends HtmlMenu{


	/**
	 * @param string $identifier
	 * @param array $items icons
	 */
	public function __construct( $identifier, $items=array()){
		parent::__construct( $identifier, $items);
		$this->addToProperty("class", "icon");
	}


	/**
	 * {@inheritDoc}
	 * @see HtmlMenu::createItem()
	 */
	protected function createItem($value) {
		$count=\sizeof($this->content);
		$value=new HtmlIcon("icon-".$count, $value);
		$itemO=new HtmlLink("item-".$count,"",$value);
		return $itemO->setClass("item");
	}
}
