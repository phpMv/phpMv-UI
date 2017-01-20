<?php
namespace Ajax\semantic\widgets\dataform;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\collections\form\HtmlFormRadio;
use Ajax\semantic\html\collections\form\HtmlFormTextarea;
use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\html\collections\form\HtmlFormCheckbox;
use Ajax\semantic\html\collections\form\HtmlFormDropdown;

/**
 * @author jc
 * @property FormInstanceViewer $_instanceViewer
 * @property object $_modelInstance;
 */

trait FormFieldAsTrait{

	abstract protected function _getFieldIdentifier($prefix);
	abstract public function setValueFunction($index,$callback);

	private function _getLabelField($caption,$icon=NULL){
		$label=new HtmlLabel($this->_getFieldIdentifier("lbl"),$caption,$icon);
		return $label;
	}

	/**
	 * @param HtmlFormField $element
	 * @param array $attributes
	 */
	protected function _applyAttributes($element,&$attributes,$index){
		$this->_addRules($element, $attributes);
		if(isset($attributes["callback"])){
			$callback=$attributes["callback"];
			if(\is_callable($callback)){
				$callback($element,$this->_modelInstance,$index);
				unset($attributes["callback"]);
			}
		}
		$element->fromArray($attributes);
	}

	protected function _addRules($element,$attributes){
		if(isset($attributes["rules"])){
			$rules=$attributes["rules"];
			if(\is_array($rules))
				$element->addRules($rules);
				else
					$element->addRule($rules);
				unset($attributes["rules"]);
		}
	}

	protected function _fieldAs($elementCallback,$index,$attributes=NULL,$identifier=null){
		$this->setValueFunction($index,function($value)use ($index,&$attributes,$elementCallback){
			$caption=$this->_instanceViewer->getCaption($index);
			$name=$this->_instanceViewer->getFieldName($index);
			$element=$elementCallback($name,$caption,$value);
			if(\is_array($attributes))
				$this->_applyAttributes($element, $attributes,$index);
			return $element;
		});
			return $this;
	}


	public function fieldAsRadio($index,$attributes=NULL){
		return $this->_fieldAs(function($name,$caption,$value){
			return new HtmlFormRadio($name,$name,$caption,$value);
		}, $index,$attributes);
	}

	public function fieldAsTextarea($index,$attributes=NULL){
		return $this->_fieldAs(function($name,$caption,$value){
			return new HtmlFormTextarea($name,$caption,$value);
		}, $index,$attributes);
	}

	public function fieldAsInput($index,$attributes=NULL){
		return $this->_fieldAs(function($name,$caption,$value){
			return new HtmlFormInput($name,$caption,"text",$value);
		}, $index,$attributes);
	}

	public function fieldAsCheckbox($index,$attributes=NULL){
		return $this->_fieldAs(function($name,$caption,$value){
			return new HtmlFormCheckbox($name,$caption,$value);
		}, $index,$attributes);
	}

	public function fieldAsDropDown($index,$elements=[],$multiple=false,$attributes=NULL){
		return $this->_fieldAs(function($name,$caption,$value) use ($elements,$multiple){
			return new HtmlFormDropdown($name,$elements,$caption,$value,$multiple);
		}, $index,$attributes);
	}
}