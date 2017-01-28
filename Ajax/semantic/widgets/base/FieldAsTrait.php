<?php
namespace Ajax\semantic\widgets\base;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\service\JString;
use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\semantic\html\elements\HtmlImage;
use Ajax\semantic\html\modules\checkbox\HtmlRadio;
use Ajax\semantic\html\base\constants\Size;
use Ajax\semantic\html\elements\HtmlLabel;
use Ajax\semantic\html\modules\HtmlProgress;
use Ajax\semantic\html\modules\HtmlRating;
use Ajax\semantic\html\elements\HtmlHeader;
/**
 * @author jc
 * @property InstanceViewer $_instanceViewer
 * @property boolean $_edition
 */

trait FieldAsTrait{

	abstract protected function _getFieldIdentifier($prefix);
	abstract public function setValueFunction($index,$callback);

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

	private function _getLabelField($caption,$icon=NULL){
		$label=new HtmlLabel($this->_getFieldIdentifier("lbl"),$caption,$icon);
		return $label;
	}

	protected function _addRules($element,&$attributes){}

	protected function _fieldAs($elementCallback,$index,$attributes=NULL,$prefix=null){
		$this->setValueFunction($index,function($value) use ($index,&$attributes,$elementCallback,$prefix){
			$name=$this->_instanceViewer->getCaption($index)."[]";
			if(isset($attributes["name"])){
				$name=$attributes["name"];
			}
			$element=$elementCallback($this->_getFieldIdentifier($prefix),$name,$value,"");
			if(\is_array($attributes))
				$this->_applyAttributes($element, $attributes,$index);
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

	public function fieldAsInput($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value){
			$input= new HtmlInput($id,"text",$value);
			//TODO check getField
			$input->setName($name);
			return $input;
		}, $index,$attributes,"input");
	}

	public function fieldAsHidden($index,$attributes=NULL){
		if(\is_array($attributes)===false){
			$attributes=[];
		}
		$attributes["imputType"]="hidden";
		return $this->fieldAsInput($index,$attributes);
	}

	public function fieldAsCheckbox($index,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value){
			$input=new HtmlCheckbox($id,"",$this->_instanceViewer->getIdentifier());
			$input->setChecked(JString::isBooleanTrue($value));
			$input->getField()->setProperty("name", $name);
			return $input;
		}, $index,$attributes,"ck");
	}

	public function fieldAsDropDown($index,$elements=[],$multiple=false,$attributes=NULL){
		return $this->_fieldAs(function($id,$name,$value) use($elements,$multiple){
			$dd=new HtmlDropdown($id,$value,$elements);
			$dd->asSelect($name,$multiple);
			return $dd;
		}, $index,$attributes,"dd");
	}
}