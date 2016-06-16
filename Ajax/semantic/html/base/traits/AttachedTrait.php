<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\semantic\html\base\constants\Side;
use Ajax\semantic\html\base\HtmlSemDoubleElement;

trait AttachedTrait {

	/**
	 *
	 * @param string $side
	 * @return \Ajax\semantic\html\base\HtmlSemDoubleElement
	 */
	public function setAttachment(HtmlSemDoubleElement $toElement, $value=Side::BOTH) {
		if (isset($toElement)) {
			$toElement->addToPropertyCtrl("class", "attached", array ("attached" ));
		}
		return $this->addToPropertyCtrl("class", $value . " attached", Side::getConstantValues("attached"));
	}
}