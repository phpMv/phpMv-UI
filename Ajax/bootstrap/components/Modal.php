<?php

namespace Ajax\bootstrap\components;

use Ajax\JsUtils;
use Ajax\bootstrap\components\js\Draggable;
use Ajax\common\components\SimpleExtComponent;

/**
 * Composant Twitter Bootstrap Modal
 * @author jc
 * @version 1.001
 */
class Modal extends SimpleExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName="modal";
	}

	public function attach($identifier) {
		parent::attach($identifier);
		$this->js->addClass($identifier, "modal", true);
		$this->js->attr($identifier, "role", "dialog", true);
		$this->js->attr($identifier, "aria-labelledby", "myModalLabel", true);
		$this->js->attr($identifier, "aria-hidden", "true", true);
	}

	/**
	 * Shows the modal when initialized.
	 * @param Boolean $value default : true
	 * @return $this
	 */
	public function setShow($value) {
		return $this->setParam("show", $value);
	}

	/**
	 * Includes a modal-backdrop element.
	 * Alternatively, specify static for a backdrop which doesn't close the modal on click.
	 * @param Boolean $value default : true
	 * @return $this
	 */
	public function setBackdrop($value) {
		return $this->setParam("backdrop", $value);
	}

	/**
	 * Closes the modal when escape key is pressed.
	 * @param Boolean $value default : false
	 * @return $this
	 */
	public function setKeyboard($value) {
		return $this->setParam("keyboard", $value);
	}

	public function setDraggable($value) {
		if ($value) {
			$this->jsCodes ["draggable"]=new Draggable();
			$this->setBackdrop(false);
		} else if (array_key_exists("draggable", $this->jsCodes)) {
			unset($this->jsCodes ["draggable"]);
			unset($this->params ["backdrop"]);
		}
	}

	/**
	 * This event fires immediately when the show instance method is called.
	 * If caused by a click, the clicked element is available as the relatedTarget property of the event.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShow($jsCode) {
		return $this->addEvent("show.bs.modal", $jsCode);
	}

	/**
	 * This event is fired when the modal has been made visible to the user (will wait for CSS transitions to complete).
	 * If caused by a click, the clicked element is available as the relatedTarget property of the event.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onShown($jsCode) {
		return $this->addEvent("shown.bs.modal", $jsCode);
	}

	/**
	 * This event is fired immediately when the hide instance method has been called.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHide($jsCode) {
		return $this->addEvent("hide.bs.modal", $jsCode);
	}

	/**
	 * This event is fired when the modal has finished being hidden from the user (will wait for CSS transitions to complete).
	 * @param string $jsCode
	 * @return $this
	 */
	public function onHidden($jsCode) {
		return $this->addEvent("hidden.bs.modal", $jsCode);
	}

	/**
	 * This event is fired when the modal has loaded content using the remote option.
	 * @param string $jsCode
	 * @return $this
	 */
	public function onLoaded($jsCode) {
		return $this->addEvent("loaded.bs.modal", $jsCode);
	}
}
