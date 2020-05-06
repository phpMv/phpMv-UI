<?php

namespace Ajax\config;

class Config {
	protected $vars;

	public function __construct($vars) {
		$this->vars=$vars;
	}

	public function setVar($name, $values) {
		$this->vars [$name]=$values;
		return $this;
	}

	public function getVar($name) {
		return $this->vars [$name];
	}

	public function getVars() {
		return $this->vars;
	}

	public function setVars($values) {
		$this->vars=$values;
		return $this;
	}

	public function addVars($values) {
		$this->vars=array_merge($this->vars, $values);
		return $this;
	}
}
