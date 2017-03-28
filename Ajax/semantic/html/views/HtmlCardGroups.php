<?php

namespace Ajax\semantic\html\views;


use Ajax\semantic\html\content\view\HtmlViewGroups;

/**
 * Semantic html Cards group
 * @author jc
 */
class HtmlCardGroups extends HtmlViewGroups {

	public function __construct($identifier, $cards=array()) {
		parent::__construct($identifier, "ui cards",$cards);
	}

	protected function createElement(){
		return new HtmlCard("card-" . $this->count());
	}

	public function newItem($identifier) {
		return new HtmlCard($identifier);
	}

	public function getCard($index) {
		return $this->getItem($index);
	}

	public function getCardContent($cardIndex, $contentIndex) {
		return $this->getItemContent($cardIndex, $contentIndex);
	}

}
