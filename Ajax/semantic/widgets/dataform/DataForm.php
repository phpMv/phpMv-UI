<?php

namespace Ajax\semantic\widgets\dataform;

use Ajax\common\Widget;
use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\semantic\widgets\datatable\PositionInTable;
use Ajax\service\JArray;
use Ajax\JsUtils;
use Ajax\semantic\html\collections\form\traits\FormTrait;
use Ajax\semantic\html\elements\HtmlButton;

/**
 * DataForm widget for editing model objects
 * @version 1.0
 * @author jc
 * @since 2.2
 * @property FormInstanceViewer $_instanceViewer
 */
class DataForm extends Widget {
	use FormTrait;

	public function __construct($identifier, $modelInstance=NULL) {
		parent::__construct($identifier, null,$modelInstance);
		$this->_init(new FormInstanceViewer($identifier), "form", new HtmlForm($identifier), true);
	}

	protected function _getFieldIdentifier($prefix,$name=""){
		return $this->identifier."-{$name}-".$this->_instanceViewer->getIdentifier();
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
		$size=\sizeof($separators);
		if($size===1){
			foreach ($values as $v){
				$form->addField($v);
			}
		}else{
			$separators[]=$count;
			for($i=0;$i<$size;$i++){
				$fields=\array_slice($values, $separators[$i]+1,$separators[$i+1]-$separators[$i]);
				//TODO check why $fields is empty
				if(\sizeof($fields)===1){
					$form->addField($fields[0]);
				}elseif(\sizeof($fields)>1){
					$form->addFields($fields);
					$i+=\sizeof($fields)-1;
				}
			}
		}
	}

	/**
	 * @return HtmlForm
	 */
	protected function getForm(){
		return $this->content["form"];
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

	public function addSubmitInToolbar($identifier,$value,$cssStyle=NULL,$url=NULL,$responseElement=NULL){
		$button=new HtmlButton($identifier,$value,$cssStyle);
		$this->_buttonAsSubmit($button,"click",$url,$responseElement);
		return $this->addInToolbar($button);
	}

	public function fieldAsSubmit($index,$cssStyle=NULL,$url=NULL,$responseElement=NULL,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption) use ($url,$responseElement,$cssStyle){
			$button=new HtmlButton($id,$value,$cssStyle);
			$this->_buttonAsSubmit($button,"click",$url,$responseElement);
			return $button;
		}, $index,$attributes);
	}

	public function fieldAsReset($index,$cssStyle=NULL,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption) use ($cssStyle){
			$button=new HtmlButton($id,$value,$cssStyle);
			$button->setProperty("type", "reset");
			return $button;
		}, $index,$attributes);
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

	public function setValidationParams(array $_validationParams) {
		$this->getForm()->setValidationParams($_validationParams);
		return $this;
	}
}