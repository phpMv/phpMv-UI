<?php

namespace Ajax\semantic\widgets\dataform;

use Ajax\semantic\widgets\base\InstanceViewer;
use Ajax\service\JString;
use Ajax\semantic\html\collections\form\HtmlFormInput;

class FormInstanceViewer extends InstanceViewer {
	protected $separators;
	protected $headers;
	protected $wrappers;

	public function __construct($identifier,$instance=NULL, $captions=NULL) {
		parent::__construct($identifier,$instance, $captions);
		$this->separators=[-1];
		$this->headers=[];
		$this->wrappers=[];
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
		if(($header=$this->hasHeader($field))!==false){
			$this->addHeaderDividerBefore($index, $header);
		}
	}

	protected function hasHeader(&$field){
		$matches=[];$result=false;
		if(\preg_match('/\{(.*?)\}/s', $field, $matches)===1){
			$result=$matches[1];
			$field=\str_replace("{".$result."}","", $field);
		}
		return $result;
	}



	public function addSeparatorAfter($fieldNum){
		if(\array_search($fieldNum, $this->separators)===false)
			$this->separators[]=$fieldNum;
		return $this;
	}

	public function addHeaderDividerBefore($fieldNum,$header){
		$this->headers[$fieldNum]=$header;
		if($fieldNum>0)
			$this->addSeparatorAfter($fieldNum-1);
		return $this;
	}

	public function addWrapper($fieldNum,$contentBefore,$contentAfter=null){
		$this->wrappers[$fieldNum]=[$contentBefore,$contentAfter];
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
			$sepCount=\sizeof($this->separators);
			for($i=$pos+1;$i<$sepCount;$i++){
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

	public function getHeaders() {
		return $this->headers;
	}

	public function getWrappers() {
		return $this->wrappers;
	}

	public function setWrappers($wrappers) {
		$this->wrappers=$wrappers;
		return $this;
	}



}
