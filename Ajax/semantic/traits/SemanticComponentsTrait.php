<?php

namespace Ajax\semantic\traits;

use Ajax\common\components\GenericComponent;
use Ajax\semantic\components\Popup;
use Ajax\semantic\components\Dropdown;
use Ajax\semantic\components\Accordion;
use Ajax\common\components\SimpleComponent;
use Ajax\semantic\components\Sticky;
use Ajax\semantic\components\Checkbox;
use Ajax\semantic\components\Rating;
use Ajax\semantic\components\Progress;
use Ajax\semantic\components\Search;
use Ajax\semantic\components\Dimmer;
use Ajax\semantic\components\Modal;

trait SemanticComponentsTrait {

	public abstract function addComponent(SimpleComponent $component, $attachTo, $params);

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
	 * @return Popup
	 */
	public function popup($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Popup($this->js), $attachTo, $params);
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
	 * @return Accordion
	 */
	public function accordion($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Accordion($this->js), $attachTo, $params);
	}

	public function sticky($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Sticky($this->js), $attachTo, $params);
	}

	public function checkbox($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Checkbox($this->js), $attachTo, $params);
	}

	public function rating($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Rating($this->js), $attachTo, $params);
	}

	public function progress($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Progress($this->js), $attachTo, $params);
	}

	public function search($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Search($this->js), $attachTo, $params);
	}

	public function dimmer($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Dimmer($this->js), $attachTo, $params);
	}

	public function modal($attachTo=NULL, $params=NULL,$paramsParts=NULL) {
		$result= $this->addComponent(new Modal($this->js), $attachTo, $params);
		$result->setParamParts($paramsParts);
		return $result;
	}
}