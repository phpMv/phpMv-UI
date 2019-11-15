<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

class Toast extends SimpleSemExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="toast";
	}

	protected function setBehavior($name) {
		$this->paramParts[]=[$name];
		return $this;
	}

	public function showDimmer(){
		return $this->setBehavior("hide dimmer");
	}

	public function setCloseIcon(){
		$this->params["closeIcon"]=true;
	}
	
	public function setDisplayTime($time){
		$this->params["displayTime"]=$time;
	}

	public function setOnHide($jsCode) {
		$jsCode=str_ireplace("\"","%quote%", $jsCode);
		return $this->setParam("onHide", "%function(){".$jsCode."}%");
	}
}
