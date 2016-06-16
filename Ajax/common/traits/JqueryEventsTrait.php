<?php

namespace Ajax\common\traits;

trait JqueryEventsTrait {
	public abstract function _prep_element($element);
	public abstract function _add_event($element, $js, $event, $preventDefault=false, $stopPropagation=false,$immediatly=true);

	/**
	 * Blur
	 *
	 * Outputs a jQuery blur event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _blur($element='this', $js='') {
		return $this->_add_event($element, $js, 'blur');
	}

	// --------------------------------------------------------------------

	/**
	 * Change
	 *
	 * Outputs a jQuery change event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _change($element='this', $js='') {
		return $this->_add_event($element, $js, 'change');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery click event
	 *
	 * @param string $element The element to attach the event to
	 * @param mixed $js The code to execute
	 * @param boolean $ret_false whether or not to return false
	 * @return string
	 */
	public function _click($element='this', $js=array(), $ret_false=TRUE) {
		if (!is_array($js)) {
			$js=array (
					$js
			);
		}

		if ($ret_false) {
			$js[]="return false;";
		}

		return $this->_add_event($element, $js, 'click');
	}

	/**
	 * Outputs a jQuery contextmenu event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _contextmenu($element='this', $js='') {
		return $this->_add_event($element, $js, 'contextmenu');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery dblclick event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _dblclick($element='this', $js='') {
		return $this->_add_event($element, $js, 'dblclick');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery error event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _error($element='this', $js='') {
		return $this->_add_event($element, $js, 'error');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery focus event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _focus($element='this', $js='') {
		return $this->_add_event($element, $js, 'focus');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery hover event
	 *
	 * @param string - element
	 * @param string - Javascript code for mouse over
	 * @param string - Javascript code for mouse out
	 * @return string
	 */
	public function _hover($element='this', $over, $out) {
		$event="\n\t$(".$this->_prep_element($element).").hover(\n\t\tfunction()\n\t\t{\n\t\t\t{$over}\n\t\t}, \n\t\tfunction()\n\t\t{\n\t\t\t{$out}\n\t\t});\n";

		$this->jquery_code_for_compile[]=$event;

		return $event;
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery keydown event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _keydown($element='this', $js='') {
		return $this->_add_event($element, $js, 'keydown');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery keypress event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _keypress($element='this', $js='') {
		return $this->_add_event($element, $js, 'keypress');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery keydown event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _keyup($element='this', $js='') {
		return $this->_add_event($element, $js, 'keyup');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery load event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _load($element='this', $js='') {
		return $this->_add_event($element, $js, 'load');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery mousedown event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _mousedown($element='this', $js='') {
		return $this->_add_event($element, $js, 'mousedown');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery mouseout event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _mouseout($element='this', $js='') {
		return $this->_add_event($element, $js, 'mouseout');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery mouseover event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _mouseover($element='this', $js='') {
		return $this->_add_event($element, $js, 'mouseover');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery mouseup event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _mouseup($element='this', $js='') {
		return $this->_add_event($element, $js, 'mouseup');
	}

	/**
	 * Outputs a jQuery resize event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _resize($element='this', $js='') {
		return $this->_add_event($element, $js, 'resize');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery scroll event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _scroll($element='this', $js='') {
		return $this->_add_event($element, $js, 'scroll');
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs a jQuery unload event
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _unload($element='this', $js='') {
		return $this->_add_event($element, $js, 'unload');
	}

}