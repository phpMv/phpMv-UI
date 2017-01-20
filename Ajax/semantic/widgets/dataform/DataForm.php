<?php

namespace Ajax\semantic\widgets\dataform;

use Ajax\common\Widget;
use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\semantic\widgets\datatable\PositionInTable;
use Ajax\service\JArray;
use Ajax\JsUtils;

/**
 * DataForm widget for editing model objects
 * @version 1.0
 * @author jc
 * @since 2.2
 */
class DataForm extends Widget {
	use FormFieldAsTrait;

	public function __construct($identifier, $modelInstance=NULL) {
		parent::__construct($identifier, null,$modelInstance);
		$this->_instanceViewer=new FormInstanceViewer();
		$this->content=["form"=>new HtmlForm($identifier)];
		$this->_toolbarPosition=PositionInTable::BEFORETABLE;
	}

	public function compile(JsUtils $js=NULL,&$view=NULL){
		$this->_instanceViewer->setInstance($this->_modelInstance);

		$form=$this->content["form"];
		$this->_generateContent($form);

		if(isset($this->_toolbar)){
			$this->_setToolbarPosition($form);
		}
		$this->content=JArray::sortAssociative($this->content, [PositionInTable::BEFORETABLE,"form",PositionInTable::AFTERTABLE]);
		return parent::compile($js,$view);
	}

	/**
	 * @param HtmlForm $form
	 */
	protected function _generateContent($form){
		$values= $this->_instanceViewer->getValues();
		$count=$this->_instanceViewer->count();
		$separators=$this->_instanceViewer->getSeparators();
		$separators[]=$count;
		for($i=0;$i<\sizeof($separators)-1;$i++){
			$fields=\array_slice($values, $separators[$i]+1,$separators[$i+1]-$separators[$i]);
			if(\sizeof($fields)===1){
				$form->addField($fields[0]);
			}else
				$form->addFields($fields);
		}
	}

	public function addSeparatorAfter($fieldNum){
		$this->_instanceViewer->addSeparatorAfter($fieldNum);
		return $this;
	}

	public function getSeparators() {
		return $this->_instanceViewer->getSeparators();
	}

	public function setSeparators($separators) {
		$this->_instanceViewer->setSeparators($separators);
		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see \Ajax\common\Widget::getHtmlComponent()
	 * @return HtmlForm
	 */
	public function getHtmlComponent() {
		return $this->content["form"];
	}
	/**
	 * {@inheritdoc}
	 * @see \Ajax\common\Widget::_setToolbarPosition()
	 */
	protected function _setToolbarPosition($table, $captions=NULL) {
		$this->content[$this->_toolbarPosition]=$this->_toolbar;
	}

	public function setValidationParams(array $_validationParams){
		return $this->getHtmlComponent()->setValidationParams($_validationParams);
	}

	public function addSubmit($identifier,$value,$cssStyle=NULL,$url=NULL,$responseElement=NULL){
		return $this->getHtmlComponent()->addSubmit($identifier, $value,$cssStyle,$url,$responseElement);
	}
}