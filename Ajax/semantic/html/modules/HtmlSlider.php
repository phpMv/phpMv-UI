<?php
namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;

/**
 * Ajax\semantic\html\modules$HtmlSlider
 * This class is part of phpMv-ui
 * @author jcheron <myaddressmail@gmail.com>
 * @version 1.0.0
 * @since 2.3.0
 * @see https://fomantic-ui.com/modules/slider.html
 *
 */
class HtmlSlider extends HtmlSemDoubleElement {
	
	protected $_paramParts=array();
	public function __construct($identifier, $content='') {
		parent::__construct($identifier, 'div','ui slider');
	}
	
	public function setLabeled(){
		return $this->addClass('labeled');
	}
	
	public function setTicked(){
		if(!$this->propertyContains('class', 'labeled')){
			$this->addClass('labeled');
		}
		return $this->addClass('ticked');
	}
	
	public function setLabels($labels){
		$this->_params['interpretLabel']=$labels;
		return $this;
	}
	
	/**
	 * $values is an associative array with keys (min,max,start,end,step,smooth)
	 * @param array $values
	 */
	public function asRange($values=NULL){
		$this->addClass('range');
		if(\is_array($values)){
			$this->_params=\array_merge($this->_params,$values);
		}
		return $this;
	}
	
	/**
	 * $values is an associative array with keys (min,max,start,step,smooth)
	 * @param array $values
	 */
	public function setValues($values=NULL){
		if(\is_array($values)){
			$this->_params=\array_merge($this->_params,$values);
		}
		return $this;
	}
	
	public function setReversed($value=true){
		if($value){
			$this->addClass('reversed');
		}
		return $this;
	}
	
	public function setVertical($value=true){
		if($value){
			$this->addClass('vertical');
		}
		return $this;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if(isset($this->_bsComponent)===false){
			$this->_bsComponent=$js->semantic()->slider('#'.$this->identifier,$this->_params,$this->_paramParts);
		}
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}
}

