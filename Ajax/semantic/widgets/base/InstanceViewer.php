<?php
namespace Ajax\semantic\widgets\base;
use Ajax\service\JString;

class InstanceViewer {
	protected $widgetIdentifier;
	protected $instance;
	protected $reflect;
	protected $properties;
	protected $visibleProperties;
	protected $values;
	protected $afterCompile;
	protected $captions;
	protected $captionCallback;
	protected $defaultValueFunction;


	public static $index=0;

	public function __construct($identifier,$instance=NULL,$captions=NULL){
		$this->widgetIdentifier=$identifier;
		$this->values=[];
		$this->afterCompile=[];
		if(isset($instance))
			$this->setInstance($instance);
		$this->setCaptions($captions);
		$this->captionCallback=NULL;
		$this->defaultValueFunction=function($name,$value){return $value;};
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

	public function getIdentifier($index=NULL){
		if(!isset($index))
			$index=self::$index;
		$value=$index;
		if(isset($this->values["identifier"]))
			$value=$this->values["identifier"]($index,$this->instance);
		return $value;
	}

	public function getValue($index){
		$property=$this->properties[$index];
		return $this->_getValue($property, $index);
	}

	protected function _beforeAddProperty($index,&$field){

	}

	protected function _getDefaultValue($name,$value,$index){
		$func=$this->defaultValueFunction;
		return $func($name,$value,$index,$this->instance);
	}

	protected function _getPropertyValue(\ReflectionProperty $property,$index){
		$property->setAccessible(true);
		$value=$property->getValue($this->instance);
		if(isset($this->values[$index])){
			$value= $this->values[$index]($value,$this->instance,$index);
		}else{
			$value=$this->_getDefaultValue($property->getName(),$value, $index);
		}
		return $value;
	}

	protected function _getValue($property,$index){
		$value=null;
		if($property instanceof \ReflectionProperty){
			$value=$this->_getPropertyValue($property, $index);
		}else{
			if(\is_callable($property))
				$value=$property($this->instance);
			elseif(\is_array($property)){
				$values=\array_map(function($v) use ($index){return $this->_getValue($v, $index);}, $property);
				$value=\implode("", $values);
			}else{
				if(isset($this->values[$index])){
					$value= $this->values[$index]($property,$this->instance,$index);
				}elseif(isset($this->instance->{$property})){
					$value=$this->instance->{$property};
				}
			}
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

	public function getProperty($index){
		return $this->properties[$index];
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


	protected function showableProperty(\ReflectionProperty $rProperty){
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
				$this->setInstanceProperty($property);
			}
		}
		return $this;
	}

	private function setInstanceProperty($property){
		if(\is_callable($property)){
			$this->properties[]=$property;
		}elseif(\is_string($property)){
			try{
				$this->_beforeAddProperty(\sizeof($this->properties), $property);
				$rProperty=$this->reflect->getProperty($property);
				$this->properties[]=$rProperty;
			}catch(\Exception $e){
				$this->_beforeAddProperty(\sizeof($this->properties), $property);
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

	protected function getDefaultProperties(){
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

	public function getCaption($index){
		if(isset($this->captions[$index])){
			return $this->captions[$index];
		}
		if($this->properties[$index] instanceof \ReflectionProperty)
			return $this->properties[$index]->getName();
		elseif(\is_callable($this->properties[$index]))
			return "";
		else
			return $this->properties[$index];
	}

	public function getCaptions(){
		if(isset($this->captions)){
			$captions= $this->captions;
			for($i=\sizeof($captions);$i<$this->count();$i++){
				$captions[]="";
			}
		}else{
			$captions=[];
			$index=0;
			$count=$this->count();
			while($index<$count){
				$captions[]=$this->getCaption($index++);
			}
		}
		if(isset($this->captionCallback) && \is_callable($this->captionCallback)){
			$callback=$this->captionCallback;
			$callback($captions,$this->instance);
		}
		return $captions;
	}

	public function setCaption($index,$caption){
		if(isset($this->captions)===false)
			$this->captions=[];
		$this->captions[$index]=$caption;
		return $this;
	}

	public function setCaptions($captions) {
		$this->captions=$captions;
		return $this;
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

	/**
	 * Defines a callback function to call for modifying captions
	 * function parameters are $captions: the captions to modify and $instance: the active model instance
	 * @param callable $captionCallback
	 * @return \Ajax\semantic\widgets\base\InstanceViewer
	 */
	public function setCaptionCallback($captionCallback) {
		$this->captionCallback=$captionCallback;
		return $this;
	}

	/**
	 * Defines the default function which displays fields value
	 * @param callable $defaultValueFunction function parameters are : $name : the field name, $value : the field value ,$index : the field index, $instance : the active instance of model
	 * @return \Ajax\semantic\widgets\base\InstanceViewer
	 */
	public function setDefaultValueFunction($defaultValueFunction) {
		$this->defaultValueFunction=$defaultValueFunction;
		return $this;
	}


}