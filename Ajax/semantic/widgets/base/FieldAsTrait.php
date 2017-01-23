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
use Ajax\semantic\html\collections\HtmlMessage;
/**
 * @author jc
 * @property InstanceViewer $_instanceViewer
 */

trait FieldAsTrait{

	abstract protected function _getFieldIdentifier($prefix);
	abstract public function setValueFunction($index,$callback);

	private function _getLabelField($caption,$icon=NULL){
		$label=new HtmlLabel($this->_getFieldIdentifier("lbl"),$caption,$icon);
		return $label;
	}

	protected function _addRules($element,$attributes){}

	protected function _fieldAs($elementCallback,$index,$attributes=NULL,$prefix=null){
		$this->setValueFunction($index,function($value) use ($index,&$attributes,$elementCallback,$prefix){
			$name=$this->_instanceViewer->getCaption($index)."[]";
			if(isset($attributes["name"])===true){
				$name=$attributes["name"];
			}
			$element=$elementCallback($this->_getFieldIdentifier($prefix),$name,$value,"");
			if(\is_array($attributes))
				$this->_applyAttributes($element, $attributes,$index);
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

	public function fieldAsImage($index,$size=Size::SMALL,$circular=false){
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