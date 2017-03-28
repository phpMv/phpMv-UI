<?php

namespace Ajax\semantic\html\collections\form\traits;

use Ajax\service\JArray;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\html\collections\form\HtmlFormDropdown;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\collections\form\HtmlFormCheckbox;
use Ajax\semantic\html\collections\form\HtmlFormRadio;
use Ajax\semantic\html\collections\form\HtmlFormField;

/**
 * @author jc
 * @property string $identifier
 */
trait FieldsTrait {
	abstract public function addFields($fields=NULL,$label=NULL);
	abstract public function addItem($item);

	protected function createItem($value){
		if(\is_array($value)){
			$itemO=new HtmlFormInput(JArray::getDefaultValue($value, "id",""),JArray::getDefaultValue($value, "label",null),JArray::getDefaultValue($value, "type", "text"),JArray::getDefaultValue($value, "value",""),JArray::getDefaultValue($value, "placeholder",JArray::getDefaultValue($value, "label",null)));
			return $itemO;
		}elseif(\is_object($value)){
			$itemO=new HtmlFormField("field-".$this->identifier, $value);
			return $itemO;
		}else
			return new HtmlFormInput($value);
	}

	protected function createCondition($value){
		return \is_object($value)===false || $value instanceof \Ajax\semantic\html\elements\HtmlInput;
	}

	public function addInputs($inputs,$fieldslabel=null){
		$fields=array();
		foreach ($inputs as $input){
			\extract($input);
			$f=new HtmlFormInput("","");
			$f->fromArray($input);
			$fields[]=$f;
		}
		return $this->addFields($fields,$fieldslabel);
	}

	public function addFieldRule($index,$type,$prompt=NULL,$value=NULL){
		$field=$this->getItem($index);
		if(isset($field)){
			$field->addRule($type,$prompt,$value);
		}
		return $this;
	}

	public function addFieldRules($index,$rules){
		$field=$this->getItem($index);
		if(isset($field)){
			$field->addRules($rules);
		}
		return $this;
	}

	/**
	 * @param string $identifier
	 * @param array $items
	 * @param string $label
	 * @param string $value
	 * @param boolean $multiple
	 * @return \Ajax\common\html\HtmlDoubleElement
	 */
	public function addDropdown($identifier,$items=array(), $label=NULL,$value=NULL,$multiple=false){
		return $this->addItem(new HtmlFormDropdown($identifier,$items,$label,$value,$multiple));
	}

	/**
	 * @param string $identifier
	 * @param string $label
	 * @param string $type
	 * @param string $value
	 * @param string $placeholder
	 * @return HtmlFormInput
	 */
	public function addInput($identifier, $label=NULL,$type="text",$value=NULL,$placeholder=NULL){
		return $this->addItem(new HtmlFormInput($identifier,$label,$type,$value,$placeholder));
	}

	public function addPassword($identifier, $label=NULL){
		return $this->addItem(new HtmlFormInput($identifier,$label,"password","",""));
	}

	public function addButton($identifier,$value,$cssStyle=NULL,$onClick=NULL){
		return $this->addItem(new HtmlButton($identifier,$value,$cssStyle,$onClick));
	}

	public function addCheckbox($identifier, $label=NULL,$value=NULL,$type=NULL){
		return $this->addItem(new HtmlFormCheckbox($identifier,$label,$value,$type));
	}

	public function addRadio($identifier, $name,$label=NULL,$value=NULL){
		return $this->addItem(new HtmlFormRadio($identifier,$name,$label,$value));
	}
}
