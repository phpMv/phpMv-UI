<?php

namespace Ajax\bootstrap\components;

use Ajax\JsUtils;
use Ajax\common\components\SimpleExtComponent;

/**
 * Composant Twitter Bootstrap Carousel
 * @author jc
 * @version 1.001
 */
class Carousel extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="carousel";
		$this->setInterval(2000);
	}

	public function attach($identifier) {
		parent::attach($identifier);
	}

	/**
	 * The amount of time to delay between automatically cycling an item.
	 * If false, carousel will not automatically cycle.
	 * @param int $value
	 * @return \Ajax\bootstrap\components\Carousel default : 5000
	 */
	public function setInterval($value=5000) {
		return $this->setParam("interval", $value);
	}

	/**
	 * Pauses the cycling of the carousel on mouseenter and resumes the cycling of the carousel on mouseleave.
	 * @param string $event
	 * @return \Ajax\bootstrap\components\Carousel default : 'hover'
	 */
	public function setPause($event="hover") {
		return $this->setParam("pause", $event);
	}

	/**
	 * Whether the carousel should cycle continuously or have hard stops.
	 * @param string $value
	 * @return \Ajax\bootstrap\components\Carousel default : true
	 */
	public function setWrap($value=true) {
		return $this->setParam("wrap", $value);
	}

	/**
	 * Whether the carousel should react to keyboard events.
	 * @param string $value
	 * @return \Ajax\bootstrap\components\Carousel default : true
	 */
	public function setKeyboard($value=true) {
		return $this->setParam("keyboard", $value);
	}

	/**
	 * This event fires immediately when the slide instance method is invoked.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onSlide($jsCode) {
		return $this->addEvent("slide.bs.carousel", $jsCode);
	}

	/**
	 * This event is fired when the carousel has completed its slide transition.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onSlid($jsCode) {
		return $this->addEvent("slid.bs.carousel", $jsCode);
	}
}
