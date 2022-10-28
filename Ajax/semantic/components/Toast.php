<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

/**
 * Ajax\semantic\components$Toast
 * This class is part of phpMv-ui
 * @author jcheron <myaddressmail@gmail.com>
 * @version 1.0.1
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
		return $this;
	}
	
	public function setCloseIcon(){
		$this->params['closeIcon']=true;
		return $this;
	}
	
	public function setShowIcon($value=false){
		$this->params['showIcon']=$value;
		return $this;
	}

	public function setPreserveHTML($value=false){
		$this->params['preserveHTML']=$value;
		return $this;
	}
	
	public function setCloseOnClick($value){
		$this->params['closeOnClick']=$value;
		return $this;
	}
	
	public function setTitle($title){
		$this->params['title']=$title;
		return $this;
	}

	public function setActions(array $actions){
		foreach ($actions as &$action){
			if(isset($action['click'])){
				$js=\str_ireplace("\"","%quote%", $action['click']);
				$action['click']="%function(){".$js."}%";
			}
		}
		$this->params['actions']=$actions;
		if(count($actions)>0) {
			$this->params['displayTime'] = 0;
		}
		return $this;
	}
	
	public function setMessage($message){
		$this->params['message']=$message;
		return $this;
	}
	
	public function setPosition($position){
		$this->params['position']=$position;
		return $this;
	}
	
	public function setDisplayTime($time){
		$this->params['displayTime']=$time;
		return $this;
	}
	
	public function setShowProgress($value='top'){
		$this->params['showProgress']=$value;
		return $this;
	}
	
	public function setClassProgress($value){
		$this->params['classProgress']=$value;
		return $this;
	}

	public function setOnShow($jsCode) {
		$this->addComponentEvent('onShow', $jsCode);
	}
	
	public function setOnHide($jsCode) {
		$this->addComponentEvent('onHide', $jsCode);
		return $this;
	}
	
	public function setOnApprove($jsCode) {
		$this->addComponentEvent('onApprove', $jsCode);
		return $this;
	}
	
	public function setOnDeny($jsCode) {
		$this->addComponentEvent('onDeny', $jsCode);
		return $this;
	}
}
