<?php

namespace Ajax\common\traits;

trait JqueryActionsTrait {
	public abstract function _add_event($element, $js, $event, $preventDefault=false, $stopPropagation=false,$immediatly=true);
	public abstract function _prep_element($element);
	public abstract function _prep_value($value);

	/**
	 * Get or set the value of an attribute for the first element in the set of matched elements or set one or more attributes for every matched element.
	 * @param string $element
	 * @param string $attributeName
	 * @param string $value
	 * @param boolean $immediatly delayed if false
	 */
	public function _attr($element='this', $attributeName, $value="", $immediatly=false) {
		$element=$this->_prep_element($element);
		if (isset($value)) {
			$value=$this->_prep_value($value);
			$str="$({$element}).attr(\"$attributeName\",{$value});";
		} else
			$str="$({$element}).attr(\"$attributeName\");";
			if ($immediatly)
				$this->jquery_code_for_compile[]=$str;
				return $str;
	}

	/**
	 * Insert content, specified by the parameter, after each element in the set of matched elements
	 * @param string $element
	 * @param string $value
	 * @param boolean $immediatly defers the execution if set to false
	 * @return string
	 */
	public function after($element='this', $value='', $immediatly=false){
		$element=$this->_prep_element($element);
		$value=$this->_prep_value($value);
		$str="$({$element}).after({$value});";
		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	/**
	 * Execute a jQuery animate action
	 *
	 * @param string $element element
	 * @param string|array $params One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $speed
	 * @param string $extra
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _animate($element='this', $params=array(), $speed='', $extra='', $immediatly=false) {
		$element=$this->_prep_element($element);
		$speed=$this->_validate_speed($speed);

		$animations="\t\t\t";
		if (is_array($params)) {
			foreach ( $params as $param => $value ) {
				$animations.=$param.': \''.$value.'\', ';
			}
		}
		$animations=substr($animations, 0, -2); // remove the last ", "

		if ($speed!='') {
			$speed=', '.$speed;
		}

		if ($extra!='') {
			$extra=', '.$extra;
		}

		$str="$({$element}).animate({\n$animations\n\t\t}".$speed.$extra.");";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Execute a jQuery hide action
	 *
	 * @param string $element element
	 * @param string $speed One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback Javascript callback function
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _fadeIn($element='this', $speed='', $callback='', $immediatly=false) {
		$element=$this->_prep_element($element);
		$speed=$this->_validate_speed($speed);

		if ($callback!='') {
			$callback=", function(){\n{$callback}\n}";
		}

		$str="$({$element}).fadeIn({$speed}{$callback});";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Execute a jQuery fadeOut action
	 *
	 * @param string $element element
	 * @param string $speed One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback Javascript callback function
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _fadeOut($element='this', $speed='', $callback='', $immediatly=false) {
		$element=$this->_prep_element($element);
		$speed=$this->_validate_speed($speed);

		if ($callback!='') {
			$callback=", function(){\n{$callback}\n}";
		}

		$str="$({$element}).fadeOut({$speed}{$callback});";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Execute a jQuery hide action
	 *
	 * @param string $element element
	 * @param string $speed One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback Javascript callback function
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _hide($element='this', $speed='', $callback='', $immediatly=false) {
		$element=$this->_prep_element($element);
		$speed=$this->_validate_speed($speed);

		if ($callback!='') {
			$callback=", function(){\n{$callback}\n}";
		}

		$str="$({$element}).hide({$speed}{$callback});";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	// --------------------------------------------------------------------

	/**
	 * Execute a jQuery slideUp action
	 *
	 * @param string $element element
	 * @param string $speed One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback Javascript callback function
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _slideUp($element='this', $speed='', $callback='', $immediatly=false) {
		$element=$this->_prep_element($element);
		$speed=$this->_validate_speed($speed);

		if ($callback!='') {
			$callback=", function(){\n{$callback}\n}";
		}

		$str="$({$element}).slideUp({$speed}{$callback});";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Execute a jQuery slideDown action
	 *
	 * @param string $element element
	 * @param string $speed One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback Javascript callback function
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _slideDown($element='this', $speed='', $callback='', $immediatly=false) {
		$element=$this->_prep_element($element);
		$speed=$this->_validate_speed($speed);

		if ($callback!='') {
			$callback=", function(){\n{$callback}\n}";
		}

		$str="$({$element}).slideDown({$speed}{$callback});";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Execute a jQuery slideToggle action
	 *
	 * @param string $element element
	 * @param string $speed One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback Javascript callback function
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _slideToggle($element='this', $speed='', $callback='', $immediatly=false) {
		$element=$this->_prep_element($element);
		$speed=$this->_validate_speed($speed);

		if ($callback!='') {
			$callback=", function(){\n{$callback}\n}";
		}

		$str="$({$element}).slideToggle({$speed}{$callback});";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery toggle event
	 *
	 * @param string $element element
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _toggle($element='this', $immediatly=false) {
		$element=$this->_prep_element($element);
		$str="$({$element}).toggle();";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Execute all handlers and behaviors attached to the matched elements for the given event.
	 * @param string $element
	 * @param string $event
	 * @param boolean $immediatly delayed if false
	 */
	public function _trigger($element='this', $event='click', $immediatly=false) {
		$element=$this->_prep_element($element);
		$str="$({$element}).trigger(\"$event\");";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// --------------------------------------------------------------------

	/**
	 * Execute a jQuery show action
	 *
	 * @param string $element element
	 * @param string $speed One of 'slow', 'normal', 'fast', or time in milliseconds
	 * @param string $callback Javascript callback function
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _show($element='this', $speed='', $callback='', $immediatly=false) {
		$element=$this->_prep_element($element);
		$speed=$this->_validate_speed($speed);

		if ($callback!='') {
			$callback=", function(){\n{$callback}\n}";
		}

		$str="$({$element}).show({$speed}{$callback});";

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	/**
	 * Places a condition
	 * @param string $condition
	 * @param string $jsCodeIfTrue
	 * @param string $jsCodeIfFalse
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _condition($condition, $jsCodeIfTrue, $jsCodeIfFalse=null, $immediatly=false) {
		$str="if(".$condition."){".$jsCodeIfTrue."}";
		if (isset($jsCodeIfFalse)) {
			$str.="else{".$jsCodeIfFalse."}";
		}

		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	// ------------------------------------------------------------------------
	/**
	 * Call the JQuery method $jqueryCall on $element with parameters $param
	 * @param string $element
	 * @param string $jqueryCall
	 * @param mixed $param
	 * @param string $jsCallback javascript code to execute after the jquery call
	 * @param boolean $immediatly
	 * @return string
	 */
	public function _doJQuery($element, $jqueryCall, $param="", $jsCallback="", $immediatly=false) {
		$param=$this->_prep_value($param);
		$callback="";
		if ($jsCallback!="")
			$callback=", function(event){\n{$jsCallback}\n}";
			$script="$(".$this->_prep_element($element).").".$jqueryCall."(".$param.$callback.");\n";
			if ($immediatly)
				$this->jquery_code_for_compile[]=$script;
				return $script;
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
	 * @param string $jsCallback javascript code to execute after the jquery call
	 * @param boolean $immediatly
	 * @return string
	 */
	public function _doJQueryOn($event, $element, $elementToModify, $jqueryCall, $param="", $preventDefault=false, $stopPropagation=false, $jsCallback="",$immediatly=true) {
		return $this->_add_event($element, $this->_doJQuery($elementToModify, $jqueryCall, $param, $jsCallback), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Execute the code $js
	 * @param string $js Code to execute
	 * @param boolean $immediatly diffère l'exécution si false
	 * @return String
	 */
	public function _exec($js, $immediatly=false) {
		$script=$js."\n";
		if ($immediatly)
			$this->jquery_code_for_compile[]=$script;
			return $script;
	}

	/**
	 *
	 * @param string $element
	 * @param string $event
	 * @param string $js Code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean $immediatly
	 * @return String
	 */
	public function _execOn($element, $event, $js, $preventDefault=false, $stopPropagation=false,$immediatly=true) {
		return $this->_add_event($element, $this->_exec($js), $event, $preventDefault, $stopPropagation,$immediatly);
	}

	/**
	 * Ensures the speed parameter is valid for jQuery
	 * @param string|int $speed
	 * @return string
	 */
	private function _validate_speed($speed) {
		if (in_array($speed, array (
				'slow','normal','fast'
		))) {
			$speed='"'.$speed.'"';
		} elseif (preg_match("/[^0-9]/", $speed)) {
			$speed='';
		}

		return $speed;
	}
}