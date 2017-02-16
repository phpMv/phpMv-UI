<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\base\constants\Side;
use Ajax\semantic\html\base\HtmlSemDoubleElement;

trait AttachedTrait {
	abstract public function addToPropertyCtrl($name, $value, $typeCtrl);
	/**
	 * @param HtmlSemDoubleElement $toElement
	 * @param string $side
	 * @return \Ajax\semantic\html\base\HtmlSemDoubleElement
	 */
	public function setAttachment($toElement, $side=Side::BOTH) {
		if (isset($toElement)) {
			$toElement->setAttached(true);
		}
		return $this->addToPropertyCtrl("class", $side . " attached", Side::getConstantValues("attached"));
	}
}