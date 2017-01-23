<?php

namespace Ajax\semantic\widgets\dataform;

use Ajax\semantic\widgets\base\InstanceViewer;
use Ajax\service\JString;
use Ajax\semantic\html\collections\form\HtmlFormInput;

class FormInstanceViewer extends InstanceViewer {
	protected $separators;

	public function __construct($identifier,$instance=NULL, $captions=NULL) {
		parent::__construct($identifier,$instance=NULL, $captions=NULL);
		$this->separators=[-1];
	}

	protected function _beforeAddProperty($index,&$field){
		if(JString::endswith($field, "\n")===true){
			$this->addSeparatorAfter($index);
		}
		if($index>1 && JString::startswith($field, "\n")===true){
			$this->addSeparatorAfter($index-1);
		}
		$field=\str_replace("\n", "", $field);
	}

	protected function _getDefaultValue($name,$value,$index){
		$caption=$this->getCaption($index);
		$input=new HtmlFormInput($this->widgetIdentifier."-".$name,$caption,"text",$value);
		$input->setName($name);
		return $input;
	}

	public function getFieldName($index){
		$property=$this->getProperty($index);
		if($property instanceof \ReflectionProperty){
			$result=$property->getName();
		}elseif(\is_callable($property)){
			$result=$this->visibleProperties[$index];
		}else{
			$result=$property;
		}
		return $result;
	}

	public function addSeparatorAfter($fieldNum){
		if(\array_search($fieldNum, $this->separators)===false)
			$this->separators[]=$fieldNum;
			return $this;
	}

	public function getSeparators() {
		return $this->separators;
	}

	public function setSeparators($separators) {
		$this->separators=\array_merge([-1], $separators);
		return $this;
	}

}