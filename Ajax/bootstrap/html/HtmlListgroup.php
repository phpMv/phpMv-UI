<?php

namespace Ajax\bootstrap\html;

use Ajax\bootstrap\html\base\HtmlBsDoubleElement;
use Ajax\bootstrap\html\content\HtmlListgroupItem;

/**
 * Composant Twitter Bootstrap Listgroup
 * @see http://getbootstrap.com/components/#list-group
 * @author jc
 * @version 1.001
 */
class HtmlListgroup extends HtmlBsDoubleElement {

	public function __construct($identifier, $tagName="ul") {
		parent::__construct($identifier, $tagName);
		$this->content=array ();
		$this->_template='<%tagName% %properties%>%content%</%tagName%>';
		$this->setProperty("class", "list-group");
	}

	public function addItem($text="") {
		if (is_object($text)) {
			$element=$text;
		} else {
			switch($this->tagName) {
				case "ul":
					$element=new HtmlBsDoubleElement("list-gi-".$this->identifier);
					$element->setTagName("li");
					break;
				default:
					$element=new HtmlLink("list-gi-".$this->identifier);
					break;
			}
			$element->setContent($text);
		}

		$item=new HtmlListgroupItem($element);
		if (\is_array($text)) {
			$item->setHeadingAndContent($text);
		}
		$this->content []=$item;
		return $item;
	}

	public function addItems($items) {
		foreach ( $items as $item ) {
			if (is_string($item)) {
				$this->addItem($item);
			} else
				$this->content []=$item;
		}
	}

	public function getItem($index) {
		if ($index<sizeof($this->content))
			return $this->content [$index];
	}

	/* (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		$this->addItem($function($object));
	}
}
