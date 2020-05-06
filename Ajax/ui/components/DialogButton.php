<?php
namespace Ajax\ui\components;

use Ajax\JsUtils;
use Ajax\common\components\BaseComponent;
use Ajax\service\JString;

/**
 * JQuery UI Button for the Dialog Component
 *
 * @author jc
 * @version 1.001
 */
class DialogButton extends BaseComponent {

	private function addFunction($jsCode) {
		if (! JString::startsWith($jsCode, "function"))
			$jsCode = "%function(){" . $jsCode . "}%";
		return $jsCode;
	}

	public function __construct($caption, $jsCode, $event = "click") {
		parent::__construct(NULL);
		$this->params["text"] = $caption;
		$this->params[$event] = $this->addFunction($jsCode);
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

	public static function cancelButton($caption = "Annuler") {
		return new DialogButton($caption, "$( this ).dialog( 'close' );");
	}

	/**
	 *
	 * @param JsUtils $js
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement
	 * @param string $caption
	 * @param array $parameters
	 *        	default : array("params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"jqueryDone"=>"html","ajaxTransition"=>null,"jsCondition"=>NULL,"headers"=>null)
	 * @return DialogButton
	 */
	public static function submitButton(JsUtils $js, $url, $form, $responseElement, $caption = "Okay", $parameters = []) {
		return new DialogButton($caption, $js->postFormDeferred($url, $form, $responseElement, $parameters) . ";$( this ).dialog( 'close' );");
	}
}
