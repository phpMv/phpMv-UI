<?php

namespace Ajax\semantic\components;

use Ajax\common\components\SimpleExtComponent;
use Ajax\JsUtils;

class SimpleSemExtComponent extends SimpleExtComponent {
	protected $paramParts;
	public function __construct(JsUtils $js=NULL) {
		parent::__construct($js);
		$this->paramParts=array();
	}

	protected function addBehavior($name) {
		$this->paramParts[]=[$name];
		return $this;
	}
	
	protected function generateParamParts(){
		$results=[];
		foreach ($this->paramParts as $paramPart){
			$results[]="{$this->uiName}(".\implode(",", $paramPart).")";
		}
		return \implode(".", $results);
	}

	public function getScript() {
		$allParams=$this->params;
		$this->jquery_code_for_compile=array ();
		$this->compileJsCodes();
		$paramParts="";
		if(\sizeof($this->paramParts)>0){
			$paramParts=".".$this->generateParamParts();
		}
		$this->jquery_code_for_compile []="$( \"".$this->attachTo."\" ).{$this->uiName}(".$this->getParamsAsJSON($allParams).")".$paramParts.";";
		$this->compileEvents();
		return $this->compileJQueryCode();
	}

	public function setParamParts($paramParts) {
		$this->paramParts=$paramParts;
		return $this;
	}

	public function addComponentEvent($event,$jsCode){
		$jsCode=\str_ireplace("\"","%quote%", $jsCode);
		return $this->setParam($event, "%function(module){".$jsCode."}%");
	}
	
	public function setJs(JsUtils $js){
		$this->js=$js;
		$js->semantic()->addComponent($this, $this->attachTo, $this->params);
	}

}
