<?php

namespace Ajax\common\components;

use Ajax\common\components\SimpleComponent;
use Ajax\common\JsCode;

class SimpleExtComponent extends SimpleComponent {
	protected $events=array ();
	protected $jsCodes=array ();

	public function addEvent($event, $jsCode) {
		$this->events [$event]=$jsCode;
	}

	public function getScript() {
		parent::getScript();
		foreach ( $this->jsCodes as $jsCode ) {
			$this->jquery_code_for_compile []=$jsCode->compile(array (
					"identifier" => $this->attachTo
			));
		}
		return $this->compileJQueryCode();
	}

	public function addCode($jsCode) {
		$this->jsCodes []=new JsCode($jsCode);
	}
}