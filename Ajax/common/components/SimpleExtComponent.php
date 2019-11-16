<?php

namespace Ajax\common\components;

use Ajax\common\JsCode;

class SimpleExtComponent extends SimpleComponent {
	protected $events=array ();
	protected $jsCodes=array ();

	public function addEvent($event, $jsCode) {
		$this->events [$event]=$jsCode;
		return $this;
	}

	public function getScript() {
		parent::getScript();
		$this->compileJsCodes();
		return $this->compileJQueryCode();
	}
	
	protected function compileJsCodes(){
		foreach ( $this->jsCodes as $jsCode ) {
			$this->jquery_code_for_compile []=$jsCode->compile(array (
				"identifier" => $this->attachTo
			));
		}
	}

	public function addCode($jsCode) {
		$this->jsCodes []=new JsCode($jsCode);
	}

}
