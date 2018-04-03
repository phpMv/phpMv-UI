<?php
namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemCollection;
use Ajax\semantic\html\content\HtmlShapeItem;
use Ajax\JsUtils;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\BaseHtml;


class HtmlShape extends HtmlSemCollection{

	protected $_params=array();
	protected $_autoActive=true;

	public function __construct( $identifier, $sides){
		parent::__construct( $identifier, "div", "ui shape");
		$this->_template='<%tagName% id="%identifier%" %properties%><div class="sides">%wrapContentBefore%%content%%wrapContentAfter%</div></%tagName%>';
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

	public function jsFlipLeft(){
		return $this->jsDo("flip left");
	}

	public function jsFlipRight(){
		return $this->jsDo("flip right");
	}

	public function jsFlipUp(){
		return $this->jsDo("flip up");
	}

	public function jsFlipDown(){
		return $this->jsDo("flip down");
	}

	public function jsFlipOver(){
		return $this->jsDo("flip over");
	}

	public function jsFlipBack(){
		return $this->jsDo("flip back");
	}

	private function doActionOn($element,$event,$what){
		if($element instanceof BaseHtml){
			return $element->on($event, $what,true,true);
		}
	}

	public function flipLeftOn($element,$event){
		return $this->doActionOn($element, $event, $this->jsFlipLeft());
	}

	public function flipRightOn($element,$event){
		return $this->doActionOn($element, $event, $this->jsFlipRight());
	}

	public function flipUpOn($element,$event){
		return $this->doActionOn($element, $event, $this->jsFlipUp());
	}

	public function flipDownOn($element,$event){
		return $this->doActionOn($element, $event, $this->jsFlipDown());
	}

	public function flipBackOn($element,$event){
		return $this->doActionOn($element, $event, $this->jsFlipBack());
	}

	public function flipOverOn($element,$event){
		return $this->doActionOn($element, $event, $this->jsFlipOver());
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

	public function setWidth($width="initial"){
		$this->_params["width"]=$width;
	}
	public function onChange($jsCode){
		return $this->_params["onChange"]="%function(){" . $jsCode . "}%";
	}

	public function beforeChange($jsCode){
		return $this->_params["beforeChange"]="%function(){" . $jsCode . "}%";
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
