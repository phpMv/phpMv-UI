<?php

namespace Ajax\common\html\html5;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\common\html\HtmlCollection;
/**
 * Html list (ul or ol)
 * @author jc
 * @version 1.001
 */
class HtmlList extends HtmlCollection {
	public function __construct($identifier, $items=array()) {
		parent::__construct($identifier, "ul");
		$this->addItems($items);
	}
	public function setOrdered($ordered=true){
		$this->tagName=($ordered===true)?"ol":"ul";
	}

	/**
	 * {@inheritDoc}
	 * @see HtmlCollection::createItem()
	 */
	protected function createItem($value) {
		$item=new HtmlDoubleElement("item-".$this->identifier."-".$this->count());
		$item->setTagName("li");
		$item->setContent($value);
		return $item;
	}

}
