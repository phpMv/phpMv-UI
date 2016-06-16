<?php

namespace Ajax\bootstrap\components;

class Splitbutton extends Dropdown {

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\common\SimpleComponent::compileEvents()
	 */
	protected function compileEvents() {
		foreach ( $this->events as $event => $jsCode ) {
			if ($event==="buttonClick") {
				$this->jquery_code_for_compile []="$( \"#split-".preg_replace('/[^a-zA-Z0-9\-.]/s', '', $this->attachTo)."\" ).on(\"click\" , function( event, data ) {".$jsCode."});";
			} else {
				$this->jquery_code_for_compile []="$( \"".$this->attachTo."\" ).on(\"".$event."\" , function( event, data ) {".$jsCode."});";
			}
		}
	}
}