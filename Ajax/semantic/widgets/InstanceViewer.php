<?php
namespace Ajax\semantic\widgets;
use Ajax\service\JString;

class InstanceViewer {
	private $instance;
	private $reflect;
	private $properties;
	private $captions;
	private $visibleProperties;
	private $values;

	public function __construct($instance=NULL,$captions=NULL){
		$this->values=[];
		if(isset($instance))
			$this->setInstance($instance);
		$this->setCaptions($captions);
	}

	public function getCaption($index){
		return $this->properties[$index]->getName();
	}

	public function getCaptions(){
		if(isset($this->captions)){
			return $this->captions;
		}
		$captions=[];
		$index=0;
		$count=$this->count();
		while($index<$count){
			$captions[]=$this->getCaption($index++);
		}
		return $captions;
	}

	public function getValues(){
		$values=[];
		$index=0;
		$count=$this->count();
		while($index<$count){
			$values[]=$this->getValue($index++);
		}
		return $values;
	}

	public function getValue($index){
		$this->properties[$index]->setAccessible(true);
		$value=$this->properties[$index]->getValue($this->instance);
		if(isset($this->values[$index])){
			$value= $this->values[$index]($value);
		}
		return $value;
	}

	public function count(){
		return \sizeof($this->properties);
	}

	public function showableProperty(\ReflectionProperty $rProperty){
		if(\is_array($this->visibleProperties)){
			return \array_search($rProperty->getName(), $this->visibleProperties);
		}
		return JString::startswith($rProperty->getName(),"_")===false;
	}

	public function setInstance($instance) {
		if(\is_string($instance)){
			$instance=new $instance();
		}
		$this->instance=$instance;
		$this->properties=[];
		$this->reflect=new \ReflectionClass($instance);
		$properties=$this->reflect->getProperties();
		foreach ($properties as $property){
			$showable=$this->showableProperty($property);
			if($showable!==false){
				if(\is_int($showable))
					$this->properties[$showable]=$property;
				else
					$this->properties[]=$property;
			}
		}
		return $this;
	}

	public function setCaptions($captions) {
		$this->captions=$captions;
		return $this;
	}

	public function setVisibleProperties($visibleProperties) {
		$this->visibleProperties=$visibleProperties;
		return $this;
	}

	public function setValueFunction($index,$callback){
		$this->values[$index]=$callback;
	}


}