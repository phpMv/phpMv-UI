<?php

namespace Ajax\common\html;

use Ajax\service\JArray;
use Ajax\JsUtils;
use Ajax\service\JReflection;
use Ajax\service\JString;

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

	protected function setItemIdentifier($item,$classname,$index){
		if($item instanceof BaseWidget){
			if(JString::isNull($item->getIdentifier())){
				$item->setIdentifier($classname."-".$this->identifier."-".$index);
			}
		}
	}

	/**
	 * adds and returns an item
	 * @param HtmlDoubleElement|string|array $item
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
		if (is_int($index)&& isset($this->content[$index]))
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
	abstract protected function createItem($value);

	protected function createCondition($value){
		return \is_object($value)===false;
	}

	protected function contentAs($tagName){
		foreach ($this->content as $item){
			$item->setTagName($tagName);
		}
		return $this;
	}

	public function setProperties($properties){
		$i=0;
		foreach ($properties as $k=>$v){
			$c=$this->content[$i++];
			if(isset($c))
				$c->setProperty($k,$v);
			else
				return $this;
		}
		return $this;
	}

	/**
	 * Sets the values of a property for each item in the collection
	 * @param string $property
	 * @param array|mixed $values
	 * @return $this
	 */
	public function setPropertyValues($property,$values){
		if(\is_array($values)===false){
			$values=\array_fill(0, $this->count(),$values);
		}
		foreach ($values as $i=>$value){
			if(isset($this->content[$i])){
				$this->content[$i]->setProperty($property,$value);
			}
			else{
				return $this;
			}
		}
		return $this;
	}
	
	/**
	 * Adds the values of a property for each item in the collection
	 * @param string $property
	 * @param array|mixed $values
	 * @return $this
	 */
	public function addPropertyValues($property,$values){
		if(\is_array($values)===false){
			$values=\array_fill(0, $this->count(),$values);
		}
		foreach ($values as $i=>$value){
			if(isset($this->content[$i])){
				$this->content[$i++]->addToProperty($property,$value);
			}
			else{
				return $this;
			}
		}
		return $this;
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$index=0;
		$classname=\strtolower(JReflection::shortClassName($this));
		foreach ($this->content as $item){
			$this->setItemIdentifier($item,$classname,$index++);
		}
		return parent::compile($js,$view);
	}

	public function getItemById($identifier){
		return $this->getElementById($identifier, $this->content);
	}

	/**
	 * @param array $hrefs
	 * @param string $target
	 * @return HtmlCollection
	 */
	public function asLinks($hrefs=[],$target=NUll) {
		foreach ( $this->content as $index=>$item ) {
			if($item instanceof HtmlDoubleElement){
				$href="";
				if(isset($hrefs[$index]))
					$href=$hrefs[$index];
				$item->asLink($href,$target);
			}
		}
		return $this;
	}
	
	/**
	 * Remove a portion of the items array and replace it with something else
	 * @param int $offset
	 * @param int $length  If length is omitted, removes everything from offset to the end of the array. If length is specified and is positive, then that many elements will be removed. If length is specified and is negative then the end of the removed portion will be that many elements from the end of the array. If length is specified and is zero, no elements will be removed. Tip: to remove everything from offset to the end of the array when replacement is also specified, use count($input) for length. 
	 * @return $this
	 */
	public function splice($offset,$length=null){
		$this->content=array_slice($this->content, $offset,$length);
		return $this;
	}
}
