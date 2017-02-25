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
		$this->defaultValueFunction=function($name,$value,$index){
			$caption=$this->getCaption($index);
			$input=new HtmlFormInput($this->widgetIdentifier."-".$name,$caption,"text",$value);
			$input->setName($name);
			return $input;
		};
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



	public function addSeparatorAfter($fieldNum){
		if(\array_search($fieldNum, $this->separators)===false)
			$this->separators[]=$fieldNum;
		return $this;
	}

	public function getSeparators() {
		return $this->separators;
	}

	public function removeSeparator($index){
		\array_splice($this->separators,$index,1);
	}

	public function removeField($index){
		parent::removeField($index);
		$pos=\array_search($index, $this->separators);
		if($pos!==false){
			for($i=$pos+1;$i<\sizeof($this->separators);$i++){
				$this->separators[$i]--;
			}
			\array_splice($this->separators, $pos, 1);
		}
		return $this;
	}

	public function setSeparators($separators) {
		$this->separators=\array_merge([-1], $separators);
		return $this;
	}

}