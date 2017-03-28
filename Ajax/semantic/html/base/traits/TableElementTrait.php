<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\base\constants\State;

trait TableElementTrait {

	abstract public function addState($state);

	public function setPositive() {
		return $this->addState(State::POSITIVE);
	}

	public function setNegative() {
		return $this->addState(State::NEGATIVE);
	}

	public function setWarning() {
		return $this->addState(State::WARNING);
	}

	public function setError() {
		return $this->addState(State::ERROR);
	}

	public function setDisabled($disable=true) {
		if($disable)
			$this->addState(State::DISABLED);
		return $this;
	}
}
