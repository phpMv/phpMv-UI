<?php

namespace Ajax\semantic\html\collections\menus;

use Ajax\semantic\html\content\HtmlAccordionMenuItem;
use Ajax\JsUtils;
use Ajax\common\html\HtmlCollection;

class HtmlAccordionMenu extends HtmlMenu{
	protected $params=array();

	public function __construct( $identifier, $items=array() ){
		parent::__construct( $identifier, $items);
		$this->addToProperty("class", "accordion");
		$this->setVertical();
	}

	/**
	 * {@inheritDoc}
	 * @see HtmlCollection::createItem()
	 */
	protected function createItem($value) {
		$title=$value;
		$content="";
		if(\is_array($value)){
			$title=@$value[0];$content=@$value[1];
		}
		$itemO=new HtmlAccordionMenuItem("item-".$this->identifier."-".$this->count(), $title, $content);
		return $itemO->setClass("item");
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if(isset($this->_bsComponent)===false)
			$this->_bsComponent=$js->semantic()->accordion("#".$this->identifier,$this->params);
			$this->addEventsOnRun($js);
			return $this->_bsComponent;
	}

	public function setExclusive($value){
		$this->params["exclusive"]=$value;
	}
}
