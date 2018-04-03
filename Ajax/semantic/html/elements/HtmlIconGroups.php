<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\service\JArray;
use Ajax\JsUtils;

/**
 * Semantic Icons group component
 * @see http://phpmv-ui.kobject.net/index/direct/main/72
 * @see http://semantic-ui.com/elements/icon.html#/definition
 * @author jc
 * @version 1.001
 */
class HtmlIconGroups extends HtmlSemCollection {

	public function __construct($identifier, $icons=array(), $size="") {
		parent::__construct($identifier, "i", "icons");
		$this->addItems($icons);
		$this->setSize($size);
	}
	
	/**
	 * @return HtmlIcon
	 */
	public function getItem($index){
		return parent::getItem($index);
	}

	protected function createItem($value) {
		$icon=$value;
		if (\is_array($value)) {
			$icon=JArray::getValue($value, "icon", 0);
			$size=JArray::getValue($value, "size", 1);
		}
		$iconO=new HtmlIcon("icon-" . $this->identifier, $icon);
		if (isset($size)) {
			$iconO->setSize($size);
		}
		return $iconO;
	}

	protected function createCondition($value) {
		return ($value instanceof HtmlIcon) === false;
	}

	public function getIcon($index) {
		return $this->content[$index];
	}

	public function run(JsUtils $js){
		$result= parent::run($js);
		return $result->setItemSelector("i");
	}

	public function toCorner($index=1) {
		$this->getItem($index)->toCorner();
		return $this;
	}

	public static function corner($mainIcon,$cornerIcon,$size="huge"){
		$icons=new HtmlIconGroups("icons",[$mainIcon,$cornerIcon],$size);
		return $icons->toCorner(1);
	}
}
