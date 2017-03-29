<?php

namespace Ajax\ui\Properties;

use Ajax\common\components\BaseComponent;

/**
 * Composant JQuery UI Animation property
 * @author jc
 * @version 1.001
 */
class Animation extends BaseComponent {

	public function __construct($duration=400, $easing="swing", $queue=false) {
		$this->setDuration($duration);
		$this->setEasing($easing);
		$this->setQueue($queue);
	}

	/**
	 * An int determining how long the animation will run (in milliseconds).
	 * @param int $value default : 400
	 */
	public function setDuration($value) {
		$this->setParamCtrl("duration", $value, "is_int");
	}

	/**
	 * A string indicating which easing function to use for the transition.
	 * @param string $value default : swing
	 */
	public function setEasing($value) {
		$this->setParamCtrl("easing", $value, array (
				"linear",
				"swing",
				"easeInQuad",
				"easeOutQuad",
				"easeInOutQuad",
				"easeInCubic",
				"easeOutCubic",
				"easeInOutCubic",
				"easeInQuart",
				"easeOutQuart",
				"easeInOutQuart",
				"easeInQuint",
				"easeOutQuint",
				"easeInOutQuint",
				"easeInExpo",
				"easeOutExpo",
				"easeInOutExpo",
				"easeInSine",
				"easeOutSine",
				"easeInOutSine",
				"easeInCirc",
				"easeOutCirc",
				"easeInOutCirc",
				"easeInElastic",
				"easeOutElastic",
				"easeInOutElastic",
				"easeInBack",
				"easeOutBack",
				"easeInOutBack",
				"easeInBounce",
				"easeOutBounce",
				"easeInOutBounce"
		));
	}

	/**
	 * A Boolean indicating whether to place the animation in the effects queue.
	 * If false, the animation will begin immediately.
	 * @param Boolean $value default : true
	 */
	public function setQueue($value) {
		$this->setParamCtrl("queue", $value, "is_bool");
	}

	/*
	 * (non-PHPdoc)
	 * @see \Ajax\common\BaseComponent::getScript()
	 */
	public function getScript() {
	}
}
