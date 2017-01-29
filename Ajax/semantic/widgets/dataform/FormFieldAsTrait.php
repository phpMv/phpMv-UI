<?php
namespace Ajax\semantic\widgets\dataform;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\collections\form\HtmlFormRadio;
use Ajax\semantic\html\collections\form\HtmlFormTextarea;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\html\collections\form\HtmlFormCheckbox;
use Ajax\semantic\html\collections\form\HtmlFormDropdown;
use Ajax\semantic\html\collections\form\HtmlFormFields;
use Ajax\semantic\html\collections\HtmlMessage;

/**
 * @author jc
 * @property FormInstanceViewer $_instanceViewer
 * @property object $_modelInstance
 */

trait FormFieldAsTrait{

	abstract protected function _getFieldIdentifier($prefix);
	abstract public function setValueFunction($index,$callback);
	abstract protected function _applyAttributes($element,&$attributes,$index);
	abstract public function getIdentifier();

	private function _getLabelField($caption,$icon=NULL){
		$label=new HtmlLabel($this->_getFieldIdentifier("lbl"),$caption,$icon);
		return $label;
	}

	protected function _addRules($element,&$attributes){
		if(isset($attributes["rules"])){
			$rules=$attributes["rules"];
			if(\is_array($rules)){
				$element->addRules($rules);
			}
			else
				$element->addRule($rules);
			unset($attributes["rules"]);
		}
	}

	protected function _fieldAs($elementCallback,$index,$attributes=NULL,$identifier=null){
		$this->setValueFunction($index,function($value) use ($index,&$attributes,$elementCallback){
			$caption=$this->_instanceViewer->getCaption($index);
			$name=$this->_instanceViewer->getFieldName($index);
			$element=$elementCallback($this->getIdentifier()."-".$name,$name,$value,$caption);
			if(\is_array($attributes)){
				$this->_applyAttributes($element, $attributes,$index);
			}
			return $element;
		});
			return $this;
	}


	public function fieldAsRadio($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption){
			return new HtmlFormRadio($id,$name,$caption,$value);
		}, $index,$attributes);
	}

	public function fieldAsRadios($index,$elements=[],$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption,$elements){
			return HtmlFormFields::radios($name,$elements,$caption,$value);
		}, $index,$attributes);
	}

	public function fieldAsTextarea($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption){
			$textarea=new HtmlFormTextarea($id,$caption,$value);
			$textarea->setName($name);
			return $textarea;
		}, $index,$attributes);
	}

	public function fieldAsInput($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption){
			$input= new HtmlFormInput($id,$caption,"text",$value);
			$input->setName($name);
			return $input;
		}, $index,$attributes);
	}

	public function fieldAsCheckbox($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption){
			return new HtmlFormCheckbox($id,$caption,$value);
		}, $index,$attributes);
	}

	public function fieldAsDropDown($index,$elements=[],$multiple=false,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption) use ($elements,$multiple){
			$dd=new HtmlFormDropdown($id,$elements,$caption,$value,$multiple);
			$dd->setName($name);
			return $dd;
		}, $index,$attributes);
	}

	public function fieldAsMessage($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption){
			$mess= new HtmlMessage("message-".$id,$value);
			$mess->addHeader($caption);
			return $mess;
		}, $index,$attributes,"message");
	}
}