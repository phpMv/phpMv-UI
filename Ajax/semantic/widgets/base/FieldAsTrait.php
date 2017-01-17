<?php
namespace Ajax\semantic\widgets\base;
use Ajax\semantic\html\elements\HtmlInput;
use Ajax\semantic\html\modules\checkbox\HtmlCheckbox;
use Ajax\service\JString;
use Ajax\semantic\html\modules\HtmlDropdown;
use Ajax\semantic\html\elements\HtmlImage;
use Ajax\semantic\html\modules\checkbox\HtmlRadio;
use Ajax\semantic\html\base\constants\Size;
use Ajax\semantic\widgets\datatable\InstanceViewer;

/**
 * @author jc
 * @property InstanceViewer $_instanceViewer
 */

trait FieldAsTrait{

	protected abstract function _getFieldIdentifier($prefix);
	public abstract function setValueFunction($index,$callback);

	public function fieldAsImage($index,$size=Size::SMALL,$circular=false){
		$this->setValueFunction($index,function($img) use($size,$circular){
			$image=new HtmlImage($this->_getFieldIdentifier("image"),$img);$image->setSize($size);if($circular)$image->setCircular();
			return $image;
		}
		);
			return $this;
	}

	public function fieldAsAvatar($index){
		$this->setValueFunction($index,function($img){return (new HtmlImage("",$img))->asAvatar();});
		return $this;
	}

	public function fieldAsRadio($index,$name=NULL){
		$this->setValueFunction($index,function($value)use ($index,$name){
			if(isset($name)===false){
				$name=$this->_instanceViewer->getCaption($index)."[]";
			}
			$radio=new HtmlRadio($this->_getFieldIdentifier("radio"),$name,$value,$value);
			return $radio;
		}
		);
			return $this;
	}

	public function fieldAsInput($index,$name=NULL,$type="text",$placeholder=""){
		$this->setValueFunction($index,function($value) use($index,$name,$type,$placeholder){
			$input=new HtmlInput($this->_getFieldIdentifier("input"),$type,$value,$placeholder);
			if(isset($name)===false){
				$name=$this->_instanceViewer->getCaption($index)."[]";
			}
			$input->getField()->setProperty("name", $name);
			$input->setFluid();
			return $input;
		}
		);
			return $this;
	}

	public function fieldAsCheckbox($index,$name=NULL){
		$this->setValueFunction($index,function($value) use($index,$name){
			$checkbox=new HtmlCheckbox($this->_getFieldIdentifier("ck"),"",$value);
			$checkbox->setChecked(JString::isBooleanTrue($value));
			if(isset($name)===false){
				$name=$this->_instanceViewer->getCaption($index)."[]";
			}
			$checkbox->getField()->setProperty("name", $name);
			return $checkbox;}
			);
			return $this;
	}

	public function fieldAsDropDown($index,$elements=[],$multiple=false,$name=NULL){
		$this->setValueFunction($index,function($value) use($index,$elements,$multiple,$name){
			$dd=new HtmlDropdown($this->_getFieldIdentifier("dd"),$value,$elements);
			if(isset($name)===false){
				$name=$this->_instanceViewer->getCaption($index)."[]";
			}
			$dd->asSelect($name,$multiple);
			return $dd;}
			);
			return $this;
	}
}