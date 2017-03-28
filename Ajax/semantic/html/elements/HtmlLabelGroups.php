<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\service\JArray;
use Ajax\semantic\html\base\constants\Size;
use Ajax\semantic\html\base\constants\Color;

/**
 * Semantic Label groups component
 * @see http://semantic-ui.com/elements/label.html#/definition
 * @author jc
 * @version 1.001
 */
class HtmlLabelGroups extends HtmlSemCollection {

	public function __construct($identifier,$labels=array(), $attributes=array()) {
		parent::__construct($identifier, "div", "ui labels");
		$this->_states=\array_merge(Size::getConstants(),Color::getConstants(),["tag","circular"]);
		$this->addItems($labels);
		$this->setStates($attributes);
	}

	protected function createItem($value) {
		$caption=$value;
		$icon=NULL;
		$tagName="div";
		if (\is_array($value)) {
			$caption=JArray::getValue($value, "caption", 0);
			$icon=JArray::getValue($value, "icon", 1);
			$tagName=JArray::getValue($value, "tagName", 2);
		}
		$labelO=new HtmlLabel("label-" . $this->identifier, $caption,$icon,$tagName);
		return $labelO;
	}

	protected function createCondition($value) {
		return ($value instanceof HtmlLabel) === false;
	}

}
