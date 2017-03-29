<?php

namespace Ajax\common\html\html5;

trait HtmlLinkTrait {

	abstract public function setProperty($name,$value);
	abstract public function getProperty($name);

	public function setHref($value) {
		$this->setProperty("href", $value);
	}

	public function getHref() {
		return $this->getProperty("href");
	}

	public function setTarget($value="_self") {
		return $this->setProperty("target", $value);
	}
}
