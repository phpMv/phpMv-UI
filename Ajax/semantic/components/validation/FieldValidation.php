<?php
namespace Ajax\semantic\components\validation;
use Ajax\service\JArray;

/**
 * @author jc
 * @version 1.001
 * Generates a JSON field validator
 */
class FieldValidation implements \JsonSerializable{
	/**
	 * @var string
	 */
	protected  $identifier;
	/**
	 * @var array array of Rules
	 */
	protected $rules;

	/**
	 * @var string
	 */
	protected $depends;

	protected $optional;

	public function __construct($identifier){
		$this->identifier=$identifier;
		$this->rules=[];
	}

	public function getIdentifier() {
		return $this->identifier;
	}

	public function setIdentifier($identifier) {
		$this->identifier=$identifier;
		return $this;
	}

	public function getRules() {
		return $this->rules;
	}

	public function addRule($type,$prompt=NULL,$value=NULL){
		if($type instanceof  Rule)
			$this->rules[]=$type;
		else if(\is_array($type)){
			$value=JArray::getValue($type, "value", 2);
			$prompt=JArray::getValue($type, "prompt", 1);
			$type=JArray::getValue($type, "type", 0);
			$this->rules[]=new Rule($type,$prompt,$value);
		}else
			$this->rules[]=new Rule($type,$prompt,$value);
	}

	public function jsonSerialize(){
		return ["identifier"=>$this->identifier,"rules"=>$this->rules];
	}

	public function setDepends($depends) {
		$this->depends=$depends;
		return $this;
	}

	public function setOptional($optional) {
		$this->optional=$optional;
		return $this;
	}

}
