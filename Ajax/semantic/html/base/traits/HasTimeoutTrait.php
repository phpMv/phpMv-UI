<?php

namespace Ajax\semantic\html\base\traits;

use Ajax\JsUtils;

/**
 * @author jc
 * @property string $identifier
 * @property BaseComponent $_bsComponent
 */
trait HasTimeoutTrait {
	protected $_timeout;
	protected $_closeTransition="{animation : 'scale',duration : '2s'}";

	/**
	 * {@inheritDoc}
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::run()
	 */
	public function run(JsUtils $js){
		if(!isset($this->_bsComponent)){
			if(isset($this->_timeout)){
				$js->exec("setTimeout(function() { $('#{$this->identifier}').transition({$this->_closeTransition}); }, {$this->_timeout});",true);
			}
		}
		return parent::run($js);
	}

	public function setTimeout($_timeout) {
		$this->_timeout=$_timeout;
		return $this;
	}

	public function setCloseTransition($_closeTransition) {
		$this->_closeTransition=$_closeTransition;
		return $this;
	}
}
