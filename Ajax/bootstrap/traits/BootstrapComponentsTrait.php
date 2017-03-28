<?php

namespace Ajax\bootstrap\traits;

use Ajax\common\components\GenericComponent;
use Ajax\bootstrap\components\Modal;
use Ajax\bootstrap\components\Tooltip;
use Ajax\bootstrap\components\Popover;
use Ajax\bootstrap\components\Dropdown;
use Ajax\bootstrap\components\Splitbutton;
use Ajax\bootstrap\components\Tab;
use Ajax\bootstrap\components\Carousel;
use Ajax\bootstrap\components\Collapse;
use Ajax\Bootstrap;
use Ajax\common\components\SimpleComponent;

/**
 * @property \Ajax\JsUtils $js
 */

trait BootstrapComponentsTrait {

	/**
	 * @param SimpleComponent $component
	 * @param string|null $attachTo $attachTo
	 * @param array|null|string $params
	 * @return SimpleComponent
	 */
	abstract public function addComponent(SimpleComponent $component, $attachTo, $params);
	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return GenericComponent
	 */
	public function generic($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new GenericComponent($this->js), $attachTo, $params);
	}

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Modal
	 */
	public function modal($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Modal($this->js), $attachTo, $params);
	}

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Tooltip
	 */
	public function tooltip($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Tooltip($this->js), $attachTo, $params);
	}

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Popover
	 */
	public function popover($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Popover($this->js), $attachTo, $params);
	}

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Dropdown
	 */
	public function dropdown($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Dropdown($this->js), $attachTo, $params);
	}

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Splitbutton
	 */
	public function splitbutton($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Splitbutton($this->js), $attachTo, $params);
	}

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Tab
	 */
	public function tab($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Tab($this->js), $attachTo, $params);
	}

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Collapse
	 */
	public function collapse($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Collapse($this->js), $attachTo, $params);
	}

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Carousel
	 */
	public function carousel($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Carousel($this->js), $attachTo, $params);
	}
}
