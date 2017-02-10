<?php
namespace Ajax\semantic\widgets\base;
use Ajax\service\JString;
use Ajax\semantic\html\elements\HtmlImage;
use Ajax\semantic\html\modules\checkbox\HtmlRadio;
use Ajax\semantic\html\base\constants\Size;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\modules\HtmlProgress;
use Ajax\semantic\html\modules\HtmlRating;
use Ajax\semantic\html\elements\HtmlHeader;
use Ajax\semantic\html\collections\form\HtmlFormCheckbox;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\html\collections\form\HtmlFormDropdown;
use Ajax\semantic\html\collections\form\HtmlFormTextarea;
use Ajax\semantic\html\collections\form\HtmlFormFields;
use Ajax\semantic\html\collections\HtmlMessage;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\service\JArray;

/**
 * @author jc
 * @property InstanceViewer $_instanceViewer
 * @property boolean $_edition
 * @property mixed _modelInstance
 */
trait FieldAsTrait{

	abstract protected function _getFieldIdentifier($prefix,$name="");
	abstract public function setValueFunction($index,$callback);
	abstract protected function _getFieldName($index);
	abstract protected function _getFieldCaption($index);
	abstract protected function _buttonAsSubmit(&$button,$event,$url,$responseElement=NULL);

	/**
	 * @param HtmlFormField $element
	 * @param array $attributes
	 */
	protected function _applyAttributes($element,&$attributes,$index){
		if(isset($attributes["callback"])){
			$callback=$attributes["callback"];
			if(\is_callable($callback)){
				$callback($element,$this->_modelInstance,$index);
				unset($attributes["callback"]);
			}
		}
		unset($attributes["rules"]);
		$element->fromArray($attributes);
	}

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
			else{
				$element->addRule($rules);
			}
			unset($attributes["rules"]);
		}
	}

	protected function _prepareFormFields(&$field,$name,&$attributes){
		$field->setName($name);
		$this->_addRules($field, $attributes);
		return $field;
	}

	protected function _fieldAs($elementCallback,$index,$attributes=NULL,$prefix=null){
		$this->setValueFunction($index,function($value) use ($index,&$attributes,$elementCallback,$prefix){
			$caption=$this->_getFieldCaption($index);
			$name=$this->_getFieldName($index);
			$id=$this->_getFieldIdentifier($prefix,$name);
			if(isset($attributes["name"])){
				$name=$attributes["name"];
				unset($attributes["name"]);
			}
			$element=$elementCallback($id,$name,$value,$caption);
			if(\is_array($attributes)){
				$this->_applyAttributes($element, $attributes,$index);
			}
			$element->setDisabled(!$this->_edition);
			return $element;
		});
			return $this;
	}


	public function fieldAsProgress($index,$label=NULL, $attributes=array()){
		$this->setValueFunction($index,function($value) use($label,$attributes){
			$pb=new HtmlProgress($this->_getFieldIdentifier("pb"),$value,$label,$attributes);
			return $pb;
		});
			return $this;
	}

	public function fieldAsRating($index,$max=5, $icon=""){
		$this->setValueFunction($index,function($value) use($max,$icon){
			$rating=new HtmlRating($this->_getFieldIdentifier("rat"),$value,$max,$icon);
			return $rating;
		});
			return $this;
	}

	public function fieldAsLabel($index,$icon=NULL){
		$this->setValueFunction($index,function($caption) use($icon){
			$lbl=$this->_getLabelField($caption,$icon);
			return $lbl;
		});
			return $this;
	}

	public function fieldAsHeader($index,$niveau=1,$icon=NULL,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value) use($niveau,$icon){
			$header=new HtmlHeader($id,$niveau,$value);
			if(isset($icon))
				$header->asIcon($icon, $value);
			return $header;
		}, $index,$attributes,"header");
	}


	public function fieldAsImage($index,$size=Size::MINI,$circular=false){
		$this->setValueFunction($index,function($img) use($size,$circular){
			$image=new HtmlImage($this->_getFieldIdentifier("image"),$img);$image->setSize($size);if($circular)$image->setCircular();
			return $image;
		});
			return $this;
	}

	public function fieldAsAvatar($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value){
			$img=new HtmlImage($id,$value);
			$img->asAvatar();
			return $img;
		}, $index,$attributes,"avatar");
	}

	public function fieldAsRadio($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value){
			$input= new HtmlRadio($id,$name,$value,$value);
			return $input;
		}, $index,$attributes,"radio");
	}

	public function fieldAsRadios($index,$elements=[],$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption) use ($elements){
			return HtmlFormFields::radios($name,$elements,$caption,$value);
		}, $index,$attributes,"radios");
	}

	public function fieldAsInput($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption) use ($attributes){
			$input= new HtmlFormInput($id,$caption,"text",$value);
			return $this->_prepareFormFields($input, $name, $attributes);
		}, $index,$attributes,"input");
	}

	public function fieldAsTextarea($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value){
			$textarea=new HtmlFormTextarea($id,null,$value);
			$textarea->setName($name);
			return $textarea;
		}, $index,$attributes,"textarea");
	}

	public function fieldAsHidden($index,$attributes=NULL){
		if(!\is_array($attributes)){
			$attributes=[];
		}
		$attributes["imputType"]="hidden";
		return $this->fieldAsInput($index,$attributes);
	}

	public function fieldAsCheckbox($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value){
			$input=new HtmlFormCheckbox($id,NULL,$this->_instanceViewer->getIdentifier());
			$input->setChecked(JString::isBooleanTrue($value));
			$input->setName($name);
			return $input;
		}, $index,$attributes,"ck");
	}

	public function fieldAsDropDown($index,$elements=[],$multiple=false,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value) use($elements,$multiple){
			$dd=new HtmlFormDropdown($id,$elements,NULL,$value);
			$dd->asSelect($name,$multiple);
			return $dd;
		}, $index,$attributes,"dd");
	}

	public function fieldAsMessage($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption){
			$mess= new HtmlMessage("message-".$id,$value);
			$mess->addHeader($caption);
			return $mess;
		}, $index,$attributes,"message");
	}

	/**Change fields type
	 * @param array $types an array or associative array $type=>$attribute
	 */
	public function fieldsAs(array $types){
		$i=0;
		if(JArray::isAssociative($types)){
			foreach ($types as $type=>$attributes){
				$this->fieldAs($i++,$type,$attributes);
			}
		}else{
			foreach ($types as $type){
				$this->fieldAs($i++,$type);
			}
		}
	}

	public function fieldAs($index,$type,$attributes=NULL){
		$method="fieldAs".\ucfirst($type);

		if(\method_exists($this, $method)){
			if(!\is_array($attributes)){
				$attributes=[$index];
			}else{
				\array_unshift($attributes, $index);
			}
			\call_user_func_array([$this,$method], $attributes);
		}
	}

	public function fieldAsSubmit($index,$cssStyle=NULL,$url=NULL,$responseElement=NULL,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value,$caption) use ($url,$responseElement,$cssStyle){
			$button=new HtmlButton($id,$value,$cssStyle);
			$this->_buttonAsSubmit($button,"click",$url,$responseElement);
			return $button;
		}, $index,$attributes);
	}
}