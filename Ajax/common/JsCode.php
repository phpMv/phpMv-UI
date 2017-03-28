<?php

namespace Ajax\common;

/**
 * Base class for js code
 * @author jc
 * @version 1.001
 */
class JsCode {
	protected $mask;

	public function __construct($mask) {
		$this->mask=$mask;
	}

	public function getMask() {
		return $this->mask;
	}

	public function setMask($mask) {
		$this->mask=$mask;
		return $this;
	}

	public function compile($keyAndvalues) {
		$result=$this->mask;
		foreach ( $keyAndvalues as $k => $v ) {
			$result=str_ireplace("%{$k}%", $v, $result);
		}
		return $result;
	}
}
