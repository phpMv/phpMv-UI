<?php
namespace Ajax\semantic\html\content;

use Ajax\service\JArray;
use Ajax\semantic\html\elements\HtmlList;

class HtmlListItem extends HtmlAbsractItem {

	protected $image;

	public function __construct($identifier, $content = NULL) {
		parent::__construct($identifier, "item", $content);
	}

	protected function initContent($content) {
		if (\is_array($content)) {
			if (JArray::isAssociative($content) === false) {
				$icon = $content[0] ?? null;
				$title = $content[1] ?? null;
				$desc = $content[2] ?? null;
			} else {
				$icon = $content["icon"] ?? null;
				$image = $content["image"] ?? null;
				$title = $content["title"] ?? null;
				$header = $content["header"] ?? null;
				$desc = $content["description"] ?? null;
				$items = $content["items"] ?? null;
			}
			if (isset($icon)) {
				$this->setIcon($icon);
			}
			if (isset($image)) {
				$this->setImage($image);
			}
			if (isset($title)) {
				$this->setTitle($title, $desc);
			} elseif (isset($header)) {
				$this->setTitle($header, $desc, "header");
			}
			if (isset($items)) {
				$this->addList($items);
			}
		} else {
			$this->setContent($content);
		}
	}

	public function addList($items = array(), $ordered = false) {
		$list = new HtmlList("", $items);
		if ($ordered)
			$list->setOrdered();
		$list->setClass("list");
		$this->content["list"] = $list;
		return $list;
	}

	public function getList() {
		return $this->content["list"];
	}

	public function getItem($index) {
		return $this->getList()->getItem($index);
	}
}
