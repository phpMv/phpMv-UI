<?php

namespace Ajax\common\traits;

trait JsUtilsEventsTrait {

	/**
	 * Outputs a javascript library blur event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function blur($element='this', $js='') {
		return $this->js->_blur($element, $js);
	}

	/**
	 * Outputs a javascript library change event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function change($element='this', $js='') {
		return $this->js->_change($element, $js);
	}

	/**
	 * Outputs a javascript library click event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @param boolean $ret_false or not to return false
	 * @return string
	 */
	public function click($element='this', $js='', $ret_false=TRUE) {
		return $this->js->_click($element, $js, $ret_false);
	}

	/**
	 * Outputs a javascript library contextmenu event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function contextmenu($element='this', $js='') {
		return $this->js->_contextmenu($element, $js);
	}


	/**
	 * Outputs a javascript library dblclick event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function dblclick($element='this', $js='') {
		return $this->js->_dblclick($element, $js);
	}

	/**
	 * Outputs a javascript library error event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function error($element='this', $js='') {
		return $this->js->_error($element, $js);
	}

	/**
	 * Outputs a javascript library focus event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function focus($element='this', $js='') {
		return $this->js->_add_event($element, $js, "focus");
	}

	/**
	 * Outputs a javascript library hover event
	 *
	 * @param string $element
	 * @param string $over code for mouse over
	 * @param string $out code for mouse out
	 * @return string
	 */
	public function hover($element='this', $over, $out) {
		return $this->js->_hover($element, $over, $out);
	}

	/**
	 * Outputs a javascript library keydown event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function keydown($element='this', $js='') {
		return $this->js->_keydown($element, $js);
	}

	/**
	 * Outputs a javascript library keypress event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function keypress($element='this', $js='') {
		return $this->js->_keypress($element, $js);
	}

	/**
	 * Outputs a javascript library keydown event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function keyup($element='this', $js='') {
		return $this->js->_keyup($element, $js);
	}

	/**
	 * Outputs a javascript library load event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function load($element='this', $js='') {
		return $this->js->_load($element, $js);
	}

	/**
	 * Outputs a javascript library mousedown event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function mousedown($element='this', $js='') {
		return $this->js->_mousedown($element, $js);
	}

	/**
	 * Outputs a javascript library mouseout event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function mouseout($element='this', $js='') {
		return $this->js->_mouseout($element, $js);
	}
	/**
	 * Outputs a javascript library mouseover event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function mouseover($element='this', $js='') {
		return $this->js->_mouseover($element, $js);
	}

	/**
	 * Outputs a javascript library mouseup event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function mouseup($element='this', $js='') {
		return $this->js->_mouseup($element, $js);
	}


	/**
	 * Outputs a javascript library unload event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function unload($element='this', $js='') {
		return $this->js->_unload($element, $js);
	}

	// --------------------------------------------------------------------
	/**
	 * Outputs a javascript library resize event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function resize($element='this', $js='') {
		return $this->js->_resize($element, $js);
	}
	// --------------------------------------------------------------------
	/**
	 * Outputs a javascript library scroll event
	 *
	 * @param string $element element to attach the event to
	 * @param string $js code to execute
	 * @return string
	 */
	public function scroll($element='this', $js='') {
		return $this->js->_scroll($element, $js);
	}
}