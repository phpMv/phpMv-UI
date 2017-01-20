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
use Ajax\semantic\html\base\HtmlSemDoubleElement;

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

	/**
	 * @param HtmlSemDoubleElement $element
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
		$element->fromArray($attributes);
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

	public function fieldAsAvatar($index){
		$this->setValueFunction($index,function($img){return (new HtmlImage("",$img))->asAvatar();});
		return $this;
	}


	public function fieldAsRadio($index,$attributes=NULL){
		$this->setValueFunction($index,function($value)use ($index,$attributes){
			if(isset($attributes["name"])===false){
				$attributes["name"]=$this->_instanceViewer->getCaption($index)."[]";
			}
			$radio=new HtmlRadio($this->_getFieldIdentifier("radio"),$attributes["name"],$value,$value);
			$this->_applyAttributes($radio, $attributes, $index);
			return $radio;
		});
			return $this;
	}

	public function fieldAsInput($index,$attributes=NULL){
		$this->setValueFunction($index,function($value) use($index,$attributes){
			$input=new HtmlInput($this->_getFieldIdentifier("input"),"text",$value);
			if(isset($attributes["name"])===false){
				$attributes["name"]=$this->_instanceViewer->getCaption($index)."[]";
			}
			$input->getField()->setProperty("name", $attributes["name"]);
			$this->_applyAttributes($input, $attributes, $index);
			return $input;
		});
			return $this;
	}

	public function fieldAsCheckbox($index,$attributes=NULL){
		$this->setValueFunction($index,function($value) use($index,$attributes){
			$checkbox=new HtmlCheckbox($this->_getFieldIdentifier("ck"),"",$value);
			$checkbox->setChecked(JString::isBooleanTrue($value));
			if(isset($attributes["name"])===false){
				$attributes["name"]=$this->_instanceViewer->getCaption($index)."[]";
			}
			$checkbox->getField()->setProperty("name", $attributes["name"]);
			$this->_applyAttributes($checkbox, $attributes, $index);
			return $checkbox;
		});
			return $this;
	}

	public function fieldAsDropDown($index,$elements=[],$multiple=false,$attributes=NULL){
		$this->setValueFunction($index,function($value) use($index,$elements,$multiple,$attributes){
			$dd=new HtmlDropdown($this->_getFieldIdentifier("dd"),$value,$elements);
			if(isset($attributes["name"])===false){
				$attributes["name"]=$this->_instanceViewer->getCaption($index)."[]";
			}
			$dd->asSelect($attributes["name"],$multiple);
			$this->_applyAttributes($dd, $attributes, $index);
			return $dd;
		});
			return $this;
	}
}