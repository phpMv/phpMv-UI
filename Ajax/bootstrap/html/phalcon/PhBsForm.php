<?php

namespace Ajax\bootstrap\html\phalcon;

use Phalcon\Forms\Form;
use Ajax\bootstrap\html\HtmlForm;
use Ajax\JsUtils;


class PhBsForm extends Form {
	/**
	 *
	 * @var HtmlForm
	 */
	protected $form;

	public function __construct($identifier, $entity=null, array $userOptions=null) {
		parent::__construct($entity, $userOptions);
		$this->form=new HtmlForm($identifier);
	}

	/*
	 * (non-PHPdoc)
	 * @see \Phalcon\Forms\Form::add()
	 */
	public function add($element, $postion=null, $type=null) {
		parent::add($element, $postion, $type);
	}

	public function compile(JsUtils $js=NULL, &$view=NULL) {
		$result="";
		foreach ( $this->_elements as $element ) {
			if ($element instanceof PhBsElement)
				$result.=$element->compile($js, $view);
		}
		return $result;
	}

	public function run(JsUtils $js) {
		foreach ( $this->_elements as $element ) {
			if ($element instanceof PhBsElement)
				$element->run($js);
		}
	}
}