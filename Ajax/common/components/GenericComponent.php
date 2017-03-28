<?php

namespace Ajax\common\components;

use Ajax\common\components\SimpleExtComponent;

class GenericComponent extends SimpleExtComponent {

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\common\components\SimpleExtComponent::getScript()
	 */
	public function getScript() {
		$this->jquery_code_for_compile=array ();
		foreach ( $this->jsCodes as $jsCode ) {
			$this->jquery_code_for_compile []=$jsCode->compile(array (
					"identifier" => $this->attachTo
			));
		}
		$this->compileEvents();
		return $this->compileJQueryCode();
	}
}
