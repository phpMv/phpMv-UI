<?php

namespace Ajax\semantic\html\collections\menus;

use Ajax\semantic\html\elements\HtmlIcon;
use Ajax\semantic\html\elements\html5\HtmlLink;

/**
 * Semantic Menu component with only labeled icons
 * @see http://semantic-ui.com/collections/menu.html
 * @author jc
 * @version 1.001
 */
class HtmlLabeledIconMenu extends HtmlMenu{


	/**
	 * @param string $identifier
	 * @param array $items icons
	 */
	public function __construct( $identifier, $items=array()){
		parent::__construct( $identifier, $items);
		$this->addToProperty("class", "labeled icon");
	}

	/**
	 * {@inheritDoc}
	 * @see HtmlMenu::createItem()
	 */
	protected function createItem($value) {
		$text="";
		$v=$value;
		if(\is_array($value)){
			$v=@$value[0];
			$text=@$value[1];
		}
		$count=\sizeof($this->content);
		$value=new HtmlIcon("icon-".$count, $v);
		$value->wrap("",$text);
		$itemO=new HtmlLink("item-".$count,"",$value);
		return $itemO->setClass("item");
	}
}
