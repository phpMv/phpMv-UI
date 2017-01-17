<?php
namespace Ajax\semantic\widgets\datatable;
use Ajax\service\JString;

class InstanceViewer {
	private $instance;
	private $reflect;
	private $properties;
	private $captions;
	private $visibleProperties;
	private $values;
	private $afterCompile;
	private static $index=0;

	public function __construct($instance=NULL,$captions=NULL){
		$this->values=[];
		$this->afterCompile=[];
		if(isset($instance))
			$this->setInstance($instance);
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

	public function getValues(){
		$values=[];
		$index=0;
		$count=$this->count();
		while($index<$count){
			$values[]=$this->getValue($index++);
		}
		return $values;
	}

	public function getIdentifier(){
		$value=self::$index;
		if(isset($this->values["identifier"]))
			$value=$this->values["identifier"](self::$index,$this->instance);
		self::$index++;
		return $value;
	}

	public function getValue($index){
		$property=$this->properties[$index];
		return $this->_getValue($property, $index);
	}

	private function _getValue($property,$index){
		if($property instanceof \ReflectionProperty){
			$property->setAccessible(true);
			$value=$property->getValue($this->instance);
			if(isset($this->values[$index])){
				$value= $this->values[$index]($value);
			}
		}else{
			if(\is_callable($property))
				$value=$property($this->instance);
			elseif(\is_array($property)){
				$values=\array_map(function($v) use ($index){return $this->_getValue($v, $index);}, $property);
				$value=\implode("", $values);
			}else
				$value=$property;
		}
		if(isset($this->afterCompile[$index])){
			if(\is_callable($this->afterCompile[$index])){
				$this->afterCompile[$index]($value,$this->instance,$index);
			}
		}
		return $value;
	}

	public function insertField($index,$field){
		array_splice( $this->visibleProperties, $index, 0, $field );
		return $this;
	}

	public function insertInField($index,$field){
		$vb=$this->visibleProperties;
		if(isset($vb[$index])){
			if(\is_array($vb[$index])){
				$this->visibleProperties[$index][]=$field;
			}else{
				$this->visibleProperties[$index]=[$vb[$index],$field];
			}
		}else{
			return $this->insertField($index, $field);
		}
		return $this;
	}

	public function addField($field){
		$this->visibleProperties[]=$field;
		return $this;
	}

	public function count(){
		return \sizeof($this->properties);
	}

	public function visiblePropertiesCount(){
		return \sizeof($this->visibleProperties);
	}

	private function showableProperty(\ReflectionProperty $rProperty){
		return JString::startswith($rProperty->getName(),"_")===false;
	}

	public function setInstance($instance) {
		if(\is_string($instance)){
			$instance=new $instance();
		}
		$this->instance=$instance;
		$this->properties=[];
		$this->reflect=new \ReflectionClass($instance);
		if(\sizeof($this->visibleProperties)===0){
			$this->properties=$this->getDefaultProperties();
		}else{
			foreach ($this->visibleProperties as $property){
				if(\is_callable($property)){
					$this->properties[]=$property;
				}elseif(\is_string($property)){
					try{
						$rProperty=$this->reflect->getProperty($property);
						$this->properties[]=$rProperty;
					}catch(\Exception $e){
						$this->properties[]=$property;
					}
				}elseif(\is_int($property)){
					$props=$this->getDefaultProperties();
					if(isset($props[$property]))
						$this->properties[]=$props[$property];
					else
						$this->properties[]=$property;
				}else{
					$this->properties[]=$property;
				}
			}
		}
		return $this;
	}

	private function getDefaultProperties(){
		$result=[];
		$properties=$this->reflect->getProperties();
		foreach ($properties as $property){
			$showable=$this->showableProperty($property);
			if($showable!==false){
				$result[]=$property;
			}
		}
		return $result;
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
		return $this;
	}

	public function setIdentifierFunction($callback){
		$this->values["identifier"]=$callback;
		return $this;
	}

	public static function setIndex($index) {
		self::$index=$index;
	}

	public function getProperties() {
		return $this->properties;
	}

	/**
	 * Associates a $callback function after the compilation of the field at $index position
	 * The $callback function can take the following arguments : $field=>the compiled field, $instance : the active instance of the object, $index: the field position
	 * @param int $index postion of the compiled field
	 * @param callable $callback function called after the field compilation
	 * @return \Ajax\semantic\widgets\datatable\InstanceViewer
	 */
	public function afterCompile($index,$callback){
		$this->afterCompile[$index]=$callback;
		return $this;
	}
}