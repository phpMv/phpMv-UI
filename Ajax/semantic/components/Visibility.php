<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

/**
 * Visibility provides a set of callbacks for when a content appears in the viewport
 * @see https://semantic-ui.com/behaviors/visibility.html
 * @author jc
 *
 */
class Visibility extends SimpleSemExtComponent{
	
	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="visibility";
		$this->params=["once"=>false,"observeChanges"=>true];
	}
	
	public function setOnce($value=false) {
		return $this->setParam("once", $value);
	}
	
	public function setObserveChanges($value=true) {
		return $this->setParam("observeChanges", $value);
	}
	
	public function setOnTopVisible($value) {
		$this->params["onTopVisible"]="%function(){".$value."}%";
	}
	
	public function setOnBottomVisible($value) {
		$this->params["onBottomVisible"]="%function(){".$value."}%";
	}
}
