<?php

namespace Ajax\semantic\html\elements;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\content\HtmlListItem;
use Ajax\semantic\html\collections\form\HtmlFormCheckbox;
use Ajax\JsUtils;
use Ajax\semantic\html\modules\checkbox\AbstractCheckbox;

class HtmlList extends HtmlSemCollection {
	protected $_hasCheckedList;

	public function __construct($identifier, $items=array()) {
		parent::__construct($identifier, "div", "ui list");
		$this->addItems($items);
		$this->_hasCheckedList=false;
	}

	protected function createItem($value) {
		$count=$this->count();
		$item=new HtmlListItem("item-" . $this->identifier . "-" . $count, $value);
		return $item;
	}

	public function addHeader($niveau, $content) {
		$header=new HtmlHeader("header-" . $this->identifier, $niveau, $content, "page");
		$this->wrap($header);
		return $header;
	}

	public function getItemPart($index,$partName="header"){
		return $this->getItem($index)->getPart($partName);
	}

	public function itemsAs($tagName) {
		return $this->contentAs($tagName);
	}

	public function asLink() {
		$this->addToPropertyCtrl("class", "link", array ("link" ));
		return $this->contentAs("a");
	}

	public function addList($items=array()) {
		$list=new HtmlList("", $items);
		$list->setClass("list");
		return $this->addItem($list);
	}

	protected function getItemToAdd($item){
		$itemO=parent::getItemToAdd($item);
		if($itemO instanceof AbstractCheckbox)
			$itemO->addClass("item");
		return $itemO;
	}

	public function setCelled() {
		return $this->addToProperty("class", "celled");
	}

	public function setBulleted() {
		return $this->addToProperty("class", "bulleted");
	}

	public function setOrdered() {
		return $this->addToProperty("class", "ordered");
	}

	public function run(JsUtils $js) {
		if ($this->_hasCheckedList === true) {
			$jsCode=include dirname(__FILE__) . '/../../components/jsTemplates/tplCheckedList.php';
			$jsCode=\str_replace("%identifier%", "#" . $this->identifier, $jsCode);
			$this->executeOnRun($jsCode);
		}
		return parent::run($js);
	}

	public function setRelaxed() {
		return $this->addToProperty("class", "relaxed");
	}

	public function setSelection() {
		return $this->addToProperty("class", "selection");
	}

	public function setDivided() {
		return $this->addToProperty("class", "divided");
	}

	public function setHorizontal() {
		return $this->addToProperty("class", "horizontal");
	}

	/**
	 * Adds a grouped checked box to the list
	 * @param array $items
	 * @param string|array|null $masterItem
	 * @param array|null $values
	 * @param string $notAllChecked
	 * @return HtmlList
	 */
	public function addCheckedList($items=array(), $masterItem=NULL, $values=array(),$notAllChecked=false,$name=null) {
		$count=$this->count();
		$identifier=$this->identifier . "-" . $count;
		if (isset($masterItem)) {
			if(\is_array($masterItem)){
				$masterO=new HtmlFormCheckbox("master-" . $identifier, @$masterItem[0],@$masterItem[1]);
				if(isset($name))
					$masterO->setName($name);
				if(isset($masterItem[1])){
					if(\array_search($masterItem[1], $values)!==false){
						$masterO->getDataField()->setProperty("checked", "");
					}
				}
			}else{
				$masterO=new HtmlFormCheckbox("master-" . $identifier, $masterItem);
			}
			if($notAllChecked){
				$masterO->getDataField()->addClass("_notAllChecked");
			}
			$masterO->getHtmlCk()->addToProperty("class", "master");
			$masterO->setClass("item");
			$this->addItem($masterO);
		}
		$fields=array ();
		$i=0;
		foreach ( $items as $val => $caption ) {
			$itemO=new HtmlFormCheckbox($identifier . "-" . $i++, $caption, $val, "child");
			if (\array_search($val, $values) !== false) {
				$itemO->getDataField()->setProperty("checked", "");
			}
			if(isset($name))
				$itemO->setName($name);
			$itemO->setClass("item");
			$fields[]=$itemO;
		}
		if (isset($masterO) === true) {
			$list=new HtmlList("", $fields);
			$list->setClass("list");
			$masterO->addContent($list);
		} else {
			$this->addList($fields);
		}
		$this->_hasCheckedList=true;
		return $this;
	}

	public function setIcons($icons){
		if(!\is_array($icons)){
			$icons=\array_fill(0, \sizeof($this->content), $icons);
		}
		$max=\min(\sizeof($icons),\sizeof($this->content));
		for($i=0;$i<$max;$i++){
			$this->content[$i]->addIcon($icons[$i]);
		}
		return $this;
	}
}
