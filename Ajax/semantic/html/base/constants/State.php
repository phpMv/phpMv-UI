<?php

namespace Ajax\semantic\html\base\constants;

use Ajax\common\BaseEnum;
use Ajax\common\html\BaseHtml;

abstract class State extends BaseEnum {
	const ACTIVE="active", DISABLED="disabled", ERROR="error", FOCUS="focus", LOADING="loading", NEGATIVE="negative", POSITIVE="positive", SUCCESS="success", WARNING="warning";

	public static function add($state, $elements) {
		if (!\is_array($state)) {
			$state=\explode(" ", $state);
		}
		if (\is_array($elements)) {
			foreach ( $elements as $element ) {
				if ($element instanceof BaseHtml) {
					self::_add($state, $element);
				}
			}
		}
	}

	private static function _add($states, $element) {
		foreach ( $states as $state ) {
			$element->addToPropertyCtrl("class", $state, array ($state ));
		}
	}
}
