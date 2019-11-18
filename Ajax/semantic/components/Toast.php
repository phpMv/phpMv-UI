<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

/**
 * Ajax\semantic\components$Toast
 * This class is part of phpMv-ui
 * @author jcheron <myaddressmail@gmail.com>
 * @version 1.0.0
 * @since 2.3.0
 * @see https://fomantic-ui.com/modules/toast.html
 */
class Toast extends SimpleSemExtComponent {

	public function __construct(JsUtils $js=NULL) {
		parent::__construct($js);
		$this->uiName='toast';
	}

	public function close(){
		return $this->addBehavior('close');
	}

	public function setClass($value){
		$this->params['class']=$value;
	}
	
	public function setCloseIcon(){
		$this->params['closeIcon']=true;
	}
	
	public function setShowIcon($value=false){
		$this->params['showIcon']=$value;
	}
	
	public function setCloseOnClick($value){
		$this->params['closeOnClick']=$value;
	}
	public function setTitle($title){
		$this->params['title']=$title;
	}
	
	public function setMessage($message){
		$this->params['message']=$message;
	}
	
	public function setDisplayTime($time){
		$this->params['displayTime']=$time;
	}
	
	public function setShowProgress($value='top'){
		$this->params['showProgress']=$value;
	}
	
	public function setClassProgress($value){
		$this->params['classProgress']=$value;
	}

	public function setOnShow($jsCode) {
		$this->addComponentEvent('onShow', $jsCode);
	}
	
	public function setOnHide($jsCode) {
		$this->addComponentEvent('onHide', $jsCode);
	}
	
	public function setOnApprove($jsCode) {
		$this->addComponentEvent('onApprove', $jsCode);
	}
	
	public function setOnDeny($jsCode) {
		$this->addComponentEvent('onDeny', $jsCode);
	}
}
