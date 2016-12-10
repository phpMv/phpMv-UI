<?php
namespace Ajax\semantic\components\validation;
/**
 * @author jc
 * @version 1.001
 * Generates a JSON Rule for the validation of a field
 */
class Rule implements \JsonSerializable{
	/**
	 * @var string
	 */
	private $type;
	/**
	 * @var string
	 */
	private $prompt;

	/**
	 * @var string
	 */
	private $value;

	public function __construct($type,$prompt=NULL,$value=NULL){
		$this->type=$type;
		$this->prompt=$prompt;
		$this->value=$value;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type=$type;
		return $this;
	}

	public function getPrompt() {
		return $this->prompt;
	}

	public function setPrompt($prompt) {
		$this->prompt=$prompt;
		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value=$value;
		return $this;
	}

	public function jsonSerialize() {
		$result= ["type"=>$this->type];
		if(isset($this->prompt))
			$result["prompt"]=$this->prompt;
		if(isset($this->value))
			$result["value"]=$this->value;
		return $result;
	}

	public static function match($name,$prompt=null){
		return new Rule("match[".$name."]",$prompt);
	}

	public static function integer($min=0,$max=100,$prompt=null){
		return new Rule("integer[{$min}..{$max}]",$prompt);
	}

	public static function decimal($prompt=null){
		return new Rule("decimal]",$prompt);
	}

	public static function number($prompt=null){
		return new Rule("number",$prompt);
	}

}