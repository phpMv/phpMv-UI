<?php

namespace Ajax\semantic\components;

use Ajax\JsUtils;

/**
 * Ajax\semantic\components$Modal
 * This class is part of Ubiquity
 * @author jcheron <myaddressmail@gmail.com>
 * @version 1.0.1
 *
 */
class Modal extends SimpleSemExtComponent {

	public function __construct(JsUtils $js) {
		parent::__construct($js);
		$this->uiName='modal';
	}

	public function showDimmer(){
		return $this->addBehavior('hide dimmer');
	}

	public function setInverted(){
		$this->params['inverted']=true;
	}

	public function setOnHidden($jsCode) {
		$this->addComponentEvent('onHidden', $jsCode);
	}
}
