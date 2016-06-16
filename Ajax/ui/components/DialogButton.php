<?php

namespace Ajax\ui\Components;

use Phalcon\Text;
use Ajax\JsUtils;
use Ajax;
use Ajax\common\components\BaseComponent;

/**
 * JQuery UI Button for the Dialog Component
 * @author jc
 * @version 1.001
 */
class DialogButton extends BaseComponent {

	private function addFunction($jsCode) {
		if (!Text::startsWith($jsCode, "function"))
			$jsCode="%function(){" . $jsCode . "}%";
		return $jsCode;
	}

	public function __construct($caption, $jsCode, $event="click") {
		parent::__construct(NULL);
		$this->params ["text"]=$caption;
		$this->params [$event]=$this->addFunction($jsCode);
	}

	public function __toString() {
		return $this->getScript();
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\common\BaseComponent::getScript()
	 */
	public function getScript() {
		return json_encode($this->params, JSON_UNESCAPED_SLASHES);
	}

	public static function cancelButton($caption="Annuler") {
		return new DialogButton($caption, "$( this ).dialog( 'close' );");
	}

	public static function submitButton(JsUtils $js, $url, $form, $responseElement, $caption="Okay") {
		return new DialogButton($caption, $js->postForm($url, $form, $responseElement) . ";$( this ).dialog( 'close' );");
	}
}