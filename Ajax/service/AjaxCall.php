<?php

namespace Ajax\service;


use Ajax\JsUtils;
class AjaxCall {
	private $method;
	private $parameters;

	public function __construct($method, $parameters) {
		$this->method=$method;
		$this->parameters=$parameters;
	}

	public function compile(JsUtils $js=null) {
		if ($js===null)
			return;
		$params="{}";
		$jsCallback=NULL;
		$attr="id";
		$validation=false;
		$stopPropagation=true;
		$preventDefault=true;
		$jqueryDone="html";
		$ajaxTransition=null;
		$hasLoader=true;
		$method="get";
		$rowClass="_json";
		extract($this->parameters);
		$result=$this->_eventPreparing($preventDefault, $stopPropagation);
		switch($this->method) {
			case "get":
				$result.=$js->getDeferred($url, $responseElement, $params, $jsCallback, $attr,$jqueryDone,$ajaxTransition);
				break;
			case "post":
				$result.=$js->postDeferred($url, $responseElement, $params, $jsCallback, $attr,$hasLoader,$jqueryDone,$ajaxTransition);
				break;
			case "postForm":
				$result.=$js->postFormDeferred($url, $form, $responseElement, $validation, $jsCallback, $attr,$hasLoader,$jqueryDone,$ajaxTransition);
				break;
			case "json":
				$result.=$js->jsonDeferred($url,$method,$params,$jsCallback);
				break;
			case "jsonArray":
				$result.=$js->jsonArrayDeferred($modelSelector, $url,$method,$params,$jsCallback,$rowClass);
				break;
		}
		return $result;
	}

	protected function _eventPreparing($preventDefault,$stopPropagation){
		$result="";
		if ($preventDefault===true) {
			$result.=Javascript::$preventDefault;
		}
		if ($stopPropagation===true) {
			$result.=Javascript::$stopPropagation;
		}
		return $result;
	}

	public function getMethod() {
		return $this->method;
	}

	public function setMethod($method) {
		$this->method=$method;
		return $this;
	}

	public function getParameters() {
		return $this->parameters;
	}

	public function setParameters($parameters) {
		$this->parameters=$parameters;
		return $this;
	}
}
