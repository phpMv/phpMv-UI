<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

/**
 * Ajax\semantic\components$Slider
 * This class is part of phpMv-ui
 * @author jcheron <myaddressmail@gmail.com>
 * @version 1.0.0
 * @since 2.3.0
 * @see https://fomantic-ui.com/modules/slider.html
 */
class Slider extends SimpleSemExtComponent {

	public function __construct(JsUtils $js=NULL) {
		parent::__construct($js);
		$this->uiName='slider';
	}

	public function close(){
		return $this->addBehavior('close');
	}

	public function setInterpretLabel($labels){
		$this->addCode('var labels='.\json_encode($labels).';');
		$this->params['interpretLabel']='%function(value) {return labels[value];}%';
	}
	
	public function setMin($min){
		$this->params['min']=$min;
	}
	
	public function setMax($max){
		$this->params['max']=$max;
	}
	
	public function setStart($start){
		$this->params['start']=$start;
	}
	
	public function setEnd($end){
		$this->params['end']=$end;
	}
	
	public function setStep($step){
		$this->params['step']=$step;
	}

	public function setSmooth($smooth) {
		$this->params['smooth']=$smooth;
	}
	
	public function setOnChange($jsCode) {
		$this->addComponentEvent('onChange', $jsCode);
	}
	
	public function setOnMove($jsCode) {
		$this->addComponentEvent('onMove', $jsCode);
	}

}
