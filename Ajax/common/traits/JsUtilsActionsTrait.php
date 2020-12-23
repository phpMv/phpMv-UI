<?php

namespace Ajax\common\traits;

use Ajax\service\Javascript;

/**
 *
 * @author jc
 * @property array $jquery_code_for_compile
 * @property array $jquery_code_for_compile_at_last
 */
trait JsUtilsActionsTrait {

	abstract public function _add_event($element, $js, $event, $preventDefault = false, $stopPropagation = false, $immediatly = true, $listenerOn=false);

	/**
	 * show or hide with effect
	 *
	 * @param string $action
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	protected function _showHideWithEffect($action, $element = 'this', $speed = '', $callback = '', $immediatly = false) {
		$element = Javascript::prep_element ( $element );
		$speed = $this->_validate_speed ( $speed );
		if ($callback != '') {
			$callback = ", function(){\n{$callback}\n}";
		}
		$str = "$({$element}).{$action}({$speed}{$callback});";
		if ($immediatly)
			$this->jquery_code_for_compile [] = $str;
		return $str;
	}

	/**
	 * Ensures the speed parameter is valid for jQuery
	 *
	 * @param string|int $speed
	 * @return string
	 */
	private function _validate_speed($speed) {
		if (in_array ( $speed, array (
				'slow',
				'normal',
				'fast'
		) )) {
			$speed = '"' . $speed . '"';
		} elseif (preg_match ( "/[^0-9]/", $speed )) {
			$speed = '';
		}

		return $speed;
	}

	/**
	 * Execute a generic jQuery call with a value.
	 *
	 * @param string $jQueryCall
	 * @param string $element
	 * @param string $param
	 * @param boolean $immediatly
	 *        	delayed if false
	 */
	public function _genericCallValue($jQueryCall, $element = 'this', $param = "", $immediatly = false) {
		$element = Javascript::prep_element ( $element );
		if (isset ( $param )) {
			$param = Javascript::prep_value ( $param );
			$str = "$({$element}).{$jQueryCall}({$param});";
		} else
			$str = "$({$element}).{$jQueryCall}();";
		if ($immediatly)
			$this->jquery_code_for_compile [] = $str;
		return $str;
	}

	/**
	 * Execute a generic jQuery call with 2 elements.
	 *
	 * @param string $jQueryCall
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly
	 *        	delayed if false
	 * @return string
	 */
	public function _genericCallElement($jQueryCall, $to = 'this', $element = '', $immediatly = false) {
		$to = Javascript::prep_element ( $to );
		$element = Javascript::prep_element ( $element );
		$str = "$({$to}).{$jQueryCall}({$element});";
		if ($immediatly)
			$this->jquery_code_for_compile [] = $str;
		return $str;
	}

	/**
	 * add class to element
	 *
	 * @param string $element
	 * @param string $class
	 *        	to add
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function addClass($element = 'this', $class = '', $immediatly = false) {
		return $this->_genericCallValue ( 'addClass', $element, $class, $immediatly );
	}

	/**
	 * Insert content, specified by the parameter, after each element in the set of matched elements
	 *
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function after($to, $element, $immediatly = false) {
		return $this->_genericCallElement ( 'after', $to, $element, $immediatly );
	}

	/**
	 * Insert content, specified by the parameter, before each element in the set of matched elements
	 *
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function before($to, $element, $immediatly = false) {
		return $this->_genericCallElement ( 'before', $to, $element, $immediatly );
	}

	/**
	 * Get or set the value of an attribute for the first element in the set of matched elements or set one or more attributes for every matched element.
	 *
	 * @param string $element
	 * @param string $attributeName
	 * @param string $value
	 * @param boolean $immediatly
	 *        	delayed if false
	 */
	public function attr($element = 'this', $attributeName = 'id', $value = "", $immediatly = false) {
		$element = Javascript::prep_element ( $element );
		if (isset ( $value )) {
			$value = Javascript::prep_value ( $value );
			$str = "$({$element}).attr(\"$attributeName\",{$value});";
		} else
			$str = "$({$element}).attr(\"$attributeName\");";
		if ($immediatly)
			$this->jquery_code_for_compile [] = $str;
		return $str;
	}

	/**
	 * Get or set the value of the first element in the set of matched elements or set one or more attributes for every matched element.
	 *
	 * @param string $element
	 * @param string $value
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 */
	public function val($element = 'this', $value = '', $immediatly = false) {
		return $this->_genericCallValue ( 'val', $element, $value, $immediatly );
	}

	/**
	 * Get or set the html of an attribute for the first element in the set of matched elements.
	 *
	 * @param string $element
	 * @param string $value
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 */
	public function html($element = 'this', $value = '', $immediatly = false) {
		return $this->_genericCallValue ( 'html', $element, $value, $immediatly );
	}

	/**
	 * Outputs a javascript library animate event
	 *
	 * @param string $element
	 *        	element
	 * @param array|string $params
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $extra
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function animate($element = 'this', $params = array (), $speed = '', $extra = '', $immediatly = false) {
		$element = Javascript::prep_element ( $element );
		$speed = $this->_validate_speed ( $speed );

		$animations = "\t\t\t";
		if (\is_array ( $params )) {
			foreach ( $params as $param => $value ) {
				$animations .= $param . ': \'' . $value . '\', ';
			}
		}
		$animations = substr ( $animations, 0, - 2 ); // remove the last ", "

		if ($speed != '') {
			$speed = ', ' . $speed;
		}

		if ($extra != '') {
			$extra = ', ' . $extra;
		}

		$str = "$({$element}).animate({\n$animations\n\t\t}" . $speed . $extra . ");";

		if ($immediatly)
			$this->jquery_code_for_compile [] = $str;
		return $str;
	}

	/**
	 * Insert content, specified by the parameter $element, to the end of each element in the set of matched elements $to.
	 *
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function append($to, $element, $immediatly = false) {
		return $this->_genericCallElement ( 'append', $to, $element, $immediatly );
	}

	/**
	 * Insert content, specified by the parameter $element, to the beginning of each element in the set of matched elements $to.
	 *
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function prepend($to, $element, $immediatly = false) {
		return $this->_genericCallElement ( 'prepend', $to, $element, $immediatly );
	}

	/**
	 * Execute a javascript library hide action
	 *
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function fadeIn($element = 'this', $speed = '', $callback = '', $immediatly = false) {
		return $this->_showHideWithEffect ( "fadeIn", $element, $speed, $callback, $immediatly );
	}

	/**
	 * Execute a javascript library hide action
	 *
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function fadeOut($element = 'this', $speed = '', $callback = '', $immediatly = false) {
		return $this->_showHideWithEffect ( "fadeOut", $element, $speed, $callback, $immediatly );
	}

	/**
	 * Execute a javascript library slideUp action
	 *
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function slideUp($element = 'this', $speed = '', $callback = '', $immediatly = false) {
		return $this->_showHideWithEffect ( "slideUp", $element, $speed, $callback, $immediatly );
	}

	/**
	 * Execute a javascript library removeClass action
	 *
	 * @param string $element
	 *        	element
	 * @param string $class
	 *        	Class to add
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function removeClass($element = 'this', $class = '', $immediatly = false) {
		return $this->_genericCallValue ( 'removeClass', $element, $class, $immediatly );
	}

	/**
	 * Execute a javascript library slideDown action
	 *
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function slideDown($element = 'this', $speed = '', $callback = '', $immediatly = false) {
		return $this->_showHideWithEffect ( "slideDown", $element, $speed, $callback, $immediatly );
	}

	/**
	 * Execute a javascript library slideToggle action
	 *
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function slideToggle($element = 'this', $speed = '', $callback = '', $immediatly = false) {
		return $this->_showHideWithEffect ( "slideToggle", $element, $speed, $callback, $immediatly );
	}

	/**
	 * Execute a javascript library hide action
	 *
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function hide($element = 'this', $speed = '', $callback = '', $immediatly = false) {
		return $this->_showHideWithEffect ( "hide", $element, $speed, $callback, $immediatly );
	}

	/**
	 * Execute a javascript library toggle action
	 *
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function toggle($element = 'this', $speed = '', $callback = '', $immediatly = false) {
		return $this->_showHideWithEffect ( "toggle", $element, $speed, $callback, $immediatly );
	}

	/**
	 * Execute a javascript library toggle class action
	 *
	 * @param string $element
	 *        	element
	 * @param string $class
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function toggleClass($element = 'this', $class = '', $immediatly = false) {
		return $this->_genericCallValue ( 'toggleClass', $element, $class, $immediatly );
	}

	/**
	 * Execute all handlers and behaviors attached to the matched elements for the given event.
	 *
	 * @param string $element
	 * @param string $event
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 */
	public function trigger($element = 'this', $event = 'click', $immediatly = false) {
		$element = Javascript::prep_element ( $element );
		$str = "$({$element}).trigger(\"$event\");";

		if ($immediatly)
			$this->jquery_code_for_compile [] = $str;
		return $str;
	}

	/**
	 * Execute a javascript library show action
	 *
	 * @param string $element
	 *        	element
	 * @param string $speed
	 *        	One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback
	 *        	Javascript callback function
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 * @return string
	 */
	public function show($element = 'this', $speed = '', $callback = '', $immediatly = false) {
		return $this->_showHideWithEffect ( "show", $element, $speed, $callback, $immediatly );
	}

	/**
	 * Creates a jQuery sortable
	 *
	 * @param string $element
	 * @param array $options
	 * @return string
	 */
	public function sortable($element, $options = array ()) {
		if (count ( $options ) > 0) {
			$sort_options = array ();
			foreach ( $options as $k => $v ) {
				$sort_options [] = "\n\t\t" . $k . ': ' . $v . "";
			}
			$sort_options = implode ( ",", $sort_options );
		} else {
			$sort_options = '';
		}

		return "$(" . Javascript::prep_element ( $element ) . ").sortable({" . $sort_options . "\n\t});";
	}

	/**
	 * Table Sorter Plugin
	 *
	 * @param string $table
	 *        	table name
	 * @param string $options
	 *        	plugin location
	 * @return string
	 */
	public function tablesorter($table = '', $options = '') {
		$this->jquery_code_for_compile [] = "\t$(" . Javascript::prep_element ( $table ) . ").tablesorter($options);\n";
	}

	/**
	 * Allows to attach a condition
	 *
	 * @param string $condition
	 * @param string $jsCodeIfTrue
	 * @param string $jsCodeIfFalse
	 * @param boolean $immediatly
	 *        	defers the execution if set to false
	 */
	public function condition($condition, $jsCodeIfTrue, $jsCodeIfFalse = null, $immediatly = false) {
		$str = "if(" . $condition . "){" . $jsCodeIfTrue . "}";
		if (isset ( $jsCodeIfFalse )) {
			$str .= "else{" . $jsCodeIfFalse . "}";
		}

		if ($immediatly)
			$this->jquery_code_for_compile [] = $str;
		return $str;
	}

	/**
	 * Call the JQuery method $jqueryCall on $element with parameters $param
	 *
	 * @param string $element
	 * @param string $jqueryCall
	 * @param mixed $param
	 * @param string $jsCallback
	 *        	javascript code to execute after the jquery call
	 * @param boolean $immediatly
	 * @return string
	 */
	private function _doJQuery($element, $jqueryCall, $param = "", $jsCallback = "", $immediatly = false) {
		$param = Javascript::prep_value ( $param );
		$callback = "";
		if ($jsCallback != "")
			$callback = ", function(event){\n{$jsCallback}\n}";
		$script = "$(" . Javascript::prep_element ( $element ) . ")." . $jqueryCall . "(" . $param . $callback . ");\n";
		if ($immediatly)
			$this->jquery_code_for_compile [] = $script;
		return $script;
	}

	/**
	 * Calls the JQuery callback $someThing on $element with facultative parameter $param
	 *
	 * @param string $element
	 *        	the element
	 * @param string $jqueryCall
	 *        	the JQuery callback
	 * @param mixed $param
	 *        	array or string parameters
	 * @param string $jsCallback
	 *        	javascript code to execute after the jquery call
	 * @return mixed
	 */
	public function doJQuery($element, $jqueryCall, $param = "", $jsCallback = "") {
		return $this->_doJQuery ( $element, $jqueryCall, $param, $jsCallback, true );
	}

	/**
	 * Calls the JQuery callback $someThing on $element with facultative parameter $param
	 *
	 * @param string $element
	 *        	the element
	 * @param string $jqueryCall
	 *        	the JQuery callback
	 * @param mixed $param
	 *        	array or string parameters
	 * @param string $jsCallback
	 *        	javascript code to execute after the jquery call
	 * @return mixed
	 */
	public function doJQueryDeferred($element, $jqueryCall, $param = "", $jsCallback = "") {
		return $this->_doJQuery ( $element, $jqueryCall, $param, $jsCallback, false );
	}

	/**
	 *
	 * @param string $event
	 * @param string $element
	 * @param string $elementToModify
	 * @param string $jqueryCall
	 * @param string|array $param
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param string $jsCallback
	 *        	javascript code to execute after the jquery call
	 * @param boolean $immediatly
	 * @return string
	 */
	private function _doJQueryOn($event, $element, $elementToModify, $jqueryCall, $param = "", $preventDefault = false, $stopPropagation = false, $jsCallback = "", $immediatly = true) {
		return $this->_add_event ( $element, $this->_doJQuery ( $elementToModify, $jqueryCall, $param, $jsCallback ), $event, $preventDefault, $stopPropagation, $immediatly );
	}

	/**
	 * Calls the JQuery callback $jqueryCall on $element with facultative parameter $param in response to an event $event
	 *
	 * @param string $event
	 * @param string $element
	 * @param string $elementToModify
	 * @param string $jqueryCall
	 * @param string $param
	 * @param array $parameters
	 *        	default : array("preventDefault"=>false,"stopPropagation"=>false,"jsCallback"=>'',"immediatly"=>true)
	 */
	public function doJQueryOn($event, $element, $elementToModify, $jqueryCall, $param = "", $parameters = array ()) {
		$jsCallback = "";
		$stopPropagation = false;
		$preventDefault = false;
		$immediatly = true;
		extract ( $parameters );
		return $this->_doJQueryOn ( $event, $element, $elementToModify, $jqueryCall, $param, $preventDefault, $stopPropagation, $jsCallback, $immediatly );
	}

	/**
	 * Executes the code $js
	 *
	 * @param string $js
	 *        	Code to execute
	 * @param boolean $immediatly
	 *        	delayed if false
	 * @return String
	 */
	public function exec($js, $immediatly = false) {
		$script = $js . "\n";
		if ($immediatly)
			$this->jquery_code_for_compile [] = $script;
		return $script;
	}

	/**
	 * Executes the code $js
	 *
	 * @param string $js
	 *        	Code to execute
	 * @param boolean $immediatly
	 *        	delayed if false
	 * @return String
	 */
	public function execAtLast($js) {
		$script = $js . "\n";
		$this->jquery_code_for_compile_at_last [] = $script;
		return $script;
	}

	/**
	 * Executes the javascript code $js when $event fires on $element
	 *
	 * @param string $event
	 * @param string $element
	 * @param string $js
	 *        	Code to execute
	 * @param array $parameters
	 *        	default : array("preventDefault"=>false,"stopPropagation"=>false,"immediatly"=>true)
	 * @return String
	 */
	public function execOn($event, $element, $js, $parameters = array ()) {
		$stopPropagation = false;
		$preventDefault = false;
		$immediatly = true;
		extract ( $parameters );
		$script = $this->_add_event ( $element, $this->exec ( $js ), $event, $preventDefault, $stopPropagation, $immediatly );
		return $script;
	}
	public function setJsonToElement($json, $elementClass = "_element", $immediatly = true) {
		$retour = "var data={$json};" . "\n\tdata=($.isPlainObject(data))?data:JSON.parse(data);" . "\n\tvar pk=data['pk'];var object=data['object'];" . "\n\tfor(var field in object){" . "\n\tif($('[data-field='+field+']',$('._element[data-ajax='+pk+']')).length){" . "\n\t\t$('[data-field='+field+']',$('._element[data-ajax='+pk+']')).each(function(){" . "\n\t\t\tif($(this).is('[value]')) { $(this).val(object[field]);} else { $(this).html(object[field]); }" . "\n\t});" . "\n}};\n";
		$retour .= "\t$(document).trigger('jsonReady',[data]);\n";
		return $this->exec ( $retour, $immediatly );
	}

	/**
	 * Sets an element draggable (HTML5 drag and drop)
	 *
	 * @param string $element
	 *        	The element selector
	 * @param array $parameters
	 *        	default : array("attr"=>"id","preventDefault"=>false,"stopPropagation"=>false,"immediatly"=>true)
	 */
	public function setDraggable($element, $parameters = [ ]) {
		$attr = "id";
		$preventDefault = false;
		$stopPropagation = false;
		$immediatly = true;
		extract ( $parameters );
		$script = $this->_add_event ( $element, Javascript::draggable ( $attr ), "dragstart", $preventDefault, $stopPropagation, $immediatly );
		return $script;
	}

	/**
	 * Declares an element as a drop zone (HTML5 drag and drop)
	 *
	 * @param string $element
	 *        	The element selector
	 * @param array $parameters
	 *        	default : array("attr"=>"id","stopPropagation"=>false,"immediatly"=>true,"jqueryDone"=>"append")
	 * @param string $jsCallback
	 *        	the js script to call when element is dropped
	 */
	public function asDropZone($element, $jsCallback = "", $parameters = [ ]) {
		$stopPropagation = false;
		$immediatly = true;
		$jqueryDone = "append";
		$script = $this->_add_event ( $element, '', "dragover", true, $stopPropagation, $immediatly );
		extract ( $parameters );
		$script .= $this->_add_event ( $element, Javascript::dropZone ( $jqueryDone, $jsCallback ), "drop", true, $stopPropagation, $immediatly );
		return $script;
	}

	/**
	 * Calls a function or evaluates an expression at specified intervals (in milliseconds)
	 *
	 * @param string $jsCode
	 *        	The code to execute
	 * @param int $time
	 *        	The time interval in milliseconds
	 * @param string $globalName
	 *        	The global name of the interval, used to clear it
	 * @param boolean $immediatly
	 *        	delayed if false
	 * @return string
	 */
	public function interval($jsCode, $time, $globalName = null, $immediatly = true) {
		if (! Javascript::isFunction ( $jsCode )) {
			$jsCode = "function(){\n" . $jsCode . "\n}";
		}
		if (isset ( $globalName )) {
			$script = "if(window.{$globalName}){clearInterval(window.{$globalName});}\nwindow.{$globalName}=setInterval({$jsCode},{$time});";
		} else {
			$script = "setInterval({$jsCode},{$time});";
		}
		return $this->exec ( $script, $immediatly );
	}

	/**
	 * Clears an existing interval
	 *
	 * @param string $globalName
	 *        	The interval global name
	 * @param boolean $immediatly
	 *        	delayed if false
	 * @return string
	 */
	public function clearInterval($globalName, $immediatly = true) {
		return $this->exec ( "if(window.{$globalName}){clearInterval(window.{$globalName});}", $immediatly );
	}

	/**
	 * Associates a counter to the element designated by $counterSelector
	 * Triggers the events counter-start and counter-end on finished with the parameters value and limit
	 *
	 * @param string $counterSelector
	 *        	Selector of the existing element wich display the counter
	 * @param integer $value
	 *        	The initial value of the counter
	 * @param integer $limit
	 *        	The limit of the counter (minimum if countDown is true, maximum if not)
	 * @param string $globalName
	 *        	The global name of the counter, to use with the clearInterval method
	 * @param boolean $countDown
	 *        	count down if true or elapse if false
	 * @param boolean $immediatly
	 *        	delayed if false
	 * @return string
	 */
	public function counter($counterSelector, $value = 0, $limit = 0, $globalName = null, $countDown = true, $immediatly = true) {
		$stop = "";
		if ($countDown) {
			$stop = "if (--timer < " . $limit . ") {clearInterval(interval);display.trigger({type:'counter-end',value: timer,limit:" . $limit . "});}";
		} else {
			if ($limit != 0) {
				$stop = "if (++timer > " . $limit . ") {clearInterval(interval);display.trigger({type:'counter-end',value: timer,limit:" . $limit . "});}";
			}
		}
		$global = "";
		if (isset ( $globalName )) {
			$global = "\nwindow.{$globalName}=interval;";
		}
		$timer = "var startTimer=function(duration, display) {var timer = duration, days, hours, minutes, seconds;
											var sh=function(disp,v){if(disp.is('[value]')){disp.val(v);} else {disp.html(v);};};
											var shHide=function(v,k,kBefore){if(v==0 && display.find(k).closest('.timer').is(':visible') && (!kBefore || !display.find(kBefore).closest('.timer').is(':visible'))){display.find(k).closest('.timer').hide();}else{sh(display.find(k),v);}};
											var pl=function(v,text){return (v>1)?v+' '+text+'s':(v>0)?v+' '+text:'';};
											var d0=function(v){return v < 10 ? '0' + v : v;};
											var shortSh=function(d,h,m,s){sh(display,pl(d,'day')+' '+[h,m,s].join(':'));};
											var longSh=function(d,h,m,s){shHide(d,'.day');shHide(h,'.hour','.day');shHide(m,'.minute','.hour');shHide(s,'.second','.minute');};
											var mainSh=(display.find('.hour').first().length)?longSh:shortSh;
											display.trigger('counter-start',timer);
											display.show();
											var interval=setInterval(function () {
												days = parseInt(timer / 86400, 10);
												hours = d0(parseInt((timer%86400) / 3600, 10));
												minutes = d0(parseInt((timer%3600) / 60, 10));
												seconds = d0(parseInt(timer%60, 10));
												mainSh(days,hours,minutes,seconds);
												" . $stop . "
    										}, 1000);
										" . $global . "
										}";
		$element = '$("' . $counterSelector . '")';
		return $this->exec ( $timer . "\nstartTimer(" . $value . "," . $element . ");", $immediatly );
	}

	/**
	 * Associates a counter to the element designated by $counterSelector when $event is triggered on $element
	 *
	 * @param string $element
	 *        	The triggering element
	 * @param string $event
	 *        	The triggering event
	 * @param string $counterSelector
	 *        	Selector of the existing element wich display the counter
	 * @param integer $value
	 *        	The initial value of the counter
	 * @param integer $limit
	 *        	The limit of the counter (minimum if countDown is true, maximum if not)
	 * @param string $globalName
	 *        	The global name of the counter, to use with the clearInterval method
	 * @param boolean $countDown
	 *        	count down if true or elapse if false
	 * @return string
	 */
	public function counterOn($element, $event, $counterSelector, $value = 0, $limit = 0, $globalName = null, $countDown = true) {
		return $this->execOn ( $event, $element, $this->counter ( $counterSelector, $value, $limit, $globalName, $countDown, false ) );
	}

	/**
	 * Activates an element if it is active (add the class active)
	 *
	 * @param string $target
	 *        	the container element
	 * @param string $property
	 *        	default: href
	 * @param string $href
	 *        	the active href (if null, window.location.href is used)
	 * @return string
	 */
	public function activateLink($target, $property = 'href', $href = null) {
		return $this->execAtLast ( $this->_activateLink ( $target, $property, $href ) );
	}

	/**
	 * Returns the javascript code for activate an element if it is active (add the class active)
	 *
	 * @param string $target
	 *        	the container element
	 * @param string $property
	 *        	default: href
	 * @param string $href
	 *        	the active href (if null, window.location.href is used)
	 * @return string
	 */
	public function _activateLink($target, $property = 'href', $href = null) {
		$js = '$("' . $target . ' [' . $property . ']").removeClass("active");';
		if (isset ( $href )) {
			$js .= 'var href="' . $href . '";';
		} else {
			$js .= 'var href=window.location.href;';
		}
		$js .= '$("' . $target . ' [' . $property . ']").each(function(){if(href.includes($(this).attr("' . $property . '"))) $(this).addClass("active");});';
		return $js;
	}
}
