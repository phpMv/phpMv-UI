<?php

namespace Ajax\common\html;

use Ajax\service\JString;

/**
 * BaseWidget for Twitter Bootstrap, jQuery UI or Semantic rich components
 * @author jc
 * @version 1.001
 */
abstract class BaseWidget {
	protected $identifier;
	protected $_identifier;
	protected $_self;

	public function __construct($identifier) {
		$this->identifier=$this->cleanIdentifier($identifier);
		$this->_identifier=$this->identifier;
		$this->_self=$this;
	}

	public function getIdentifier() {
		return $this->identifier;
	}

	public function setIdentifier($identifier) {
		$this->identifier=$this->cleanIdentifier($identifier);
		return $this;
	}

	protected function cleanIdentifier($id) {
		return JString::cleanIdentifier($id);
	}
}
