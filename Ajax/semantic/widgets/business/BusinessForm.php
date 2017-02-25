<?php

namespace Ajax\semantic\widgets\business;

use Ajax\semantic\widgets\dataform\DataForm;
use Ajax\JsUtils;

/**
 * @author jc
 */
abstract class BusinessForm extends DataForm {
	protected $_fieldsOrder;
	protected $_fieldsDefinition;
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \Ajax\semantic\widgets\dataform\DataForm::__construct()
	 */
	public function __construct($identifier,$modelInstance=null,$fields=[],$captions=[],$separators=[]) {
		parent::__construct($identifier,$modelInstance);
		$this->setFields($fields);
		$this->setSeparators($separators);
		$this->fieldsAs($this->_fieldsDefinition);
		$this->setCaptions($captions);
	}

	protected function _getIndex($fieldName){
		$index=$fieldName;
		if(\is_string($fieldName)){
			$index=\array_search($fieldName, $this->_fieldsOrder);
		}
		return $index;
	}
	protected function _fieldAs($elementCallback,&$index,$attributes=NULL,$prefix=null){
		$index=$this->_getIndex($index);
		return parent::_fieldAs($elementCallback, $index,$attributes,$prefix);
	}


	public function removeField($fieldName){
		parent::removeField($fieldName);
		\array_splice($this->_fieldsOrder,$this->_getIndex($fieldName),1);
		return $this;
	}

	public function compile(JsUtils $js=NULL,&$view=NULL){
		return parent::compile($js,$view);
	}
}