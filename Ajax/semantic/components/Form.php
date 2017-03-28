<?php
namespace Ajax\semantic\components;

use Ajax\JsUtils;
use Ajax\semantic\components\validation\FieldValidation;
use Ajax\semantic\components\validation\Rule;
/**
 * @author jc
 * @version 1.001
 * Generates a JSON form validation string
 */
class Form extends SimpleSemExtComponent {

	/**
	 * @var array
	 */
	public function __construct(JsUtils $js=null) {
		parent::__construct($js);
		$this->uiName="form";
		$this->params["fields"]=[];
	}

	public function addField($identifier){
		$this->params["fields"][$identifier]=new FieldValidation($identifier);
	}

	public function setInline($value){
		return $this->setParam("inline", true);
	}

	public function setOn($value){
		return $this->setParam("on", $value);
	}



	/**
	 * @param string $identifier
	 * @param Rule|string $type
	 * @param mixed $value
	 * @param string|NULL $prompt
	 */
	public function addFieldRule($identifier,$type,$prompt=NULL,$value=NULL){
		if(isset($this->params["fields"][$identifier])===false){
			$this->addField($identifier);
		}
		$this->params["fields"][$identifier]->addRule($type,$prompt,$value);
	}

	/**
	 * @param FieldValidation $fieldValidation
	 */
	public function addFieldValidation($fieldValidation){
		$this->params["fields"][$fieldValidation->getIdentifier()]=$fieldValidation;
	}

	public function setJs(JsUtils $js){
		$this->js=$js;
	}

	public function getScript() {
		$allParams=$this->params;
		$this->jquery_code_for_compile=array ();
		$this->jquery_code_for_compile []="$( \"".$this->attachTo."\" ).{$this->uiName}(".$this->getParamsAsJSON($allParams).");";
		$this->compileEvents();
		return $this->compileJQueryCode();
	}

	public function onValid($jsCode){
		$this->addComponentEvent("onValid", $jsCode);
	}
}
