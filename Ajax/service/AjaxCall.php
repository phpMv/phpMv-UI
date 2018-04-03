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
		$stopPropagation=true;
		$preventDefault=true;
		$method="get";
		$this->parameters["immediatly"]=false;
		extract($this->parameters);
		$result=$this->_eventPreparing($preventDefault, $stopPropagation);
		switch($this->method) {
			case "get":
				$result.=$js->getDeferred($url, $responseElement, $this->parameters);
				break;
			case "post":
				$result.=$js->postDeferred($url, $params,$responseElement, $this->parameters);
				break;
			case "postForm":
				$result.=$js->postFormDeferred($url, $form, $responseElement, $this->parameters);
				break;
			case "json":
				$result.=$js->jsonDeferred($url,$method,$this->parameters);
				break;
			case "jsonArray":
				$result.=$js->jsonArrayDeferred($modelSelector, $url,$method,$this->parameters);
				break;
			default:
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
