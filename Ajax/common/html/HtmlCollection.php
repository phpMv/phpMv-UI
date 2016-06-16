<?php

namespace Ajax\common\html;

use Ajax\common\html\HtmlDoubleElement;
use Ajax\service\JArray;
use Ajax\JsUtils;

/**
 * Base class for Html collections
 * @author jc
 * @version 1.001
 */
abstract class HtmlCollection extends HtmlDoubleElement {

	public function __construct($identifier,$tagName="div"){
		parent::__construct($identifier,$tagName);
		$this->content=array();
	}

	public function addItems($items){
		if(JArray::isAssociative($items)){
			foreach ($items as $k=>$v){
				$this->addItem([$k,$v]);
			}
		}else{
			foreach ($items as $item){
				$this->addItem($item);
			}
		}
		return $this;
	}

	public function setItems($items){
		$this->content=$items;
		return $this;
	}

	public function getItems(){
		return $this->content;
	}

	protected function getItemToAdd($item){
		$itemO=$item;
		if($this->createCondition($item)===true){
			$itemO=$this->createItem($item);
		}
		return $itemO;
	}

	/**
	 * adds and returns an item
	 * @param HtmlDoubleElement|string $item
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addItem($item){
		$itemO=$this->getItemToAdd($item);
		$this->addContent($itemO);
		return $itemO;
	}

	public function insertItem($item,$position=0){
		$itemO=$this->getItemToAdd($item);
		\array_splice( $this->content, $position, 0, array($itemO));
		return $itemO;
	}

	/**
	 * Return the item at index
	 * @param int|string $index the index or the item identifier
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function getItem($index) {
		if (is_int($index))
			return $this->content[$index];
		else {
			$elm=$this->getElementById($index, $this->content);
			return $elm;
		}
	}

	public function setItem($index, $value) {
		$this->content[$index]=$value;
		return $this;
	}

	public function removeItem($index){
		return array_splice($this->content, $index, 1);
	}

	public function count(){
		return \sizeof($this->content);
	}

	/* (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\base\BaseHtml::fromDatabaseObject()
	 */
	public function fromDatabaseObject($object, $function) {
		return $this->addItem($function($object));
	}

	public function apply($callBack){
		foreach ($this->content as $item){
			$callBack($item);
		}
		return $this;
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\bootstrap\html\HtmlSingleElement::fromArray()
	 */
	public function fromArray($array) {
		$this->addItems($array);
		return $this;
	}
	/**
	 * The item factory
	 * @param mixed $value
	 */
	protected abstract function createItem($value);

	protected function createCondition($value){
		return \is_object($value)===false;
	}

	protected function contentAs($tagName){
		foreach ($this->content as $item){
			$item->setTagName($tagName);
		}
		return $this;
	}
}