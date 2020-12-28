<?php

namespace Ajax\common\traits;

use Ajax\service\Javascript;

/**
 *
 * @author jc
 * @property array $jquery_code_for_compile
 */
trait JsUtilsEventsTrait {
	protected $jquery_events = [
			'bind',
			'blur',
			'change',
			'click',
			'dblclick',
			'delegate',
			'die',
			'error',
			'focus',
			'focusin',
			'focusout',
			'hover',
			'keydown',
			'keypress',
			'keyup',
			'live',
			'load',
			'mousedown',
			'mousseenter',
			'mouseleave',
			'mousemove',
			'mouseout',
			'mouseover',
			'mouseup',
			'off',
			'on',
			'one',
			'ready',
			'resize',
			'scroll',
			'select',
			'submit',
			'toggle',
			'trigger',
			'triggerHandler',
			'undind',
			'undelegate',
			'unload'
	];

	abstract public function _add_event($element, $js, $event, $preventDefault = false, $stopPropagation = false, $immediatly = true, $listenerOn=false);

	/**
	 * Outputs a javascript library blur event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function blur($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'blur', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library change event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler 
	 * @return string
	 */
	public function change($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'change', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library click event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string|array $js
	 *        	code to execute
	 * @param boolean $ret_false
	 *        	or not to return false
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function click($element = 'this', $js = '', $ret_false = TRUE, $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		if (! is_array ( $js )) {
			$js = array (
					$js
			);
		}

		if ($ret_false) {
			$js [] = "return false;";
		}

		return $this->_add_event ( $element, $js, 'click', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library contextmenu event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function contextmenu($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'contextmenu', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library dblclick event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function dblclick($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'dblclick', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library error event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function error($element = 'this', $js = '') {
		return $this->_add_event ( $element, $js, 'error' );
	}

	/**
	 * Outputs a javascript library focus event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function focus($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'focus', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library hover event
	 *
	 * @param string $element
	 * @param string $over
	 *        	code for mouse over
	 * @param string $out
	 *        	code for mouse out
	 * @return string
	 */
	public function hover($element = 'this', $over = '', $out = '') {
		$event = "\n\t$(" . Javascript::prep_element ( $element ) . ").hover(\n\t\tfunction()\n\t\t{\n\t\t\t{$over}\n\t\t}, \n\t\tfunction()\n\t\t{\n\t\t\t{$out}\n\t\t});\n";
		$this->jquery_code_for_compile [] = $event;
		return $event;
	}

	/**
	 * Outputs a javascript library keydown event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function keydown($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'keydown', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library keypress event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function keypress($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'keypress', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library keydown event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function keyup($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'keyup', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library load event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function load($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'load', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library mousedown event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function mousedown($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'mousedown', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library mouseout event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function mouseout($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'mouseout', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library mouseleave event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function mouseleave($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'mouseleave', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library mouseenter event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function mouseenter($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'mouseenter', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library mouseover event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function mouseover($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'mouseover', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library mouseup event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function mouseup($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'mouseup', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	/**
	 * Outputs a javascript library unload event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function unload($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'unload', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	// --------------------------------------------------------------------
	/**
	 * Outputs a javascript library resize event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function resize($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'resize', $preventDefault, $stopPropagation, true, $listenerOn );
	}

	// --------------------------------------------------------------------
	/**
	 * Outputs a javascript library scroll event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @param boolean $preventDefault
	 * @param boolean $stopPropagation
	 * @param boolean|string $listenerOn use a selector for a delegated event handler
	 * @return string
	 */
	public function scroll($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false, $listenerOn=false) {
		return $this->_add_event ( $element, $js, 'scroll', $preventDefault, $stopPropagation, true, $listenerOn );
	}
}
