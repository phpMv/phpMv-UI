<?php
namespace Ajax\common\traits;

use Ajax\service\Javascript;

/**
 *
 * @author jc
 * @property array $jquery_code_for_compile
 */
trait JsUtilsEventsTrait {

	protected $jquery_events = array(
		"bind",
		"blur",
		"change",
		"click",
		"dblclick",
		"delegate",
		"die",
		"error",
		"focus",
		"focusin",
		"focusout",
		"hover",
		"keydown",
		"keypress",
		"keyup",
		"live",
		"load",
		"mousedown",
		"mousseenter",
		"mouseleave",
		"mousemove",
		"mouseout",
		"mouseover",
		"mouseup",
		"off",
		"on",
		"one",
		"ready",
		"resize",
		"scroll",
		"select",
		"submit",
		"toggle",
		"trigger",
		"triggerHandler",
		"undind",
		"undelegate",
		"unload"
	);

	abstract public function _add_event($element, $js, $event, $preventDefault = false, $stopPropagation = false, $immediatly = true);

	/**
	 * Outputs a javascript library blur event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function blur($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'blur');
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
	 * @return string
	 */
	public function change($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false) {
		return $this->_add_event($element, $js, 'change', $preventDefault, $stopPropagation);
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
	 * @return string
	 */
	public function click($element = 'this', $js = '', $ret_false = TRUE, $preventDefault = false, $stopPropagation = false) {
		if (! is_array($js)) {
			$js = array(
				$js
			);
		}

		if ($ret_false) {
			$js[] = "return false;";
		}

		return $this->_add_event($element, $js, 'click', $preventDefault, $stopPropagation);
	}

	/**
	 * Outputs a javascript library contextmenu event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function contextmenu($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'contextmenu');
	}

	/**
	 * Outputs a javascript library dblclick event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function dblclick($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'dblclick');
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
		return $this->_add_event($element, $js, 'error');
	}

	/**
	 * Outputs a javascript library focus event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function focus($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'focus');
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
	public function hover($element = 'this', $over, $out) {
		$event = "\n\t$(" . Javascript::prep_element($element) . ").hover(\n\t\tfunction()\n\t\t{\n\t\t\t{$over}\n\t\t}, \n\t\tfunction()\n\t\t{\n\t\t\t{$out}\n\t\t});\n";
		$this->jquery_code_for_compile[] = $event;
		return $event;
	}

	/**
	 * Outputs a javascript library keydown event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function keydown($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'keydown');
	}

	/**
	 * Outputs a javascript library keypress event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function keypress($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'keypress');
	}

	/**
	 * Outputs a javascript library keydown event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function keyup($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'keyup');
	}

	/**
	 * Outputs a javascript library load event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function load($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'load');
	}

	/**
	 * Outputs a javascript library mousedown event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function mousedown($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'mousedown');
	}

	/**
	 * Outputs a javascript library mouseout event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function mouseout($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false) {
		return $this->_add_event($element, $js, 'mouseout', $preventDefault, $stopPropagation);
	}

	/**
	 * Outputs a javascript library mouseleave event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function mouseleave($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false) {
		return $this->_add_event($element, $js, 'mouseleave', $preventDefault, $stopPropagation);
	}

	/**
	 * Outputs a javascript library mouseenter event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function mouseenter($element = 'this', $js = '', $preventDefault = false, $stopPropagation = false) {
		return $this->_add_event($element, $js, 'mouseenter', $preventDefault, $stopPropagation);
	}

	/**
	 * Outputs a javascript library mouseover event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function mouseover($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'mouseover');
	}

	/**
	 * Outputs a javascript library mouseup event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function mouseup($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'mouseup');
	}

	/**
	 * Outputs a javascript library unload event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function unload($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'unload');
	}

	// --------------------------------------------------------------------
	/**
	 * Outputs a javascript library resize event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function resize($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'resize');
	}

	// --------------------------------------------------------------------
	/**
	 * Outputs a javascript library scroll event
	 *
	 * @param string $element
	 *        	element to attach the event to
	 * @param string $js
	 *        	code to execute
	 * @return string
	 */
	public function scroll($element = 'this', $js = '') {
		return $this->_add_event($element, $js, 'scroll');
	}
}
