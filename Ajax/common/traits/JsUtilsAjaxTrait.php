<?php

namespace Ajax\common\traits;

use Ajax\service\JArray;
use Ajax\Jquery;

/**
 * @author jc
 * @property Jquery $js
 */
trait JsUtilsAjaxTrait {

	public function setAjaxLoader($loader) {
		$this->js->_setAjaxLoader($loader);
	}

	/**
	 * Performs an ajax GET request
	 * @param string $url The url of the request
	 * @param string $params JSON parameters
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function get($url, $responseElement="", $params="{}", $jsCallback=NULL,$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->js->_get($url, $params, $responseElement, $jsCallback, NULL, $hasLoader,$jqueryDone,$ajaxTransition,true);
	}

	/**
	 * Performs an ajax request and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $immediatly
	 */
	public function json($url, $method="get", $params="{}", $jsCallback=NULL, $attr="id", $context="document",$immediatly=false) {
		return $this->js->_json($url, $method, $params, $jsCallback, $attr, $context,$immediatly);
	}

	/**
	 * Makes an ajax request and receives the JSON data types by assigning DOM elements with the same name when $event fired on $element
	 * @param string $element
	 * @param string $event
	 * @param string $url the request address
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","immediatly"=>true)
	 */
	public function jsonOn($event,$element, $url,$parameters=array()) {
		return $this->js->_jsonOn($event, $element, $url,$parameters);
	}

	/**
	 * Prepares an ajax request delayed and receives the JSON data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $params Paramètres passés au format JSON
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 */
	public function jsonDeferred($url, $method="get", $params="{}", $jsCallback=NULL) {
		return $this->js->_json($url, $method, $params, $jsCallback, NULL, false);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name
	 * @param string $url the request url
	 * @param string $params The JSON parameters
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 */
	public function jsonArray($maskSelector, $url, $method="get", $params="{}", $jsCallback=NULL) {
		return $this->js->_jsonArray($maskSelector, $url, $method, $params, $jsCallback, NULL,true);
	}

	/**
	 * Peforms an ajax request delayed and receives a JSON array data types by copying and assigning them to the DOM elements with the same name
	 * @param string $maskSelector the selector of the element to clone
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $method Method used
	 * @param string $jsCallback javascript code to execute after the request
	 */
	public function jsonArrayDeferred($maskSelector, $url, $method="get", $params="{}", $jsCallback=NULL) {
		return $this->js->_jsonArray($maskSelector, $url, $method, $params, $jsCallback, NULL, false);
	}

	/**
	 * Performs an ajax request and receives the JSON array data types by assigning DOM elements with the same name when $event fired on $element
	 * @param string $element
	 * @param string $event
	 * @param string $url the request url
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","params"=>"{}","method"=>"get","immediatly"=>true)
	 */
	public function jsonArrayOn($event,$element,$maskSelector, $url,$parameters=array()) {
		return $this->js->_jsonArrayOn($event,$element,$maskSelector, $url, $parameters);
	}

	/**
	 * Prepares a Get ajax request
	 * To use on an event
	 * @param string $url The url of the request
	 * @param string $params JSON parameters
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $attr the html attribute added to the request
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function getDeferred($url, $responseElement="", $params="{}", $jsCallback=NULL,$attr="id",$jqueryDone="html",$ajaxTransition=null) {
		return $this->js->_get($url, $params, $responseElement, $jsCallback, $attr, false,$jqueryDone,$ajaxTransition);
	}

	/**
	 * Performs a get to $url on the event $event on $element
	 * and display it in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html")
	 */
	public function getOn($event, $element, $url, $responseElement="", $parameters=array()) {
		$params=JArray::getDefaultValue($parameters, "params", "{}");
		return $this->js->_getOn($event, $element, $url, $params, $responseElement, $parameters);
	}

	/**
	 * Performs a get to $url on the click event on $element
	 * and display it in $responseElement
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $responseElement The selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"params"=>"{}","jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html")
	 */
	public function getOnClick($element, $url, $responseElement="", $parameters=array()) {
		return $this->getOn("click", $element, $url, $responseElement, $parameters);
	}

	/**
	 * Makes an ajax post
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function post($url, $responseElement="", $params="{}", $jsCallback=NULL,$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->js->_post($url, $params, $responseElement, $jsCallback, NULL, $hasLoader,$jqueryDone,$ajaxTransition,true);
	}

	/**
	 * Prepares a delayed ajax POST
	 * to use on an event
	 * @param string $url the request url
	 * @param string $params JSON parameters
	 * @param string $attr the html attribute added to the request
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function postDeferred($url, $responseElement="", $params="{}", $jsCallback=NULL, $attr="id",$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->js->_post($url, $params, $responseElement, $jsCallback, $attr, $hasLoader,$jqueryDone,$ajaxTransition,false);
	}

	/**
	 * Performs a post to $url on the event $event fired on $element and pass the parameters $params
	 * Display the result in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $params The parameters to send
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null)
	 */
	public function postOn($event, $element, $url, $params="{}", $responseElement="", $parameters=array()) {
		return $this->js->_postOn($event, $element,  $url, $params, $responseElement, $parameters);
	}

	/**
	 * Performs a post to $url on the click event fired on $element and pass the parameters $params
	 * Display the result in $responseElement
	 * @param string $element
	 * @param string $url The url of the request
	 * @param string $params The parameters to send
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null)
	 */
	public function postOnClick($element, $url, $params="{}", $responseElement="", $parameters=array()) {
		return $this->postOn("click", $element, $url, $params, $responseElement, $parameters);
	}

	/**
	 * Performs a post form with ajax
	 * @param string $url The url of the request
	 * @param string $form The form HTML id
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function postForm($url, $form, $responseElement, $validation=false, $jsCallback=NULL,$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->js->_postForm($url, $form, $responseElement, $validation, $jsCallback, NULL, $hasLoader,$jqueryDone,$ajaxTransition,true);
	}

	/**
	 * Performs a delayed post form with ajax
	 * For use on an event
	 * @param string $url The url of the request
	 * @param string $form The form HTML id
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param string $jsCallback javascript code to execute after the request
	 * @param string $attr the html attribute added to the request
	 * @param boolean $hasLoader true for showing ajax loader. default : true
	 * @param string $jqueryDone the jquery function call on ajax data. default:html
	 * @param string|callable $ajaxTransition
	 */
	public function postFormDeferred($url, $form, $responseElement, $validation=false, $jsCallback=NULL,$attr="id",$hasLoader=true,$jqueryDone="html",$ajaxTransition=null) {
		return $this->js->_postForm($url, $form, $responseElement, $validation, $jsCallback, $attr, $hasLoader,$jqueryDone,$ajaxTransition,false);
	}

	/**
	 * Performs a post form with ajax in response to an event $event on $element
	 * display the result in $responseElement
	 * @param string $event
	 * @param string $element
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null)
	 */
	public function postFormOn($event, $element, $url, $form, $responseElement="", $parameters=array()) {
		return $this->js->_postFormOn($event,$element, $url, $form, $responseElement, $parameters);
	}

	/**
	 * Performs a post form with ajax in response to the click event on $element
	 * display the result in $responseElement
	 * @param string $element
	 * @param string $url
	 * @param string $form
	 * @param string $responseElement selector of the HTML element displaying the answer
	 * @param array $parameters default : array("preventDefault"=>true,"stopPropagation"=>true,"validation"=>false,"jsCallback"=>NULL,"attr"=>"id","hasLoader"=>true,"immediatly"=>true,"jqueryDone"=>"html","ajaxTransition"=>null)
	 */
	public function postFormOnClick($element, $url, $form, $responseElement="", $parameters=array()) {
		return $this->postFormOn("click", $element, $url, $form, $responseElement, $parameters);
	}
}