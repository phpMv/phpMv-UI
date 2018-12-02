<?php

namespace Ajax\semantic\html\collections\form\traits;

use Ajax\service\JArray;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\html\collections\form\HtmlFormDropdown;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\collections\form\HtmlFormCheckbox;
use Ajax\semantic\html\collections\form\HtmlFormRadio;
use Ajax\semantic\html\collections\form\HtmlFormField;
use Ajax\common\html\html5\HtmlTextarea;
use Ajax\semantic\html\collections\form\HtmlFormTextarea;
use Ajax\semantic\html\base\HtmlSemDoubleElement;
use Ajax\semantic\html\elements\HtmlButtonGroups;
use Ajax\semantic\html\collections\form\HtmlFormFields;

/**
 * @author jc
 * @property string $identifier
 */
trait FieldsTrait {
	abstract public function addFields($fields=NULL,$label=NULL);
	abstract public function addItem($item);
	abstract public function getItem($index);
	abstract public function count();

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

	/**
	 * Sets the values of a property for each Field of each item in the collection
	 * @param string $property
	 * @param array|mixed $values
	 * @return HtmlFormFields
	 */
	public function setFieldsPropertyValues($property,$values){
		$i=0;
		if(\is_array($values)===false){
			$values=\array_fill(0, $this->count(),$values);
		}
		foreach ($values as $value){
			$c=$this->content[$i++];
			if(isset($c)){
				$df=$c->getDataField();
				$df->setProperty($property,$value);
			}
			else{
				return $this;
			}
		}
		return $this;
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
	 * Adds a new dropdown element
	 * @param string $identifier
	 * @param array $items
	 * @param string $label
	 * @param string $value
	 * @param boolean $multiple
	 * @return HtmlFormDropdown
	 */
	public function addDropdown($identifier,$items=array(), $label=NULL,$value=NULL,$multiple=false){
		return $this->addItem(new HtmlFormDropdown($identifier,$items,$label,$value,$multiple));
	}

	/**
	 * Adds a new button groups
	 * @param string $identifier
	 * @param array $elements
	 * @param boolean $asIcons
	 * @return HtmlButtonGroups
	 */
	public function addButtonGroups($identifier,$elements=[],$asIcons=false){
		return $this->addItem(new HtmlButtonGroups($identifier,$elements,$asIcons));
	}

	/**
	 * Adds a button with a dropdown button
	 * @param string $identifier
	 * @param string $value
	 * @param array $items
	 * @param boolean $asCombo
	 * @param string $icon
	 * @return HtmlButtonGroups
	 */
	public function addDropdownButton($identifier,$value,$items=[],$asCombo=false,$icon=null){
		return $this->addItem(HtmlButton::dropdown($identifier, $value,$items,$asCombo,$icon));
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

	/**
	 * @param string $identifier
	 * @param string $label
	 * @param string $value
	 * @param string $placeholder
	 * @param int $rows
	 * @return HtmlTextarea
	 */
	public function addTextarea($identifier, $label,$value=NULL,$placeholder=NULL,$rows=5){
		return $this->addItem(new HtmlFormTextarea($identifier,$label,$value,$placeholder,$rows));
	}

	public function addPassword($identifier, $label=NULL){
		return $this->addItem(new HtmlFormInput($identifier,$label,"password","",""));
	}

	public function addButton($identifier,$value,$cssStyle=NULL,$onClick=NULL){
		return $this->addItem(new HtmlButton($identifier,$value,$cssStyle,$onClick));
	}
	
	public function addButtonIcon($identifier,$icon,$cssStyle=NULL,$onClick=NULL){
		$bt=new HtmlButton($identifier);
		$bt->asIcon($icon);
		if(isset($onClick))
			$bt->onClick($onClick);
		if (isset($cssStyle))
			$bt->addClass($cssStyle);
		return $this->addItem($bt);
	}

	/**
	 * @param string $identifier
	 * @param string $label
	 * @param string $value
	 * @param string $type
	 * @return HtmlFormCheckbox
	 */
	public function addCheckbox($identifier, $label=NULL,$value=NULL,$type=NULL){
		return $this->addItem(new HtmlFormCheckbox($identifier,$label,$value,$type));
	}

	public function addRadio($identifier, $name,$label=NULL,$value=NULL){
		return $this->addItem(new HtmlFormRadio($identifier,$name,$label,$value));
	}

	public function addElement($identifier,$content,$label,$tagName="div",$baseClass=""){
		$div=new HtmlSemDoubleElement($identifier,$tagName,$baseClass,$content);
		return $this->addItem(new HtmlFormField("field-".$identifier, $div,$label));
	}
}
