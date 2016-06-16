<?php

namespace Ajax\semantic\html\views;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\service\JArray;
use Ajax\semantic\html\base\constants\Wide;

class HtmlCardGroups extends HtmlSemCollection {

	public function __construct($identifier, $cards=array()) {
		parent::__construct($identifier, "div", "ui cards");
		$this->addItems($cards);
	}

	protected function createItem($value) {
		$result=new HtmlCard("card-" . $this->count());
		if (\is_array($value)) {
			$header=JArray::getValue($value, "header", 0);
			$metas=JArray::getValue($value, "metas", 1);
			$description=JArray::getValue($value, "description", 2);
			$image=JArray::getValue($value, "image", 3);
			$extra=JArray::getValue($value, "extra", 4);
			if (isset($image)) {
				$result->addImage($image);
			}
			$result->addCardHeaderContent($header, $metas, $description);
			if (isset($extra)) {
				$result->addExtraContent($extra);
			}
		} else
			$result->addCardContent($value);
		return $result;
	}

	/**
	 * Defines the cards width (alias for setWidth)
	 * @param int $wide
	 */
	public function setWide($wide) {
		$wide=Wide::getConstants()["W" . $wide];
		return $this->addToPropertyCtrl("class", $wide, Wide::getConstants());
	}

	public function newCard($identifier) {
		return new HtmlCard($identifier);
	}

	public function getCard($index) {
		return $this->getItem($index);
	}

	public function getCardContent($cardIndex, $contentIndex) {
		$card=$this->getItem($cardIndex);
		if (isset($card)) {
			return $card->getCardContent($contentIndex);
		}
	}

	public function fromDatabaseObject($object, $function) {
		return $this->addItem($function($object));
	}
}