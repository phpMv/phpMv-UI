<?php

namespace Ajax\common\traits;

trait JsUtilsActionsTrait {

	/**
	 * add class to element
	 *
	 * @param string $element
	 * @param string $class to add
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function addClass($element='this', $class='', $immediatly=false) {
		return $this->js->_genericCallValue('addClass',$element, $class, $immediatly);
	}

	/**
	 * Insert content, specified by the parameter, after each element in the set of matched elements
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function after($to, $element, $immediatly=false){
		return $this->js->_genericCallElement('after',$to, $element, $immediatly);
	}

	/**
	 * Insert content, specified by the parameter, before each element in the set of matched elements
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function before($to, $element, $immediatly=false){
		return $this->js->_genericCallElement('before',$to, $element, $immediatly);
	}

	/**
	 * Get or set the value of an attribute for the first element in the set of matched elements or set one or more attributes for every matched element.
	 * @param string $element
	 * @param string $attributeName
	 * @param string $value
	 * @param boolean $immediatly defers the execution if set to false
	 */
	public function attr($element='this', $attributeName='value', $value='', $immediatly=false) {
		return $this->js->_attr($element, $attributeName, $value, $immediatly);
	}

	/**
	 * Get or set the value of the first element in the set of matched elements or set one or more attributes for every matched element.
	 * @param string $element
	 * @param string $value
	 * @param boolean $immediatly defers the execution if set to false
	 */
	public function val($element='this',$value='',$immediatly=false){
		return $this->js->_genericCallValue('val',$element,$value,$immediatly);
	}

	/**
	 * Get or set the html of an attribute for the first element in the set of matched elements.
	 * @param string $element
	 * @param string $value
	 * @param boolean $immediatly defers the execution if set to false
	 */
	public function html($element='this', $value='', $immediatly=false) {
		return $this->js->_genericCallValue('html',$element, $value, $immediatly);
	}

	/**
	 * Outputs a javascript library animate event
	 *
	 * @param string $element element
	 * @param array $params
	 * @param string $speed One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $extra
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function animate($element='this', $params=array(), $speed='', $extra='', $immediatly=false) {
		return $this->js->_animate($element, $params, $speed, $extra, $immediatly);
	}

	/**
	 * Insert content, specified by the parameter $element, to the end of each element in the set of matched elements $to.
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function append($to, $element, $immediatly=false) {
		return $this->js->_genericCallElement('append',$to, $element, $immediatly);
	}

	/**
	 * Insert content, specified by the parameter $element, to the beginning of each element in the set of matched elements $to.
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function prepend($to, $element, $immediatly=false) {
		return $this->js->_genericCallElement('prepend',$to, $element, $immediatly);
	}

	/**
	 * Execute a javascript library hide action
	 *
	 * @param string - element
	 * @param string - One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string - Javascript callback function
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function fadeIn($element='this', $speed='', $callback='', $immediatly=false) {
		return $this->js->_fadeIn($element, $speed, $callback, $immediatly);
	}

	/**
	 * Execute a javascript library hide action
	 *
	 * @param string - element
	 * @param string - One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string - Javascript callback function
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function fadeOut($element='this', $speed='', $callback='', $immediatly=false) {
		return $this->js->_fadeOut($element, $speed, $callback, $immediatly);
	}

	/**
	 * Execute a javascript library slideUp action
	 *
	 * @param string - element
	 * @param string - One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string - Javascript callback function
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function slideUp($element='this', $speed='', $callback='', $immediatly=false) {
		return $this->js->_slideUp($element, $speed, $callback, $immediatly);
	}

	/**
	 * Execute a javascript library removeClass action
	 *
	 * @param string - element
	 * @param string - Class to add
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function removeClass($element='this', $class='', $immediatly=false) {
		return $this->js->_genericCall('removeClass',$element, $class, $immediatly);
	}

	/**
	 * Execute a javascript library slideDown action
	 *
	 * @param string - element
	 * @param string - One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string - Javascript callback function
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function slideDown($element='this', $speed='', $callback='', $immediatly=false) {
		return $this->js->_slideDown($element, $speed, $callback, $immediatly);
	}

	/**
	 * Execute a javascript library slideToggle action
	 *
	 * @param string - element
	 * @param string - One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string - Javascript callback function
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function slideToggle($element='this', $speed='', $callback='', $immediatly=false) {
		return $this->js->_slideToggle($element, $speed, $callback, $immediatly);
	}

	/**
	 * Execute a javascript library hide action
	 *
	 * @param string - element
	 * @param string - One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string - Javascript callback function
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function hide($element='this', $speed='', $callback='', $immediatly=false) {
		return $this->js->_hide($element, $speed, $callback, $immediatly);
	}

	/**
	 * Execute a javascript library toggle action
	 *
	 * @param string - element
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function toggle($element='this', $immediatly=false) {
		return $this->js->_toggle($element, $immediatly);
	}

	/**
	 * Execute a javascript library toggle class action
	 *
	 * @param string - element
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function toggleClass($element='this', $class='', $immediatly=false) {
		return $this->js->_genericCallValue('toggleClass',$element, $class, $immediatly);
	}

	/**
	 * Execute all handlers and behaviors attached to the matched elements for the given event.
	 * @param string $element
	 * @param string $event
	 * @param boolean $immediatly defers the execution if set to false
	 */
	public function trigger($element='this', $event='click', $immediatly=false) {
		return $this->js->_trigger($element, $event, $immediatly);
	}

	/**
	 * Execute a javascript library show action
	 *
	 * @param string - element
	 * @param string - One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string - Javascript callback function
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function show($element='this', $speed='', $callback='', $immediatly=false) {
		return $this->js->_show($element, $speed, $callback, $immediatly);
	}

	/**
	 * Allows to attach a condition
	 * @param string $condition
	 * @param string $jsCodeIfTrue
	 * @param string $jsCodeIfFalse
	 * @param boolean $immediatly defers the execution if set to false
	 */
	public function condition($condition, $jsCodeIfTrue, $jsCodeIfFalse=null, $immediatly=false) {
		return $this->js->_condition($condition, $jsCodeIfTrue, $jsCodeIfFalse, $immediatly);
	}

	/**
	 * Calls the JQuery callback $someThing on $element with facultative parameter $param
	 * @param string $element the element
	 * @param string $jqueryCall the JQuery callback
	 * @param mixed $param array or string parameters
	 * @param string $jsCallback javascript code to execute after the jquery call
	 * @return mixed
	 */
	public function doJQuery($element, $jqueryCall, $param="", $jsCallback="") {
		return $this->js->_doJQuery($element, $jqueryCall, $param, $jsCallback, true);
	}

	/**
	 * Calls the JQuery callback $someThing on $element with facultative parameter $param
	 * @param string $element the element
	 * @param string $jqueryCall the JQuery callback
	 * @param mixed $param array or string parameters
	 * @param string $jsCallback javascript code to execute after the jquery call
	 * @return mixed
	 */
	public function doJQueryDeferred($element, $jqueryCall, $param="", $jsCallback="") {
		return $this->js->_doJQuery($element, $jqueryCall, $param, $jsCallback, false);
	}

	/**
	 * Calls the JQuery callback $jqueryCall on $element with facultative parameter $param in response to an event $event
	 * @param string $event
	 * @param string $element
	 * @param string $elementToModify
	 * @param string $jqueryCall
	 * @param string $param
	 * @param array $parameters default : array("preventDefault"=>false,"stopPropagation"=>false,"jsCallback"=>'',"immediatly"=>true)
	 */
	public function doJQueryOn($event, $element, $elementToModify, $jqueryCall, $param="", $parameters=array()) {
		$jsCallback="";
		$stopPropagation=false;
		$preventDefault=false;
		$immediatly=true;
		extract($parameters);
		return $this->js->_doJQueryOn($event, $element, $elementToModify, $jqueryCall, $param, $preventDefault, $stopPropagation, $jsCallback,$immediatly);
	}

	/**
	 * Executes the code $js
	 * @param string $js Code to execute
	 * @param boolean $immediatly delayed if false
	 * @return String
	 */
	public function exec($js, $immediatly=false) {
		$script=$this->js->_exec($js, $immediatly);
		return $script;
	}

	/**
	 * Executes the javascript code $js when $event fires on $element
	 * @param string $event
	 * @param string $element
	 * @param string $js Code to execute
	 * @param array $parameters default : array("preventDefault"=>false,"stopPropagation"=>false,"immediatly"=>true)
	 * @return String
	 */
	public function execOn($event, $element, $js, $parameters=array()) {
		$stopPropagation=false;
		$preventDefault=false;
		$immediatly=true;
		extract($parameters);
		$script=$this->js->_execOn($element, $event, $js, $preventDefault, $stopPropagation,$immediatly);
		return $script;
	}
}