<?php
namespace Ajax\semantic\html\modules;

use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\JsUtils;
use Ajax\service\JArray;

/**
 * Ajax\semantic\html\modules$HtmlToast
 * This class is part of phpMv-ui
 * @author jcheron <myaddressmail@gmail.com>
 * @version 1.0.0
 * @since 2.3.0
 * @see https://fomantic-ui.com/modules/toast.html
 */
class HtmlToast extends HtmlSemDoubleElement {
	
	protected $_paramParts=array();
	
	public function __construct($identifier, $content='') {
		parent::__construct($identifier, 'div','ui toast');
		if(isset($content)){
			$this->setContent($content);
		}
	}
	
	public function setContent($value) {
		$this->content['content']=new HtmlSemDoubleElement('content-' . $this->identifier, 'div', 'content', $value);
		return $this;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see \Ajax\semantic\html\base\HtmlSemDoubleElement::compile()
	 */
	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$this->content=JArray::sortAssociative($this->content, ['content','actions' ]);
		return parent::compile($js, $view);
	}
	
	/*
	 * (non-PHPdoc)
	 * @see BaseHtml::run()
	 */
	public function run(JsUtils $js) {
		if(isset($this->_bsComponent)===false){
			$this->_bsComponent=$js->semantic()->toast('#'.$this->identifier,$this->_params,$this->_paramParts);
		}
		$this->addEventsOnRun($js);
		return $this->_bsComponent;
	}
}

