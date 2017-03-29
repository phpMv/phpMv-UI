<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\base\constants\Side;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\common\html\HtmlDoubleElement;

trait AttachedTrait {
	abstract public function addToPropertyCtrl($name, $value, $typeCtrl);
	/**
	 * @param HtmlSemDoubleElement $toElement
	 * @param string $side
	 * @return HtmlSemDoubleElement
	 */
	public function setAttachment(HtmlDoubleElement $toElement=NULL, $side=Side::BOTH) {
		if (isset($toElement) && \method_exists($toElement, "setAttached")) {
			$toElement->setAttached(true);
		}
		return $this->addToPropertyCtrl("class", $side . " attached", Side::getConstantValues("attached"));
	}
}
