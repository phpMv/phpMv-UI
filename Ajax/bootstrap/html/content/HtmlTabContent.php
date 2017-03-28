<?php

namespace Ajax\bootstrap\html\content;


use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
/**
 * Twitter Bootstrap HTML TabContent component
 * @author jc
 * @version 1.001
 */
class HtmlTabContent extends HtmlBsDoubleElement {

	public function __construct($identifier, $tagName="div") {
		parent::__construct($identifier, $tagName);
		$this->setProperty("class", "tab-content");
		$this->content=array (); // HtmlTabContentItem
	}

	public function addTabItem($identifier) {
		$tabItem=new HtmlTabContentItem($identifier);
		$this->content []=$tabItem;
	}

	public function getTabItem($index) {
		if ($index<sizeof($this->content))
			return $this->content [$index];
	}

	public function getTabItems() {
		return $this->content;
	}
}
