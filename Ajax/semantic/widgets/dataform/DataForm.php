<?php

namespace Ajax\semantic\widgets\dataform;

use Ajax\common\Widget;
use Ajax\semantic\html\collections\form\HtmlForm;
use Ajax\semantic\widgets\datatable\PositionInTable;
use Ajax\service\JArray;
use Ajax\JsUtils;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\base\traits\BaseTrait;

/**
 * DataForm widget for editing model objects
 * @version 1.0
 * @author jc
 * @since 2.2
 * @property FormInstanceViewer $_instanceViewer
 */
class DataForm extends Widget {
	use BaseTrait;

	public function __construct($identifier, $modelInstance=NULL) {
		parent::__construct($identifier, null,$modelInstance);
		$this->_form=new HtmlForm($identifier);
		$this->_init(new FormInstanceViewer($identifier), "form", $this->_form, true);
	}

	protected function _getFieldIdentifier($prefix,$name=""){
		return $this->identifier."-{$name}-".$this->_instanceViewer->getIdentifier();
	}

	public function compile(JsUtils $js=NULL,&$view=NULL){
		if(!$this->_generated){
			$this->_instanceViewer->setInstance($this->_modelInstance);

			$form=$this->content["form"];
			$this->_generateContent($form);

			if(isset($this->_toolbar)){
				$this->_setToolbarPosition($form);
			}
			$this->content=JArray::sortAssociative($this->content, [PositionInTable::BEFORETABLE,"form",PositionInTable::AFTERTABLE]);
			$this->_generated=true;
		}
		return parent::compile($js,$view);
	}

	/**
	 * @param HtmlForm $form
	 */
	protected function _generateContent($form){
		$values= $this->_instanceViewer->getValues();
		$count=$this->_instanceViewer->count();
		$separators=$this->_instanceViewer->getSeparators();
		$headers=$this->_instanceViewer->getHeaders();
		$wrappers=$this->_instanceViewer->getWrappers();
		\sort($separators);
		$size=\sizeof($separators);
		$nb=0;
		if($size===1){
			$i=-1;
			foreach ($values as $v){
				$this->_generateFields($form, [$v], $headers, $i, $wrappers,$nb++);
				$i++;
			}
		}else{
			$separators[]=$count;
			for($i=0;$i<$size;$i++){
				$fields=\array_slice($values, $separators[$i]+1,$separators[$i+1]-$separators[$i]);
				$this->_generateFields($form, $fields, $headers, $separators[$i], $wrappers,$nb++);
			}
		}
		if($this->_hasRules && !$this->getForm()->hasValidationParams()){
				$this->setValidationParams(["inline"=>true]);
		}
	}

	protected function _generateFields($form,$values,$headers,$sepFirst,$wrappers,$nb){
		$wrapper=null;
		if(isset($headers[$sepFirst+1]))
			$form->addHeader($headers[$sepFirst+1],4,true);
		if(isset($wrappers[$sepFirst+1])){
			$wrapper=$wrappers[$sepFirst+1];
		}
		if(\sizeof($values)===1){
			$added=$form->addField($values[0]);
		}elseif(\sizeof($values)>1){
			$added=$form->addFields($values);
		}else
			return;
		if(isset($wrapper)){
			$added->wrap($wrapper[0],$wrapper[1]);
		}
		$this->execHook("onGenerateFields",$added,$nb);
	}
	
	/**
	 * Function called when a field is generated
	 * the generated field is the first parameter
	 * @param callable $callback the fonction to call when a field is generated
	 */
	public function onGenerateField($callback){
		$this->addHook("onGenerateFields",$callback);
	}

	/**
	 * @return HtmlForm
	 */
	public function getForm(){
		return $this->content["form"];
	}

	public function addSeparatorAfter($fieldNum){
		$fieldNum=$this->_getIndex($fieldNum);
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

	public function fieldAsReset($index,$cssStyle=NULL,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value) use ($cssStyle){
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

	public function addDividerBefore($index,$title){
		$index=$this->_getIndex($index);
		$this->_instanceViewer->addHeaderDividerBefore($index, $title);
		return $this;
	}

	public function addWrapper($index,$contentBefore,$contentAfter=null){
		$index=$this->_getIndex($index);
		$this->_instanceViewer->addWrapper($index, $contentBefore,$contentAfter);
		return $this;
	}

	public function run(JsUtils $js){
		parent::run($js);
	}
}
