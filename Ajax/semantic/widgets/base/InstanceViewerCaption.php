<?php
namespace Ajax\semantic\widgets\base;
use Ajax\semantic\widgets\base\InstanceViewer;

class InstanceViewerCaption extends InstanceViewer {
	protected $captions;

	public function __construct($instance=NULL,$captions=NULL){
		parent::__construct($instance);
		$this->setCaptions($captions);
	}

	public function getCaption($index){
		if($this->properties[$index] instanceof \ReflectionProperty)
			return $this->properties[$index]->getName();
		elseif(\is_callable($this->properties[$index]))
			return "";
		else
			return $this->properties[$index];
	}

	public function getCaptions(){
		if(isset($this->captions)){
			$result= $this->captions;
			for($i=\sizeof($result);$i<$this->count();$i++){
				$result[]="";
			}
			return $result;
		}
		$captions=[];
		$index=0;
		$count=$this->count();
		while($index<$count){
			$captions[]=$this->getCaption($index++);
		}
		return $captions;
	}

	public function setCaptions($captions) {
		$this->captions=$captions;
		return $this;
	}
}