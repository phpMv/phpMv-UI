<?php
namespace Ajax\semantic\components;

use Ajax\JsUtils;

/**
 * Ajax\semantic\components$Sidebar
 * This class is part of phpMv-ui
 *
 * @author jcheron <myaddressmail@gmail.com>
 * @version 1.0.0
 * @since 2.3.6
 * @see https://fomantic-ui.com/modules/sidebar.html
 */
class Sidebar extends SimpleSemExtComponent {

	public function __construct(JsUtils $js = NULL) {
		parent::__construct($js);
		$this->uiName = 'sidebar';
	}

	public function show() {
		return $this->addBehavior('show');
	}

	public function hide() {
		return $this->addBehavior('hide');
	}

	public function toogle() {
		return $this->addBehavior('toggle');
	}

	/**
	 * Pushes page content to be visible alongside sidebar.
	 */
	public function pushPage() {
		return $this->addBehavior('push page');
	}

	/**
	 * Returns page content to original position.
	 */
	public function pullPage() {
		return $this->addBehavior('pull page');
	}

	public function setContext($value) {
		$this->params['context'] = $value;
		return $this;
	}

	public function setExclusive($value = true) {
		$this->params['exclusive'] = true;
		return $this;
	}

	public function setClosable($value = true) {
		$this->params['closable'] = $value;
		return $this;
	}

	public function setDimPage($value = true) {
		$this->params['dimPage'] = $value;
		return $this;
	}

	public function setScrollLock($value = false) {
		$this->params['scrollLock'] = $value;
		return $this;
	}

	public function setReturnScroll($value = false) {
		$this->params['returnScroll'] = $value;
		return $this;
	}

	public function setOnVisible($jsCode) {
		$this->addComponentEvent('onVisible', $jsCode);
	}

	public function setOnShow($jsCode) {
		$this->addComponentEvent('onShow', $jsCode);
		return $this;
	}

	public function setOnChange($jsCode) {
		$this->addComponentEvent('onChange', $jsCode);
		return $this;
	}

	public function setOnHide($jsCode) {
		$this->addComponentEvent('onHide', $jsCode);
		return $this;
	}

	public function setOnHidden($jsCode) {
		$this->addComponentEvent('onHidden', $jsCode);
		return $this;
	}
}
