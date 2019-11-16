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
use Ajax\semantic\components\Tab;
use Ajax\semantic\components\Shape;
use Ajax\semantic\components\Form;
use Ajax\semantic\components\SimpleSemExtComponent;
use Ajax\semantic\components\Toast;
use Ajax\semantic\components\Slider;

/**
 * @author jc
 * @property \Ajax\JsUtils $js
 */
trait SemanticComponentsTrait {

	/**
	 * @param SimpleComponent $component
	 * @param string|null $attachTo $attachTo
	 * @param array|string|null $params
	 * @return SimpleSemExtComponent
	 */
	abstract public function addComponent(SimpleComponent $component, $attachTo, $params);

	/**
	 *
	 * @param string $attachTo
	 * @param string|array $params
	 * @return SimpleSemExtComponent
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

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Sticky
	 */
	public function sticky($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Sticky($this->js), $attachTo, $params);
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Checkbox
	 */
	public function checkbox($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Checkbox($this->js), $attachTo, $params);
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Rating
	 */
	public function rating($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Rating($this->js), $attachTo, $params);
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Progress
	 */
	public function progress($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Progress($this->js), $attachTo, $params);
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Search
	 */
	public function search($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Search($this->js), $attachTo, $params);
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Dimmer
	 */
	public function dimmer($attachTo=NULL, $params=NULL) {
		return $this->addComponent(new Dimmer($this->js), $attachTo, $params);
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Modal
	 */
	public function modal($attachTo=NULL, $params=NULL,$paramsParts=NULL) {
		$result= $this->addComponent(new Modal($this->js), $attachTo, $params);
		$result->setParamParts($paramsParts);
		return $result;
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Tab
	 */
	public function tab($attachTo=NULL, $params=NULL) {
		$result= $this->addComponent(new Tab($this->js), $attachTo, $params);
		return $result;
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Shape
	 */
	public function shape($attachTo=NULL, $params=NULL) {
		$result= $this->addComponent(new Shape($this->js), $attachTo, $params);
		return $result;
	}

	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Form
	 */
	public function form($attachTo=NULL, $params=NULL) {
		$result= $this->addComponent(new Form($this->js), $attachTo, $params);
		return $result;
	}
	
	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Toast
	 */
	public function toast($attachTo=NULL, $params=NULL) {
		$result= $this->addComponent(new Toast($this->js), $attachTo, $params);
		return $result;
	}
	
	/**
	 * @param string $attachTo
	 * @param string|array $params
	 * @return Slider
	 */
	public function slider($attachTo=NULL, $params=NULL) {
		$result= $this->addComponent(new Slider($this->js), $attachTo, $params);
		return $result;
	}

}
