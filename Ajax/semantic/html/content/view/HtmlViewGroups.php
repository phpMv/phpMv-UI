<?php

namespace Ajax\semantic\html\content\view;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\service\JArray;
use Ajax\semantic\html\base\constants\Wide;
use Ajax\JsUtils;

abstract class HtmlViewGroups extends HtmlSemCollection {

	public function __construct($identifier, $uiClass,$items=array()) {
		parent::__construct($identifier, "div", $uiClass);
		$this->addItems($items);
	}

	abstract protected function createElement();

	protected function createItem($value) {
		$result=$this->createElement();
		if (\is_array($value)) {
			$header=JArray::getValue($value, "header", 0);
			$metas=JArray::getValue($value, "metas", 1);
			$description=JArray::getValue($value, "description", 2);
			$image=JArray::getValue($value, "image", 3);
			$extra=JArray::getValue($value, "extra", 4);
			if (isset($image)) {
				$result->addImage($image);
			}
			$result->addItemHeaderContent($header, $metas, $description);
			if (isset($extra)) {
				$result->addExtraContent($extra);
			}
		} else
			$result->addItemContent($value);
		return $result;
	}

	/**
	 * Defines the ites width (alias for setWidth)
	 * @param int $wide
	 */
	public function setWide($wide) {
		$wide=Wide::getConstants()["W" . $wide];
		return $this->addToPropertyCtrl("class", $wide, Wide::getConstants());
	}

	abstract public function newItem($identifier);

	/**
	 * @return HtmlViewGroups
	 */
	public function getItem($index){
		return parent::getItem($index);
	}

	public function getItemContent($itemIndex, $contentIndex) {
		$item=$this->getItem($itemIndex);
		if (isset($item)) {
			return $item->getItemContent($contentIndex);
		}
	}

	public function fromDatabaseObject($object, $function) {
		return $this->addItem($function($object));
	}

	public function run(JsUtils $js){
		$result=parent::run($js);
		return $result->setItemSelector(".item");
	}
}
