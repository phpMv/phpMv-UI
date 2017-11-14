<?php

namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;
use Ajax\common\html\BaseHtml;
use Ajax\semantic\html\collections\HtmlGrid;
use Ajax\semantic\html\elements\HtmlList;


class HtmlPopup extends HtmlSemDoubleElement {
	private $_container;
	public function __construct(BaseHtml $container,$identifier, $content="") {
		parent::__construct($identifier, "div");
		$this->_container=$container;
		$this->setClass("ui popup");
		$this->content=$content;
		$this->_params=array("on"=>"hover");
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::addList()
	 */
	public function addList($items=array(),$header=NULL){
		if(!$this->content instanceof HtmlGrid){
			$this->content=new HtmlGrid("Grid-".$this->identifier,0);
		}
		$grid=$this->content;

		$colCount=$grid->colCount();
		$colCount++;
		$grid->setColsCount($colCount);

		$list=new HtmlList("",$items);
		$list->asLink();
		if(isset($header)){
			$list->addHeader(4,$header);
		}
		$grid->getCell(0,$colCount-1)->setContent($list);
		$grid->setDivided()->setRelaxed(true);
		return $list;
	}

	/**
	 * A popup can have no maximum width and continue to flow to fit its content
	 */
	public function setFlowing(){
		return $this->addToProperty("class", "flowing");
	}

	/**
	 * A popup can provide more basic formatting
	 */
	public function setBasic(){
		return $this->addToProperty("class", "basic");
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::run()
	 */
	public function run(JsUtils $js){
		parent::run($js);
		$this->_params["popup"]="#".$this->identifier;
		$js->semantic()->popup("#".$this->_container->getIdentifier(),$this->_params);
	}

	public function setOn($event="click"){
		$this->_params["on"]=$event;
		return $this;
	}

	public function setInline($value=true){
		$this->_params["inline"]=$value;
		return $this;
	}

	public function setPosition($position){
		$this->_params["position"]=$position;
		return $this;
	}
}
