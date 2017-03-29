<?php

namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\JsUtils;
use Ajax\semantic\html\base\constants\Direction;

class HtmlSearch extends HtmlSemDoubleElement {
	private $_elements=array ();
	private $_searchFields=array ("title" );
	private $_local=false;

	public function __construct($identifier, $placeholder=NULL, $icon=NULL) {
		parent::__construct("search-" . $identifier, "div", "ui search", array ());
		$this->_identifier=$identifier;
		$this->createField($placeholder, $icon);
		$this->createResult();
		$this->_params["type"]="standard";
	}

	private function createField($placeholder=NULL, $icon=NULL) {
		$field=new HtmlInput($this->identifier);
		if (isset($placeholder))
			$field->setPlaceholder($placeholder);
		if (isset($icon))
			$field->addIcon($icon, Direction::RIGHT);
		$field->getDataField()->setClass("prompt");
		$this->content["field"]=$field;
		return $field;
	}

	private function createResult() {
		$this->content["result"]=new HtmlSemDoubleElement("results-" . $this->identifier, "div", "results");
		return $this->content["result"];
	}

	public function addResult($object) {
		$this->_local=true;
		$this->_elements[]=$object;
		return $this;
	}

	public function addResults($objects) {
		$this->_local=true;
		$this->_elements=\array_merge($this->_elements, $objects);
		return $this;
	}

	public function setUrl($url) {
		$this->_params["apiSettings"]="%{url: %quote%" . $url . "%quote%}%";
		return $this;
	}

	public function setType($type) {
		$this->_params["type"]=$type;
		return $this;
	}

	public function getType() {
		return $this->_params["type"];
	}

	private function resultsToJson() {
		$result=\json_encode($this->_elements);
		return $result;
	}

	public function setLocal() {
		$this->_local=true;
	}

	public function run(JsUtils $js) {
		$this->_params["onSelect"]='%function(result,response){$(%quote%#' . $this->identifier . '%quote%).trigger(%quote%onSelect%quote%, {%quote%result%quote%: result, %quote%response%quote%:response} );}%';
		$searchFields=\json_encode($this->_searchFields);
		$searchFields=str_ireplace("\"", "%quote%", $searchFields);
		$this->_params["searchFields"]="%" . $searchFields . "%";
		if ($this->_local === true) {
			$this->_params["source"]="%content%";
			$this->addEvent("beforeExecute", "var content=" . $this->resultsToJson() . ";");
		}
		if (isset($this->_bsComponent) === false) {
			$this->_bsComponent=$js->semantic()->search("#" . $this->identifier, $this->_params);
		}
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}

	public function setFluid() {
		return $this->addToProperty("class", "fluid");
	}

	public function onSelect($jsCode) {
		$this->addEvent("onSelect", $jsCode);
	}

	private function _opOnSelect($operation, $url, $responseElement="", $parameters=array()) {
		return $this->_ajaxOn($operation, "onSelect", $url, $responseElement, $parameters);
	}

	public function getOnSelect($url, $responseElement="", $parameters=array()) {
		$parameters["params"]="data.result";
		return $this->_opOnSelect("get", $url, $responseElement, $parameters);
	}

	public function postOnSelect($url, $responseElement="", $parameters=array()) {
		$parameters["params"]="data.result";
		return $this->_opOnSelect("post", $url, $responseElement, $parameters);
	}
}
