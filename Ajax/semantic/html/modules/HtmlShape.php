<?php
namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\content\HtmlShapeItem;
use Ajax\JsUtils;
use Ajax\semantic\html\base\HtmlSemDoubleElement;


class HtmlShape extends HtmlSemCollection{

	protected $_params=array();
	protected $_autoActive=true;

	public function __construct( $identifier, $sides){
		parent::__construct( $identifier, "div", "ui shape");
		$this->_template="<%tagName% id='%identifier%' %properties%><div class='sides'>%wrapContentBefore%%content%%wrapContentAfter%</div></%tagName%>";
		$this->addItems($sides);
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\html\HtmlCollection::createItem()
	 */
	protected function createItem($value){
		if(\is_object($value)===false){
			$value=new HtmlSemDoubleElement("","div","content",$value);
		}
		return new HtmlShapeItem("side-".$this->identifier."-".$this->count(), $value);
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\html\HtmlCollection::createCondition()
	 */
	protected function createCondition($value){
		return ($value instanceof HtmlShapeItem)===false;
	}

	/**
	 * @param int $index
	 * @return \Ajax\semantic\html\content\HtmlShapeItem
	 */
	public function getSide($index){
		return $this->getItem($index);
	}

	/**
	 * @param int $index
	 * @return mixed|NULL
	 */
	public function getSideContent($index){
		$item= $this->getItem($index);
		if(isset($item))
			return $item->getContent();
		return null;
	}

	public function jsDo($action){
		return "$('#".$this->identifier."').shape('".$action."');";
	}

	public function jsFlipleft(){
		return $this->jsDo("flip left");
	}

	public function setActiveSide($index){
		$side=$this->getSide($index);
		if(isset($side)){
			$side->setActive(true);
		}
		return $this;
	}

	public function asCube(){
		return $this->addToPropertyCtrl("class", "cube", ["cube"]);
	}

	public function asText(){
		return $this->addToPropertyCtrl("class", "text", ["text"]);
	}

	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if (isset($this->_bsComponent) === false)
			$this->_bsComponent=$js->semantic()->shape("#" . $this->identifier, $this->_params);
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		if($this->_autoActive)
			$this->setActiveSide(0);
		return parent::compile($js,$view);
	}
}