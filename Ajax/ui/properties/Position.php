<?php

namespace Ajax\ui\Properties;

use Ajax\common\components\BaseComponent;

/**
 * Composant JQuery UI Position property
 * @author jc
 * @version 1.001
 */
class Position extends BaseComponent {

	public function __construct($my="left top", $at="left bottom", $collision="none", $within="window") {
		$this->setParam("my", $my);
		$this->setParam("at", $at);
		$this->setParam("collision", $collision);
		$this->setParam("within", $within);
	}

	/**
	 * Defines which position on the element being positioned to align with
	 * the target element: "horizontal vertical" alignment.
	 * A single value such as "right" will be normalized to "right center",
	 * "top" will be normalized to "center top" (following CSS convention).
	 * Acceptable horizontal values: "left", "center", "right".
	 * Acceptable vertical values: "top", "center", "bottom".
	 * Example: "left top" or "center center". Each dimension can also contain offsets,
	 * in pixels or percent, e.g., "right+10 top-25%". Percentage offsets are relative to the element being positioned.
	 * @param string $value default : left top
	 */
	public function setMy($value) {
		$this->setParamCtrl("my", $value, "is_string");
	}

	/**
	 * Defines which position on the target element to align the positioned element against: "horizontal vertical" alignment.
	 * See the my option for full details on possible values.
	 * Percentage offsets are relative to the target element
	 * @param string $value default : left bottom
	 */
	public function setAt($value) {
		$this->setParamCtrl("at", $value, "is_string");
	}

	/**
	 * Which element to position against.
	 * If you provide a selector or jQuery object, the first matching element will be used.
	 * If you provide an event object, the pageX and pageY properties will be used. Example: "#top-menu"
	 * @param string $value default : null
	 */
	public function setOf($value) {
		$this->setParamCtrl("of", $value, "is_string");
	}

	/**
	 * When the positioned element overflows the window in some direction, move it to an alternative position.
	 * Similar to my and at, this accepts a single value or a pair for horizontal/vertical, e.g., "flip", "fit", "fit flip", "fit none"
	 * @param string $value default : none
	 */
	public function setCollision($value) {
		$this->setParamCtrl("collision", $value, "is_string");
	}

	/**
	 * Element to position within, affecting collision detection.
	 * If you provide a selector or jQuery object, the first matching element will be used.
	 * @param string $value default : window
	 */
	public function setWithin($value) {
		$this->setParamCtrl("within", $value, "is_string");
	}

	protected function setParamCtrl($key, $value, $typeCtrl) {
		if (!$typeCtrl($value)) {
			throw new \Exception("La fonction ".$typeCtrl." a retourné faux pour l'affectation de la propriété ".$key." à la position");
		} else
			$this->setParam($key, $value);
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\common\BaseComponent::getScript()
	 */
	public function getScript() {
	}
}
